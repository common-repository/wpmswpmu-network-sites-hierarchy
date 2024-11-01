<?php
/*

Plugin Name: Network Sites Hierarchy	

Plugin URI: http://richardconsulting.ro/blog/2011/08/wpms-network-sites-hierarchy/

Description: Register new site option (site_parent) and use this to create a hierarchy of sites in the WPMS network. 
Provides functions to retrieve parent site, children sites, all sites under current site

Version: 0.1.2

Author: Richard Vencu

Author URI: http://richardconsulting.ro

License: GPL2

Text Domain: nsh

*/
?>
<?php
/* define global variable to hold all blogs ids */
$nsh_all_blogs = array();

register_activation_hook ( __FILE__ , 'nsh_install' );

register_deactivation_hook ( __FILE__ , 'nsh_deactivation' );

add_action ('init','nsh_init');

function nsh_install() {

	/* At first activation push default values to database */
	global $nsh_all_blogs;
	
	nsh_retrieve_blogs();
	
	foreach ($nsh_all_blogs as $blog) {
		
		nsh_default_parent( $blog );
	}	
}

function nsh_deactivation() {

	/* Nothing to do here yet */
}

function nsh_init() {

	/* Load translation */
	load_plugin_textdomain ('nsh', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	nsh_retrieve_blogs();
	
	/* Insert network dashboard menu section, and submenu page */
	add_action( 'network_admin_menu', 'nsh_add_menu_links', 10, 0 );

	add_action( 'network_admin_edit_nsh_updateparent', 'update_blog_parent', 10, 0 );
	
	/* add action at site creation to insert default parent as 1 */
	add_action ('wpmu_new_blog','nsh_default_parent');
}

function nsh_retrieve_blogs() {
	/* Retrieve all blog ids */

	global $wpdb, $nsh_all_blogs;

	$sql = "SELECT blog_id FROM $wpdb->blogs";

	$nsh_all_blogs = $wpdb->get_col($wpdb->prepare($sql));
}

function nsh_default_parent( $blog_id ) {

	if ( !get_blog_option($blog_id, 'nsh_parent') && $blog_id != '1' )
		update_blog_option ( $blog_id, 'nsh_parent', '1' );
}

function nsh_add_menu_links() {

	$capability= 'upload_files';

	add_menu_page(__('WPMS Network Sites Hierarchy', 'nsh'), __('Sites Hierarchy', 'nsh'), $capability, 'nsh_menu','nsh_plugin_usage' , plugins_url('wpmswpmu-network-sites-hierarchy/images/menu-icon.png'));

	add_submenu_page('nsh_menu', __('Manage Network Sites Hierarchy', 'nsh'), __('Manage Hierarchy', 'nsh'), $capability, 'wpmswpmu-network-sites-hierarchy/settings.php');

}

function update_blog_parent() {

	if ( $_POST["parent"] != '0' )
		update_blog_option( absint($_POST["site_id"]), 'nsh_parent', $_POST["parent"] );
	else
		delete_blog_option( absint($_POST["site_id"]), 'nsh_parent' );
	
	wp_redirect(add_query_arg(array('page' => 'wpmswpmu-network-sites-hierarchy/settings.php', 'updated' => 'true'), network_admin_url('admin.php')));
	exit();
}

function nsh_plugin_usage() {

      if (!current_user_can('upload_files'))  {

			wp_die( __('You do not have sufficient permissions to access this page.','nsh') );

      }

		echo '<div class="wrap" id="nsh-options"><h2>' . __('Network Sites Hierarchy Plugin Usage','nsh') . '</h2>';
		
		echo '<p>' . __('See the next page to change the network sites hierarchy. <br/>To use hierarchy functions within your code use this snippets:<br/>','nsh'); 
		
		echo '<ol><li>' . __('Detect site\'s parent will return a string with the site parent\'s id:','nsh') . '<br/>
		<pre>if ( function_exists( nsh_get_parent ) )
	$parent_id = nsh_get_parent( $blog_id );
		</pre></li>';
		
		echo '<li>' . __('Detect site\'s children will return an array with site children\'s ids:','nsh') . '<br/>
		<pre>if ( function_exists( nsh_get_children ) )
	$children = nsh_get_children( $blog_id );
		</pre></li>';
		
		echo '<li>' . __('Detect all site\'s descendants will return an array with site descendants\'s ids:','nsh') . '<br/>
		<pre>if ( function_exists( nsh_get_descendants ) )
	$descendants = nsh_get_descendants( $blog_id );
		</pre></li>';
		
		echo '<li>' . __('Detect all site\'s ancestors will return an array with site ancestors\'s ids:','nsh') . '<br/>
		<pre>if ( function_exists( nsh_get_ancestors ) )
	$ancestors = nsh_get_ancestors( $blog_id );
		</pre></li></ol></p>';
		
		echo '<p>' . __('With hierarchical sites within the network some interesting things can be done such as displaying posts into sites only from descendants. This plugin was written specifically to allow this functionality but it can be used for any similar purpose.','nsh') . '</p>';
		
		echo '</div>';
?>

<?php }

function nsh_get_parent( $blog_id ) {

	return get_blog_option( $blog_id, 'nsh_parent'); 

}

function nsh_get_children( $blog_id ) {

	global $nsh_all_blogs;

	$children = array();
	
	foreach ( $nsh_all_blogs as $blog ) {
		if ( get_blog_option( $blog, 'nsh_parent') == $blog_id )
			$children[] = $blog;
	}
	
	return $children;

}

function nsh_get_descendants( $blog_id ) {

	global $nsh_all_blogs;
	
	$descendants = array();
	
	foreach ( $nsh_all_blogs as $blog ) {
		if ( get_blog_option( $blog, 'nsh_parent') == $blog_id && $blog_id != $blog) {
			$descendants[] = $blog;
			foreach ( nsh_get_descendants( $blog ) as $child )
				$descendants[] = $child;
			}

	}
	
	return $descendants;

}

function nsh_get_ancestors( $blog_id ) {

	global $nsh_all_blogs;

	$ancestors = array();
	
	foreach ( $nsh_all_blogs as $blog ) {
		if ( get_blog_option( $blog_id, 'nsh_parent') == $blog ) {
			$ancestors[] = $blog;
			foreach ( nsh_get_ancestors( $blog ) as $ancestor )
				$ancestors[] = $ancestor;
		}
	}
	
	return $ancestors;
}
?>