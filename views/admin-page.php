<!--
inline styles and scripts since they are only used on this page
no additional HTTP requests needed
-->
<style>
.cf-admin-column-view-wrap {
	background: #f3f3f3; /* needed for back compat, not needed with MP6 */
	margin: 8px 0 0;
	min-height: 100px;
	overflow: auto;
	position: relative;
}
.cf-admin-column-view-content {
	position: absolute;
}
.cf-admin-column-view-column {
	background: #fff;
	float: left;
	margin-right: 2px;
	min-height: 100px;
	overflow: auto;
	overflow-x: hidden;
	width: 200px;
}
.cf-admin-column-view-column.loading {
	background: #e8e8e8;
}
.cf-admin-column-view-column .add {
	background-color: #f3f3f3;
	bottom: 0;
	display: block;
	font-size: 95%;
	height: 22px;
	line-height: 22px;
	overflow: hidden;
	position: absolute;
	text-align: center;
	text-decoration: none;
	width: 200px;
	-webkit-transition: all 0.5s ease-out;
	-moz-transition: all 0.5s ease-out;
	-ms-transition: all 0.5s ease-out;
	-o-transition: all 0.5s ease-out;
	transition: all 0.5s ease-out;
}
.cf-admin-column-view-column .add:hover {
	background-color: #ddd;
}
.cf-admin-column-view-item {
	border-left: 3px solid #fff;
	border-bottom: 1px solid #f3f3f3;
	cursor: move;
	overflow: hidden;
	position: relative;
	width: 197px;
}
.cf-admin-column-view-item.selected {
	background: #ddd;
	border-left-color: #ddd;
}
.cf-admin-column-view-item .name {
	overflow: hidden;
}
.cf-admin-column-view-item .name,
.cf-admin-column-view-item .edit a,
.cf-admin-column-view-empty {
	display: block;
	padding: 6px 10px;
}
.cf-admin-column-view-item .has-children {
	color: #bbb;
	font-size: 150%;
	font-weight: bold;
	line-height: 66%;
	padding-left: 7px;
}
.cf-admin-column-view-item .edit {
	background: #f3f3f3;
	position: absolute;
	right: -50px;
	top: 0;
	-webkit-transition: all 0.5s ease-out;
	-moz-transition: all 0.5s ease-out;
	-ms-transition: all 0.5s ease-out;
	-o-transition: all 0.5s ease-out;
	transition: all 0.5s ease-out;
}
.cf-admin-column-view-item .edit:hover {
	background-color: #ddd;
}
.cf-admin-column-view-item:hover .edit {
	right: 0;
}
.scroll .cf-admin-column-view-item:hover .edit {
	padding-right: 14px;
}
.cf-admin-column-view-item .hint {
	cursor: auto;
	height: 100%;
	left: 0;
	position: absolute;
	top: 0;
	width: 7px;
}
.cf-admin-column-view-empty {
	color: #999;
}
.cf-admin-column-view-column .spacer {
	height: 20px;
}
/* Indicate status */
.cf-admin-column-view-item-status-draft,
.cf-admin-column-view-item-status-pending,
.cf-admin-column-view-item-status-draft.selected,
.cf-admin-column-view-item-status-pending.selected {
	border-left-color: #999;
}
.cf-admin-column-view-item-status-publish {
	border-left-color: #fff;
}
.cf-admin-column-view-item-status-password,
.cf-admin-column-view-item-status-private,
.cf-admin-column-view-item-status-password.selected,
.cf-admin-column-view-item-status-private.selected {
	border-left-color: #DD3D36;
}
/* for Edit Flow compat */
.cf-admin-column-view-item-status-approved,
.cf-admin-column-view-item-status-assigned,
.cf-admin-column-view-item-status-in-progress,
.cf-admin-column-view-item-status-pending-review,
.cf-admin-column-view-item-status-approved.selected,
.cf-admin-column-view-item-status-assigned.selected,
.cf-admin-column-view-item-status-in-progress.selected,
.cf-admin-column-view-item-status-pending-review.selected {
	border-left-color: #999;
}
</style>

<div class="wrap">
	<?php screen_icon(); ?>
	<h2><?php global $title; echo $title; ?></h2>
	<div class="cf-admin-column-view-wrap">
		<div class="cf-admin-column-view-content">
<?php

include('column.php');

?>
		</div>
	</div>
</div>

<script>
var cfAdminColumnView = {};

cfAdminColumnView.sizeWrap = function() {
	var $ = jQuery,
		height = $(window).height() - 200
		colContentHeight = 0;

	$('.cf-admin-column-view-wrap').each(function() {
		$(this).css('height', height + 'px')
			.find('.cf-admin-column-view-column').each(function() {
				colContentHeight = 0;
				$(this).removeClass('scroll')
					.find('.cf-admin-column-view-item').each(function() {
					colContentHeight += $(this)[0].offsetHeight;
				});
				$(this).css('height', height + 'px');
				// add scroll class if needed
				if (colContentHeight > height) {
					$(this).addClass('scroll');
				}
			});
	});
};

cfAdminColumnView.sortable = function() {
	var $ = jQuery
		$cols = $('.cf-admin-column-view-column');
	
	$cols.filter('.ui-sortable').sortable('destroy').end()
		.sortable({
			axis: 'y',
			items: '.cf-admin-column-view-item',
			update: function(event, ui) {
				var $col = $(ui.item).closest('.cf-admin-column-view-column')
					$items = $col.find('.cf-admin-column-view-item'),
					order = [],
					i = 0;
				
				// prep data
				$items.each(function() {
					order.push([$(this).data('post_id'), i]);
					i++;
				});
				
				// send back to server
				$.post(
					ajaxurl,
					{
						action: 'cf-admin-column-view-sort',
						nonce: $col.data('nonce'),
						order: order,
						parent_id: $col.data('parent_id'),
						post_type: $col.data('post_type')
					},
					function(response) {
					}
				);
			}
		});
};

jQuery(function($) {
	// size container
	cfAdminColumnView.sizeWrap();
	$(window).on('resize', cfAdminColumnView.sizeWrap);

	// attach sortables
	cfAdminColumnView.sortable();

	// attach click events
	$(document).on('click', '.cf-admin-column-view-item .name', function() {
		var $wrap = $('.cf-admin-column-view-wrap'),
			$content = $('.cf-admin-column-view-content'),
			$col = $(this).closest('.cf-admin-column-view-column'),
			$item = $(this).closest('.cf-admin-column-view-item'),
			$cols,
			width,
			left;

		// set selected state
		$(this).closest('.cf-admin-column-view-item').addClass('selected')
			.siblings().removeClass('selected');

		// make AJAX call to get child pages
		$.get(
			ajaxurl,
			{
				action: 'cf-admin-column-view-column',
				parent_id: $item.data('post_id'),
				post_type: $item.data('post_type')
			},
			function(response) {
				if (response.result == 'success') {
					// calc width
					var $cols = $content.find('.cf-admin-column-view-column');
						width = $cols.size() * 202,
						left = width - $wrap.width();
					if (left < 0) {
						left = 0;
					}
					// remove spinner & load column
					$content.find('.loading').remove().end()
						.append(response.html)
					cfAdminColumnView.sizeWrap();
					cfAdminColumnView.sortable();
				}
			}
		);

		// remove any columns as necessary & show spinner
		$col.nextAll().remove().end()
			.after('<div class="cf-admin-column-view-column loading"></div>');
		$cols = $content.find('.cf-admin-column-view-column');
		width = $cols.size() * 202
		left = width - $wrap.width();
		if (left < 0) {
			left = 0;
		}
		$content.css('width', width)
			.animate({ left: '-' + left }, 400);
		;

		cfAdminColumnView.sizeWrap();
	});
});
</script>
