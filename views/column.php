<?php

$post_type_obj = get_post_type_object($column_data['post_type']);

?>
<div class="cf-admin-column-view-column" data-post_type="<?php echo esc_attr($column_data['post_type']); ?>" data-parent_id="<?php echo esc_attr($column_data['parent_id']); ?>" data-nonce="<?php echo esc_attr($column_data['nonce']); ?>">
<?php

if (!empty($column_data['items'])) {
	foreach ($column_data['items'] as $item) {
		$status_class = 'cf-admin-column-view-item-status-'.$item->post_status;
		if (!empty($item->post_password)) {
			$status_class = 'cf-admin-column-view-item-status-password';
		}

		$has_children = ($item->has_children ? '<span class="has-children">&rsaquo;</span>' : '');

		// create a hint if we're showing an unpublished or private status
		$hint = '';
		if ($item->post_status != 'publish') {
			$hint = _x('Status: not published', 'hover text hint', 'cf-admin-column-view');
		}
		if ($item->post_status == 'private' || !empty($item->post_password)) {
			$hint = _x('Status: private or password-protected', 'hover text hint', 'cf-admin-column-view');
		}
?>
	<div class="cf-admin-column-view-item <?php echo $status_class; ?>" data-post_id="<?php echo esc_attr($item->ID); ?>" data-post_type="<?php echo esc_attr($item->post_type); ?>">
		<span class="name"><?php echo esc_html($item->post_title).$has_children; ?></span>
		<span class="edit"><?php echo edit_post_link(_x('Edit', 'edit item link', 'cf-admin-column-view'), '', '', $item->ID); ?></span>
		<span class="hint" title="<?php echo esc_attr($hint); ?>"></span>
	</div>
<?php
	}
}
else {
?>
	<div class="cf-admin-column-view-empty"><?php printf(_x('No %s at this level', 'empty list label', 'cf-admin-column-view'), strtolower($post_type_obj->labels->name)); ?></div>
<?php
}

$new_link_text = sprintf(
	_x('New %s Here', 'link in column', 'cf-admin-column-view'),
	$post_type_obj->labels->singular_name
);

?>
	<div class="spacer"></div>
	<a href="<?php echo esc_url(admin_url('post-new.php?post_type='.$column_data['post_type'].'&post_parent='.$column_data['parent_id'])); ?>" class="add"><?php echo $new_link_text; ?></a>
</div>
