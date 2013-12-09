<?php
/*
Plugin Name: Admin Column View
Plugin URI: http://crowdfavorite/wordpress/plugins/
Description: Adds a columnar view of all your pages (and hierarchical custom post types), similar to the view found in the OS X Finder. Makes it much easier to manage sites with lots of pages. Drag and drop to re-order your pages.
Version: 1.0.3
Author: Crowd Favorite
Author URI: http://crowdfavorite.com/
License: GPL2
*/

if (!class_exists('CF_Admin_Column_View')) {

load_plugin_textdomain('admin-column-view', false, dirname(plugin_basename(__FILE__)).'/languages/');

// add to menu
add_action('admin_menu', array('CF_Admin_Column_View', 'add_submenu_page'));

// controllers
add_action('wp_ajax_cf-admin-column-view-column', array('CF_Admin_Column_View', 'controller_action_column'));
add_action('wp_ajax_cf-admin-column-view-sort', array('CF_Admin_Column_View', 'controller_action_sort'));

// set post_parent
add_action('add_meta_boxes', array('CF_Admin_Column_View', 'set_post_parent'), 10, 2);

class CF_Admin_Column_View {

	static function add_submenu_page() {

		// get all hierarchical post types
		$post_types = get_post_types(array(
			'hierarchical' => true,
			'show_ui' => true,
		), 'objects');

		$menu_label = _x('Column View', 'name in menu', 'cf-admin-column-view');

		foreach ($post_types as $post_type => $post_type_obj) {
			add_submenu_page(
				'edit.php?post_type='.$post_type,
				sprintf(_x('%1$s Column View', 'title on admin page', 'cf-admin-column-view'), $post_type_obj->labels->name),
				$menu_label,
				$post_type_obj->cap->edit_posts,
				'cf-admin-column-view-'.$post_type,
				array('CF_Admin_Column_View', 'admin_page')
			);
		}
	}
	
	static function admin_page() {
		wp_enqueue_script('jquery-ui-sortable');
		if (empty($_GET['post_type'])) {
			wp_die(_x('Whoops!', 'random error happened', 'cf-admin-column-view'));
		}
		$post_type = stripslashes($_GET['post_type']);
		$column_data = self::column_data($post_type, 0);
		include('views/admin-page.php');
	}
	
	static function nonce_action_sort($post_type = 'page', $parent_id = 0) {
		return 'sort-'.$post_type.'-'.$parent_id;
	}
	
	static function column_data($post_type = 'page', $parent_id = 0) {
		$post_statuses = array('publish', 'pending', 'draft', 'future', 'private');
		// get column pages
		add_filter('posts_fields', array('CF_Admin_Column_View', 'column_data_fields'));
		add_filter('posts_orderby', array('CF_Admin_Column_View', 'column_data_orderby'));
		$query = new WP_Query(array(
			'orderby' => 'menu_order',
			'post_type' => $post_type,
			'post_parent' => $parent_id,
			'posts_per_page' => -1,
			'status' => $post_statuses,
		));
		remove_filter('posts_fields', array('CF_Admin_Column_View', 'column_data_fields'));
		remove_filter('posts_orderby', array('CF_Admin_Column_View', 'column_data_orderby'));
		// check for sub-pages
		$has_children = array();
		if (!empty($query->posts)) {
			$post_ids = array();
			foreach ($query->posts as $post) {
				$post_ids[] = $post->ID;
			}
			global $wpdb;
			$result = $wpdb->get_results($wpdb->prepare("
				SELECT post_parent
				FROM $wpdb->posts
				WHERE post_parent IN (".implode(',', $post_ids).")
				AND post_type = %s
				AND post_status IN ('".implode("','", $post_statuses)."')
				GROUP BY post_parent
			", $post_type));
			if (!empty($result)) {
				foreach ($result as $data) {
					$has_children[] = $data->post_parent;
				}
			}
			foreach ($query->posts as &$post) {
				$post->has_children = in_array($post->ID, $has_children);
			}
		}
		$column_data = array(
			'items' => $query->posts,
			'nonce' => wp_create_nonce(self::nonce_action_sort($post_type, $parent_id)),
			'parent_id' => $parent_id,
			'post_type' => $post_type,
		);
		return $column_data;
	}
	
	static function column_data_fields($fields) {
		global $wpdb;
		return "$wpdb->posts.ID, $wpdb->posts.post_title, $wpdb->posts.post_type, $wpdb->posts.menu_order, $wpdb->posts.post_status, $wpdb->posts.post_password";
	}
	
	static function column_data_orderby($fields) {
		global $wpdb;
		return "$wpdb->posts.menu_order, $wpdb->posts.post_title";
	}
	
	static function controller_action_column() {
		// get post type
		$post_type_obj = $post_type = false;
		if (!empty($_GET['post_type'])) {
			$post_type = stripslashes($_GET['post_type']);
			$post_type_obj = get_post_type_object($post_type);
		}

		// check permissions
		if (!$post_type || !current_user_can($post_type_obj->cap->edit_posts)) {
			wp_die("Cheatin', eh?");
		}

		// get data and load view
		$column_data = self::column_data($post_type, intval($_GET['parent_id']));
		ob_start();
		include('views/column.php');
		$html = ob_get_clean();

		// output
		header('Content-type: application/json');
		echo json_encode(array(
			'result' => 'success',
			'html' => $html,
		));
		die();
	}

	static function controller_action_sort() {
		// get post type
		$post_type_obj = $post_type = false;
		if (!empty($_POST['post_type'])) {
			$post_type = stripslashes($_POST['post_type']);
			$post_type_obj = get_post_type_object($post_type);
		}

		// check nonce and permissions
		$nonce_action = self::nonce_action_sort($post_type, intval($_POST['parent_id']));
		if (!$post_type ||
			!current_user_can($post_type_obj->cap->edit_posts) ||
			!wp_verify_nonce(stripslashes($_POST['nonce']), $nonce_action)) {
			wp_die("Cheatin', eh?");
		}

		// set menu order for each post
		if (!empty($_POST['order']) && is_array($_POST['order'])) {
			foreach ($_POST['order'] as $data) {
				wp_update_post(array(
					'ID' => $data[0],
					'menu_order' => $data[1]
				));
			}
		}

		// output
		header('Content-type: application/json');
		echo json_encode(array(
			'result' => 'success',
		));
		die();
	}
	
	static function set_post_parent($post_type, $post) {
		global $post;
		if (isset($_GET['post_parent'])) {
			$post->post_parent = intval($_GET['post_parent']);
		}
	}

}

} // end class_exists() check