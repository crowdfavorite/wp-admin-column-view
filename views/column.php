<div class="cf-admin-column-view-column" data-post_type="<?php echo esc_attr($column_data['post_type']); ?>" data-parent_id="<?php echo esc_attr($column_data['parent_id']); ?>" data-nonce="<?php echo esc_attr($column_data['nonce']); ?>">
<?php

if (!empty($column_data['items'])) {
	foreach ($column_data['items'] as $item) {
?>
	<div class="cf-admin-column-view-item" data-post_id="<?php echo esc_attr($item->ID); ?>" data-post_type="<?php echo esc_attr($item->post_type); ?>">
		<span class="name"><?php echo esc_html($item->post_title); ?></span>
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
