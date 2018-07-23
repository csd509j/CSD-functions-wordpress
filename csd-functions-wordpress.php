<?php
/*
Plugin Name: CSD Functions - Wordpress Admin
Version: 1.2
Description: Wordpress Admin Customizations for CSD School and District Theme
Author: Josh Armentano
Author URI: https://abidewebdesign.com
Plugin URI: https://abidewebdesign.com
*/
require WP_CONTENT_DIR . '/plugins/plugin-update-checker-master/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/csd509j/CSD-functions-wordpress',
	__FILE__,
	'CSD-functions-wordpress'
);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Admin bar customizations
 *
 */
function csd_admin_bar_render() {
	
    global $wp_admin_bar;
    
    $wp_admin_bar->remove_menu('comments');
    $wp_admin_bar->remove_menu('wpseo-menu');
    $wp_admin_bar->remove_menu('ubermenu');
    $wp_admin_bar->remove_menu('customize');
    $wp_admin_bar->remove_node('wp-logo');
    $wp_admin_bar->remove_menu('new-post');
    $wp_admin_bar->remove_menu('search');
    $wp_admin_bar->remove_menu('themes');
    $wp_admin_bar->remove_menu('widgets');
    $wp_admin_bar->remove_node('updates');
    $wp_admin_bar->remove_menu('autoptimize');

}
add_action( 'wp_before_admin_bar_render', 'csd_admin_bar_render' );

/*
 * Remove unused dashboard widgets
 *
 */
function remove_dashboard_widgets() {
	
	global $wp_meta_boxes;

	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']); 
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);

}
add_action('wp_dashboard_setup', 'remove_dashboard_widgets' );

/*
 * Remove unused admin menu items
 *
 */
function csd_menu_page_removing() {
	
	remove_menu_page( 'edit-comments.php' );
	remove_menu_page( 'edit.php' );
//     remove_menu_page( 'tools.php' );
    
}
add_action( 'admin_menu', 'csd_menu_page_removing' );

/*
 * Hide admin notifications for non-admins
 *
 */
function hide_update_msg_non_admins(){
	
	if (!current_user_can( 'manage_options' )) { // non-admin users
    	
    	echo '<style>#setting-error-tgmpa>.updated settings-error notice is-dismissible, .update-nag, .updated { display: none; }</style>';
        
	}
}
add_action( 'admin_head', 'hide_update_msg_non_admins');


/*
 * Add custom favicon to admin pages
 *
 */
function add_login_favicon() {
	
  	$favicon_url = get_stylesheet_directory_uri() . '/assets/images/admin-favicon.ico';
	
	echo '<link rel="shortcut icon" href="' . $favicon_url . '" />';
	
}
add_action('login_head', 'add_login_favicon');
add_action('admin_head', 'add_login_favicon');

/*
 * Remove absolute styling on mobile when logged in
 *
 */
function admin_bar_style_override() {
	
	if ( is_user_logged_in() ) {
		?>
		<style>
			#wpadminbar {
				position: fixed;
			}
			#directory-categorydiv, .ac-message, #ac-pro-version, #direct-feedback {
				display: none;
			}
			.installer-plugin-update-tr {
				display: none;
			}
			.plugins .dashicons {
				display: none;
			}
		</style>
	<?php
		
	}
}
add_action('wp_head', 'admin_bar_style_override');
add_action('admin_head', 'admin_bar_style_override');