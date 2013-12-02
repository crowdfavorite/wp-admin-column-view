<div class="cf-admin-column-view-item" data-post_id="<?php echo esc_attr($item->ID); ?>" data-post_type="<?php echo esc_attr($item->post_type); ?>">
	<span class="name"><?php echo esc_html($item->post_title); ?></span>
	<span class="edit"><?php echo edit_post_link(_x('Edit', 'edit item link', 'cf-admin-column-view'), '', '', $item->ID); ?></span>
</div>