=== Admin Post Navigation ===
Contributors: Scott Reilly
Donate link: http://coffee2code.com
Tags: admin, navigation, post, next, previous, edit
Requires at least: 2.6
Tested up to: 2.7.1
Stable tag: trunk
Version: 1.0

Adds links to the next and previous posts when editing a post in the WordPress admin.

== Description ==

Adds links to the next and previous posts when editing a post in the WordPress admin.

This plugin adds "<< Previous" and "Next >>" links to the "Edit Post" admin page, if a previous and next post are present, respectively.  The link titles (visible when hovering over the links) reveal the title of the previous/next post.  The links link to the "Edit Post" admin page for the previous/next posts so that you may edit them.

Currently, a previous/next post is determined by the next lower/higher valid post based on relative sequential post ID.  Other post criteria such as post type (draft, pending, etc), publish date, post author, category, etc, are not taken into consideration when determining the previous or next post.

NOTE: Be sure to save the post currently being edited before navigating away to the previous/next post.


== Installation ==

1. Unzip `admin-post-navigation-v1.0.zip` inside the `/wp-content/plugins/` directory for your site
1. Activate the plugin through the 'Plugins' admin menu in WordPress

== Screenshots ==

1. A screenshot of the previous/next links adjacent to the 'Edit Post' admin page header when Javascript is enabled.
2. A screenshot of the previous/next links in their own 'Edit Post' admin page sidebar panel when Javascript is disabled for the admin user.


