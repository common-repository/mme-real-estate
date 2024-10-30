<?php
/**
 * MME Real Estate
 *
 * Plugin Name: MME Real Estate
 * Plugin URI:  https://multimediaefectiva.com/mme-real-estate-plugin
 * Description: Simple real estate listing.
 * Author:      Multimedia Efectiva
 * Author URI:  https://multimediaefectiva.com/
 * License:     GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: mme-real-estate
 * Domain Path: /languages/
 * Version:     1.2.2
 *
 * @package WordPress
 **/

define( 'MME_RUTARE',      plugin_dir_path( __FILE__ ) );
define( 'MME_URL_PLUG_AD', plugin_dir_url( __FILE__ ) );

add_action( 'init', 'mmerealestate_load_textdomain' );
add_action( 'init', 'mmerealestate_propiedades', 20 );
add_action( 'init', 'mmerealestate_estado_taxonomy', 10 );
add_action( 'add_meta_boxes', 'mmerealestate_metaboxes' );
add_action( 'admin_menu', 'mmerealestate_admin_menu_page' );
add_action( 'admin_enqueue_scripts', 'mmerealestate_load_css_admin' );
add_action( 'save_post', 'mmerealestate_guardar', 3, 2 );
add_action( 'wp_enqueue_scripts', 'mmerealestate_my_load_scripts' );
add_action( 'rest_api_init', 'mmerealestate_events_endpoint' );
add_action( 'rest_api_init', 'mmerealestate_events_endpoint_post' );
add_action( 'init', 'mme_square_thumbnail' );
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'mme_realestate_setup_link' );
add_filter( 'template_include', 'mmerealestate_portfolio_page_template', 99 );
add_action( 'restrict_manage_posts', 'mmerealestate_menu_localidades_admin' );
add_shortcode ('mme_realestate_list','mme_realestate_list');
add_shortcode ('mme_realestate_filter','mme_realestate_filter');



/**
 * Set a new thumbnail size.
 **/
function mme_square_thumbnail() {
	add_image_size( 'wp_small', 350, 350, true );
}

/**
 * Show a "Set up" link in the plugin action links.
 *
 * @param array $links The existing plugin row links.
 **/
function mme_realestate_setup_link( $links ) {
	$ruta            = admin_url( 'admin.php?page=mme-real-estate/admin/views/set-up.php' );
	$show_setup_text = __( 'Set up', 'mme-real-estate' );
	$enlace          = " <a href='$ruta'>$show_setup_text</a> ";
	return array_merge( $links, array( $enlace ) );
}

require 'functions.php'; 
