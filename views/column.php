<div class="cf-admin-column-view-column" data-post_type="<?php echo esc_attr($column_data['post_type']); ?>" data-parent_id="<?php echo esc_attr($column_data['parent_id']); ?>" data-nonce="<?php echo esc_attr($column_data['nonce']); ?>">
<?php

if (!empty($column_data['items'])) {
	foreach ($column_data['items'] as $item) {
		include('item.php');
	}
}
else {
?>
	<div class="cf-admin-column-view-empty"><?php _ex('No child pages', 'empty list label', 'cf-admin-column-view'); ?></div>
<?php
}

?>
</div>
