<?php
/**
 * MME Realestate property list
 *
 * @package  MME Real Estate
 * @author   Multimedia Efectiva <info@multimediaefectiva.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$plugin_dir_path = plugin_dir_path( __FILE__ );

$plugin_dir_path = str_replace( 'views/', '', $plugin_dir_path );

require $plugin_dir_path . '/commons.php';

get_header();

require plugin_dir_path( __FILE__ ) . 'inc-propiedades-list.php';

get_footer();
