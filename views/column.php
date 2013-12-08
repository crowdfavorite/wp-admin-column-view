<div class="cf-admin-column-view-column" data-post_type="<?php echo esc_attr($column_data['post_type']); ?>" data-parent_id="<?php echo esc_attr($column_data['parent_id']); ?>" data-nonce="<?php echo esc_attr($column_data['nonce']); ?>">
<?php

if (!empty($column_data['items'])) {
	foreach ($column_data['items'] as $item) {
		$status_class = 'cf-admin-column-view-item-status-'.$item->post_status;
		if (!empty($item->post_password)) {
			$status_class = 'cf-admin-column-view-item-status-password';
		}
		$has_children = ($item->has_children ? '<span class="has-children">&rsaquo;</span>' : '');
?>
	<div class="cf-admin-column-view-item <?php echo $status_class; ?>" data-post_id="<?php echo esc_attr($item->ID); ?>" data-post_type="<?php echo esc_attr($item->post_type); ?>">
		<span class="name"><?php echo esc_html($item->post_title).$has_children; ?></span>
		<span class="edit"><?php echo edit_post_link(_x('Edit', 'edit item link', 'cf-admin-column-view'), '', '', $item->ID); ?></span>
	</div>
<?php
	}
}
else {
?>
	<div class="cf-admin-column-view-empty"><?php _ex('No child pages', 'empty list label', 'cf-admin-column-view'); ?></div>
<?php
}

?>
</div>
