<?php
/*
Plugin Name: Admin Post Navigation
Version: 1.0
Plugin URI: http://coffee2code.com/wp-plugins/admin-post-navigation
Author: Scott Reilly
Author URI: http://coffee2code.com
Description: Adds links to the next and previous posts when editing a post in the WordPress admin.

This plugin adds "<< Previous" and "Next >>" links to the "Edit Post" admin page, if a previous and next post are
present, respectively.  The link titles (visible when hovering over the links) reveal the title of the previous/next
post.  The links link to the "Edit Post" admin page for the previous/next posts so that you may edit them.

Currently, a previous/next post is determined by the next lower/higher valid post based on relative sequential post ID.
Other post criteria such as post type (draft, pending, etc), publish date, post author, category, etc, are not taken
into consideration when determining the previous or next post.
    
NOTE: Be sure to save the post currently being edited before navigating away to the previous/next post.

Compatible with WordPress 2.6+, 2.7+.

=>> Read the accompanying readme.txt file for more information.  Also, visit the plugin's homepage
=>> for more information and the latest updates

Installation:

1. Download the file http://coffee2code.com/wp-plugins/admin-post-navigation.zip and unzip it into your 
/wp-content/plugins/ directory.
2. Activate the plugin through the 'Plugins' admin menu in WordPress
*/

/*
Copyright (c) 2008-2009 by Scott Reilly (aka coffee2code)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation 
files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, 
modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the 
Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR
IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

if ( !class_exists('AdminPostNavigation') ) :

class AdminPostNavigation {
	var $prev_text = '&laquo; Previous';
	var $next_text = 'Next &raquo;';

	function AdminPostNavigation() {
		global $pagenow;
		if ( is_admin() && 'post.php' == $pagenow ) {
			$this->prev_text = __($this->prev_text);
			$this->next_text = __($this->next_text);

			add_action('admin_init', array(&$this,'admin_init'));
			add_action('admin_head', array(&$this, 'add_css'));
			add_action('admin_footer', array(&$this, 'add_js'));
		}
	}

	function admin_init() {
		add_meta_box('adminpostnav', 'Post Navigation', array(&$this, 'add_meta_box'), 'post', 'side', 'core');
	}

	function add_meta_box( $object, $box ) {
		global $post_ID;
		$display = '';
		$context = $object->post_type;
		$prev = $this->previous_post();
		if ( $prev ) {
			$post_title = attribute_escape(strip_tags($prev->post_title));
			$display .= '<a href="' . get_edit_post_link($prev->ID) . 
				"\" id='admin-post-nav-prev' title='Previous $context: $post_title' class='admin-post-nav-prev'>{$this->prev_text}</a>";
		}
		$next = $this->next_post();
		if ( $next ) {
			if ( !empty($display) )
				$display .= ' | ';
			$post_title = attribute_escape($next->post_title);
			$display .= '<a href="' . get_edit_post_link($next->ID) .
				"\" id='admin-post-nav-next' title='Next $context: $post_title' class='admin-post-nav-next'>{$this->next_text}</a>";
		}
		$display = '<span id="admin-post-nav">' . $display . '</span>';
		echo apply_filters('admin_post_nav', $display);
	}

	function add_css() {
		echo <<<CSS
		<style type="text/css">
		#admin-post-nav {
			margin-left:20px;
		}
		h2 #admin-post-nav {
			font-size:0.6em;
		}
		</style>

CSS;
	}

	// For those with JS enabled, the navigation links are moved next to the "Edit Post" header and the plugin's meta_box is hidden.
	// The fallback for non-JS people is that the plugin's meta_box is shown and the navigation links can be found there.
	function add_js() {
		echo <<<JS
		<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('#admin-post-nav').appendTo($('h2'));
			$('#adminpostnav').hide();
		});

		</script>
JS;
	}

	function query($type = '<') {
		global $post_ID, $wpdb;
		$sql = "SELECT * FROM $wpdb->posts WHERE post_type = 'post' ";
		if ($post_ID) {
			$sql .= "AND ID $type $post_ID ";
		}
		$sort = $type == '<' ? 'DESC' : 'ASC';
		$sql .= "ORDER BY post_date $sort LIMIT 1";
		return $wpdb->get_row($sql);
	}

	function next_post() {
		return $this->query('>');
	}

	function previous_post() {
		return $this->query('<');
	}

} // end AdminPostNavigation

endif; // end if !class_exists()

if ( class_exists('AdminPostNavigation') )
	new AdminPostNavigation();

?>