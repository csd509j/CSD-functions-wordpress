<?php
/*
Plugin Name: CSD Functions - Wordpress Admin
Version: 2.1
Description: Wordpress Admin Customizations and Custom Post Types for CSD School and District Theme
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
	$wp_admin_bar->remove_menu('customize');
    $wp_admin_bar->remove_node('wp-logo');
    $wp_admin_bar->remove_menu('new-post');
    $wp_admin_bar->remove_menu('search');
    $wp_admin_bar->remove_menu('themes');
    $wp_admin_bar->remove_menu('widgets');
    $wp_admin_bar->remove_node('updates');
    $wp_admin_bar->remove_menu('searchwp');
    $wp_admin_bar->remove_menu('delete-cache');
	$wp_admin_bar->remove_menu('litespeed-menu');
}
add_action( 'wp_before_admin_bar_render', 'csd_admin_bar_render' );

add_filter( 'searchwp_admin_bar', '__return_false' );
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
 * Disable plugin/theme auto-update email notifications
 *
 */
add_filter( 'auto_plugin_update_send_email', '__return_false' );
add_filter( 'auto_theme_update_send_email', '__return_false' );

/*
 * Remove unused user roles
 *
 */
remove_role( 'subscriber' );
remove_role( 'contributor' );
remove_role( 'author' );

/*
 * Remove unused admin menu items
 *
 */
function csd_menu_page_removing() {
	
	remove_menu_page( 'edit-comments.php' );
	remove_menu_page( 'edit.php' );
    
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
	
  	$favicon_url = get_stylesheet_directory_uri() . '/favicon.png';
	
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
			.user-syntax-highlighting-wrap,#your-profile h2,form#your-profile > h3, form#your-profile .user-profile-picture, form#your-profile .user-description-wrap, form#your-profile .user-display-name-wrap, form#your-profile .user-nickname-wrap, form#your-profile .show-admin-bar, .user-comment-shortcuts-wrap, form#your-profile .yoast-settings, form#your-profile .user-rich-editing-wrap, form#your-profile .user-admin-color-wrap, form#your-profile .user-url-wrap, form#your-profile .user-facebook-wrap, form#your-profile .user-instagram-wrap, form#your-profile .user-linkedin-wrap, form#your-profile .user-myspace-wrap, form#your-profile .user-pinterest-wrap, form#your-profile .user-soundcloud-wrap, form#your-profile .user-tumblr-wrap, form#your-profile .user-twitter-wrap, form#your-profile .user-youtube-wrap, form#your-profile .user-wikipedia-wrap, #directory-categorydiv, .ac-message, #ac-pro-version, #direct-feedback, .installer-plugin-update-tr, .plugins .dashicons, .shortpixel-notice, #emr-news, .wrap.emr_upload_form .option-flex-wrapper, .emr_upload_form #message {
				display: none;
			}

		</style>
	<?php
		
	}
}
add_action('wp_head', 'admin_bar_style_override');
add_action('admin_head', 'admin_bar_style_override');

function cptui_register_my_cpts() {

	/**
	 * Post Type: News.
	 */

	$labels = array(
		"name" => __( "News", "custom-post-type-ui" ),
		"singular_name" => __( "News", "custom-post-type-ui" ),
	);

	$args = array(
		"label" => __( "News", "custom-post-type-ui" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"delete_with_user" => false,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"has_archive" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => array( "slug" => "news", "with_front" => true ),
		"query_var" => true,
		"menu_icon" => "dashicons-megaphone",
		"supports" => array( "title" ),
	);

	register_post_type( "news", $args );
}

add_action( 'init', 'cptui_register_my_cpts' );

function cptui_register_my_cpts_news() {

	/**
	 * Post Type: News.
	 */

	$labels = array(
		"name" => __( "News", "custom-post-type-ui" ),
		"singular_name" => __( "News", "custom-post-type-ui" ),
	);

	$args = array(
		"label" => __( "News", "custom-post-type-ui" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"delete_with_user" => false,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"has_archive" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => array( "slug" => "news", "with_front" => true ),
		"query_var" => true,
		"menu_icon" => "dashicons-megaphone",
		"supports" => array( "title" ),
	);

	register_post_type( "news", $args );
}

add_action( 'init', 'cptui_register_my_cpts_news' );

function cptui_register_my_taxes() {

	/**
	 * Taxonomy: News Categories.
	 */

	$labels = array(
		"name" => __( "News Categories", "custom-post-type-ui" ),
		"singular_name" => __( "News Category", "custom-post-type-ui" ),
	);

	$args = array(
		"label" => __( "News Categories", "custom-post-type-ui" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => false,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => array( 'slug' => 'news-category', 'with_front' => true, ),
		"show_admin_column" => false,
		"show_in_rest" => true,
		"rest_base" => "news-category",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"show_in_quick_edit" => false,
		);
	register_taxonomy( "news-category", array( "news" ), $args );
}
add_action( 'init', 'cptui_register_my_taxes' );

function cptui_register_my_taxes_news_category() {

	/**
	 * Taxonomy: News Categories.
	 */

	$labels = array(
		"name" => __( "News Categories", "custom-post-type-ui" ),
		"singular_name" => __( "News Category", "custom-post-type-ui" ),
	);

	$args = array(
		"label" => __( "News Categories", "custom-post-type-ui" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => false,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => array( 'slug' => 'news-category', 'with_front' => true, ),
		"show_admin_column" => false,
		"show_in_rest" => true,
		"rest_base" => "news-category",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"show_in_quick_edit" => false,
		);
	register_taxonomy( "news-category", array( "news" ), $args );
}
add_action( 'init', 'cptui_register_my_taxes_news_category' );

function cptui_register_my_cpts_emergency_alert() {

	/**
	 * Post Type: Alerts.
	 */

	$labels = array(
		"name" => __( "Alerts", "custom-post-type-ui" ),
		"singular_name" => __( "Alert", "custom-post-type-ui" ),
	);

	$args = array(
		"label" => __( "Alerts", "custom-post-type-ui" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"delete_with_user" => false,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"exclude_from_search" => true,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => array( "slug" => "emergency-alert", "with_front" => true ),
		"query_var" => true,
		"menu_icon" => "dashicons-shield",
		"supports" => array( "title" ),
	);

	register_post_type( "emergency-alert", $args );
}

add_action( 'init', 'cptui_register_my_cpts_emergency_alert' );

function cptui_register_my_cpts_directory() {

	/**
	 * Post Type: Directory.
	 */

	$labels = array(
		"name" => __( "Directory", "custom-post-type-ui" ),
		"singular_name" => __( "Directory", "custom-post-type-ui" ),
	);

	$args = array(
		"label" => __( "Directory", "custom-post-type-ui" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"delete_with_user" => false,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"exclude_from_search" => true,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => array( "slug" => "directory", "with_front" => true ),
		"query_var" => true,
		"menu_icon" => "dashicons-businessman",
		"supports" => array( "title" ),
	);

	register_post_type( "directory", $args );
}

add_action( 'init', 'cptui_register_my_cpts_directory' );

function cptui_register_my_taxes_directory_category() {

	/**
	 * Taxonomy: Categories.
	 */

	$labels = array(
		"name" => __( "Categories", "custom-post-type-ui" ),
		"singular_name" => __( "Category", "custom-post-type-ui" ),
	);

	$args = array(
		"label" => __( "Categories", "custom-post-type-ui" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => false,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => array( 'slug' => 'directory-category', 'with_front' => true, ),
		"show_admin_column" => false,
		"show_in_rest" => true,
		"rest_base" => "directory-category",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"show_in_quick_edit" => false,
		);
	register_taxonomy( "directory-category", array( "directory" ), $args );
}
add_action( 'init', 'cptui_register_my_taxes_directory_category' );

