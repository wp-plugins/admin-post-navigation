=== Admin Post Navigation ===
Contributors: coffee2code
Donate link: http://coffee2code.com/donate
Tags: admin, navigation, post, next, previous, edit, coffee2code
Requires at least: 2.6
Tested up to: 2.9.1
Stable tag: 1.1.1
Version: 1.1.1

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

== Changelog ==

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
