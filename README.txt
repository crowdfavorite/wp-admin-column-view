=== Admin Column View ===
Contributors: alexkingorg, crowdfavorite
Tags: cms, page, pages, manage, admin, column, view
Requires at least: 3.7
Tested up to: 3.7.1
Stable tag: 1.0.3
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds a columnar view of all your pages (and hierarchical custom post types), similar to the view found in the OS X Finder.

== Description ==

Adds a columnar view of all your pages (and hierarchical custom post types), similar to the view found in the OS X Finder. Makes it much easier to manage sites with lots of pages/custom post types.

Use drag and drop to re-order (set the "menu_order" property) your pages/custom post types.

There is a link at each level to create a new page/custom post type at that level.

**Contributing**

The development home for this plugin is on GitHub. This is where active development happens, along with issue tracking and associated discussions.

https://github.com/crowdfavorite/wp-admin-column-view

**Support**

Support for this plugin will be provided in the form of _Product Support_. This means that we intend to fix any confirmed bugs and improve the user experience when enhancements are identified and can reasonably be accomodated. There is no _User Support_ provided for this plugin. If you are having trouble with this plugin in your particular installation of WordPress, we will not be able to help you troubleshoot the problem.

This plugin is provided under the terms of the GPL, including the following:

> BECAUSE THE PROGRAM IS LICENSED FREE OF CHARGE, THERE IS NO WARRANTY
> FOR THE PROGRAM, TO THE EXTENT PERMITTED BY APPLICABLE LAW.  EXCEPT WHEN
> OTHERWISE STATED IN WRITING THE COPYRIGHT HOLDERS AND/OR OTHER PARTIES
> PROVIDE THE PROGRAM "AS IS" WITHOUT WARRANTY OF ANY KIND, EITHER EXPRESSED
> OR IMPLIED, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
> MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE.  THE ENTIRE RISK AS
> TO THE QUALITY AND PERFORMANCE OF THE PROGRAM IS WITH YOU.  SHOULD THE
> PROGRAM PROVE DEFECTIVE, YOU ASSUME THE COST OF ALL NECESSARY SERVICING,
> REPAIR OR CORRECTION.

== Installation ==

1. Upload the `admin-column-view/ directory` to your `/wp-content/plugins/` directory or install via the WordPress web interface
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Access the Column View for pages (or any hierarchical post type) using the `Column View` admin menu item under that section

== Frequently Asked Questions ==

= Does this show draft posts/pages as well? =

Yes. Drafts, private posts and published posts/pages are all shown in the list.

= Is this compatible with the WordPress 3.8 admin refresh/MP6 plugin? =

You betcha, it was designed for it.

== Screenshots ==

1. In action in WordPress 3.8/MP6.

== Changelog ==

= 1.0.3 =
* (fix) Use `$wpdb->posts` instead of hard-coded wp_posts in SQL query filters. (thanks deckerweb)
* (fix) Handle some errant scrollbar issues.
* (fix) Handle positioning of Edit link with/without scrollbars

= 1.0.2 =
* (fix) Accept both expected params to `set_post_parent()` method.

= 1.0.1 =
* (fix) Proper spacing from page title.

= 1.0 =
* Initial public release.

== Upgrade Notice ==

= 1.0.3 =
* (fix) Use `$wpdb->posts` instead of hard-coded wp_posts in SQL query filters. (thanks deckerweb)
* (fix) Handle some errant scrollbar issues.
* (fix) Handle positioning of Edit link with/without scrollbars