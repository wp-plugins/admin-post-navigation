=== Admin Post Navigation ===
Contributors: coffee2code
Donate link: http://coffee2code.com/donate
Tags: admin, navigation, post, next, previous, edit, coffee2code
Requires at least: 2.8
Tested up to: 3.0.1
Stable tag: 1.5
Version: 1.5

Adds links to the next and previous posts when editing a post in the WordPress admin.


== Description ==

Adds links to the next and previous posts when editing a post in the WordPress admin.

This plugin adds "<< Previous" and "Next >>" links to the "Edit Post" admin page, if a previous and next post are present, respectively.  The link titles (visible when hovering over the links) reveal the title of the previous/next post.  The links link to the "Edit Post" admin page for the previous/next posts so that you may edit them.

Currently, a previous/next post is determined by the next lower/higher valid post based on relative sequential post ID and which the user can edit.  Other post criteria such as post type (draft, pending, etc), publish date, post author, category, etc, are not taken into consideration when determining the previous or next post.

NOTE: Be sure to save the post currently being edited before navigating away to the previous/next post.


== Installation ==

1. Unzip `admin-post-navigation.zip` inside the `/wp-content/plugins/` directory for your site (or install via the built-in WordPress plugin installer)
1. Activate the plugin through the 'Plugins' admin menu in WordPress


== Screenshots ==

1. A screenshot of the previous/next links adjacent to the 'Edit Post' admin page header when Javascript is enabled.
2. A screenshot of the previous/next links in their own 'Edit Post' admin page sidebar panel when Javascript is disabled for the admin user.


== Filters ==

The plugin is further customizable via three filters. Typically, these customizations would be put into your active theme's functions.php file, or used by another plugin.

= c2c_admin_post_navigation_orderby =

The 'c2c_admin_post_navigation_orderby' filter allows you to change the post field used in the ORDER BY clause for the SQL to find the previous/next post.  By default this is 'ID'.  If you wish to change this, hook this filter.  This is not typical usage for most users.

Arguments:

* $field (string) The current ORDER BY field

Example:

`add_filter( 'c2c_admin_post_navigation_orderby', 'order_apn_by_post_date' );
function order_apn_by_post_date( $field ) {
	return 'post_date';
}`

= c2c_admin_post_navigation_post_statuses =

The 'c2c_admin_post_navigation_post_statuses' filter allows you to modify the list of post_statuses used as part of the search for the prev/next post.  By default this array includes 'draft', 'future', 'pending', 'private', and 'publish'.  If you wish to change this, hook this filter.  This is not typical usage for most users.

Arguments:

* $post_statuses (array) The array of valid post_statuses

Example:

`add_filter( 'c2c_admin_post_navigation_post_statuses', 'change_apn_post_status' );
function change_apn_post_status( $post_statuses ) {
	$post_statuses[] = 'trash'; // Adding a post status
	if ( isset( $post_statuses['future'] ) ) unset( $post_statuses['future'] ); // Removing a post status
	return $post_statuses;
}`

= c2c_admin_post_navigation_display =

The 'c2c_admin_post_navigation_display' filter allows you to customize the output links for the post navigation.

Arguments:

* $text (string) The current output for the prev/next navigation link

Example:

`add_filter( 'c2c_admin_post_navigation_display', 'override_apn_display' );
function override_apn_display( $text ) {
	// Simplistic example. You could preferably make the text bold using CSS.
	return '<strong>' . $text . '</strong>';
}`


== Changelog ==

= 1.5 =
* Change post search ORDER BY from 'post_date' to 'ID'
* Add filter 'c2c_admin_post_navigation_orderby' for customizing search ORDER BY field
* Add filter 'c2c_admin_post_navigation_post_statuses' for customizing valid post_statuses for search
* Deprecate (but still support) 'admin_post_nav' filter
* Add filter 'c2c_admin_post_navigation_display' filter as replacement to 'admin_post_nav' filter to allow modifying output
* Retrieve post title via get_the_title() rather than directly from object
* Also strip tags from the title prior to use in tag attribute
* Don't navigate to auto-saves
* Check for is_admin() before defining class rather than during constructor
* esc_sql() on SQL strings that have potentially been filtered
* Use esc_attr() instead of attribute_escape()
* Store plugin instance in global variable, $c2c_admin_post_navigation, to allow for external manipulation
* Fix localization of the two strings
* Instantiate object within primary class_exists() check
* Note compatibility with WP 3.0+
* Drop compatibility with version of WP older than 2.8
* Minor code reformatting (spacing)
* Remove docs from top of plugin file (all that and more are in readme.txt)
* Remove trailing whitespace in header docs
* Add Upgrade Notice and Filters sections to readme.txt
* Add package info to top of plugin file

= 1.1.1 =
* Add PHPDoc documentation
* Note compatibility with WP 2.9+
* Update copyright date
* Update readme.txt (including adding Changelog)

= 1.1 =
* Add offset and limit arguments to query()
* Only get ID and post_title fields in query, not *
* Change the previous/next post query to ensure it only gets posts the user can edit
* Note compatibility with WP 2.8+

= 1.0 =
* Initial release


== Upgrade Notice ==

= 1.5 =
Recommended update. Highlights: find prev/next post by ID rather than post_date, added verified WP 3.0 compatibility.