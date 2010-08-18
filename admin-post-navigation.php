<?php
/**
 * @package Admin_Post_Navigation
 * @author Scott Reilly
 * @version 1.5
 */
/*
Plugin Name: Admin Post Navigation
Version: 1.5
Plugin URI: http://coffee2code.com/wp-plugins/admin-post-navigation/
Author: Scott Reilly
Author URI: http://coffee2code.com
Description: Adds links to the next and previous posts when editing a post in the WordPress admin.

Compatible with WordPress 2.8+, 2.9+, 3.0+.

=>> Read the accompanying readme.txt file for instructions and documentation.
=>> Also, visit the plugin's homepage for additional information and updates.
=>> Or visit: http://wordpress.org/extend/plugins/admin-post-navigation/

*/

/*
Copyright (c) 2008-2010 by Scott Reilly (aka coffee2code)

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

if ( is_admin() && !class_exists( 'AdminPostNavigation' ) ) :

class AdminPostNavigation {
	var $prev_text = '';
	var $next_text = '';

	var $orderby = 'ID'; // Filterable later
	var $post_statuses = array( 'draft', 'future', 'pending', 'private', 'publish' ); // Filterable later

	/**
	 * Class constructor: initializes class variables and adds actions and filters.
	 */
	function AdminPostNavigation() {
		global $pagenow;
		if ( 'post.php' == $pagenow ) {
			$this->prev_text = __( '&laquo; Previous' );
			$this->next_text = __( 'Next &raquo;' );

			add_action( 'admin_init', array( &$this,'admin_init' ) );
			add_action( 'admin_head', array( &$this, 'add_css' ) );
			add_action( 'admin_footer', array( &$this, 'add_js' ) );
		}
	}

	/**
	 * Initialize variables and meta_box
	 */
	function admin_init() {
		$this->orderby = esc_sql( apply_filters( 'c2c_admin_post_navigation_orderby', $this->orderby ) ); // pre-1.5 this used to order by 'post_date'
		$this->post_statuses = apply_filters( 'c2c_admin_post_navigation_post_statuses', $this->post_statuses );
		$this->post_statuses = "'" . implode( "', '", array_map( 'esc_sql', $this->post_statuses ) ) . "'";
		add_meta_box( 'adminpostnav', 'Post Navigation', array( &$this, 'add_meta_box' ), 'post', 'side', 'core' );
	}

	/**
	 * Adds the content for the post navigation meta_box.
	 *
	 * @param object $object
	 * @param array $box
	 * @return void (Text is echoed.)
	 */
	function add_meta_box( $object, $box ) {
		global $post_ID;
		$display = '';
		$context = $object->post_type;
		$prev = $this->previous_post();
		if ( $prev ) {
			$post_title = esc_attr( strip_tags( get_the_title( $prev->ID ) ) );
			$display .= '<a href="' . get_edit_post_link( $prev->ID ) .
				"\" id='admin-post-nav-prev' title='Previous $context: $post_title' class='admin-post-nav-prev'>{$this->prev_text}</a>";
		}
		$next = $this->next_post();
		if ( $next ) {
			if ( !empty( $display ) )
				$display .= ' | ';
			$post_title = esc_attr( strip_tags( get_the_title( $next->ID ) ) );
			$display .= '<a href="' . get_edit_post_link( $next->ID ) .
				"\" id='admin-post-nav-next' title='Next $context: $post_title' class='admin-post-nav-next'>{$this->next_text}</a>";
		}
		$display = '<span id="admin-post-nav">' . $display . '</span>';
		$display = apply_filters( 'admin_post_nav', $display ); /* Deprecated as of v1.5 */
		echo apply_filters( 'c2c_admin_post_navigation_display', $display );
	}

	/**
	 * Outputs CSS within style tags
	 */
	function add_css() {
		echo <<<CSS
		<style type="text/css">
		#admin-post-nav {margin-left:20px;}
		h2 #admin-post-nav {font-size:0.6em;}
		</style>

CSS;
	}

	/**
	 * Outputs the JavaScript used by the plugin.
	 *
	 * For those with JS enabled, the navigation links are moved next to the
	 * "Edit Post" header and the plugin's meta_box is hidden.  The fallback
	 * for non-JS people is that the plugin's meta_box is shown and the
	 * navigation links can be found there.
	 */
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

	/**
	 * Returns the previous or next post relative to the current post.
	 *
	 * Currently, a previous/next post is determined by the next lower/higher
	 * valid post based on relative sequential post ID and which the user can
	 * edit.  Other post criteria such as post type (draft, pending, etc),
	 * publish date, post author, category, etc, are not taken into
	 * consideration when determining the previous or next post.
	 *
	 * @param string $type (optional) Either '<' or '>', indicating previous or next post, respectively. Default is '<'.
	 * @param int $offset (optional) Offset. Default is 0.
	 * @param int $limit (optional) Limit. Default is 15.
	 * @return string
	 */
	function query( $type = '<', $offset = 0, $limit = 15 ) {
		global $post_ID, $wpdb;

		if ( $type != '<' )
			$type = '>';
		$offset = (int) $offset;
		$limit = (int) $limit;

		$sql = "SELECT ID, post_title FROM $wpdb->posts WHERE post_type = 'post' AND post_status IN ({$this->post_statuses}) ";
		if ( $post_ID )
			$sql .= "AND ID $type $post_ID ";
		$sort = $type == '<' ? 'DESC' : 'ASC';
		$sql .= "ORDER BY {$this->orderby} $sort LIMIT $offset, $limit";

		// Find the first one the user can actually edit
		$posts = $wpdb->get_results( $sql );
		$result = false;
		if ( $posts ) {
			foreach ( $posts as $post ) {
				if ( current_user_can( 'edit_post', $post->ID ) ) {
					$result = $post;
					break;
				}
			}
			if ( !$result ) { // The fetch did not yield a post editable by user, so query again.
				$offset += $limit;
				// Double the limit each time (if haven't found a post yet, chances are we may not, so try to get through posts quicker)
				$limit += $limit;
				return $this->query( $type, $offset, $limit );
			}
		}
		return $result;
	}

	/**
	 * Returns the next post relative to the current post.
	 *
	 * A convenience function that calls query().
	 *
	 * @return object The next post object.
	 */
	function next_post() {
		return $this->query( '>' );
	}

	/**
	 * Returns the previous post relative to the current post.
	 *
	 * A convenience function that calls query().
	 *
	 * @return object The previous post object.
	 */
	function previous_post() {
		return $this->query( '<' );
	}

} // end AdminPostNavigation

$GLOBALS['c2c_admin_post_navigation'] = new AdminPostNavigation();

endif; // end if !class_exists()

?>