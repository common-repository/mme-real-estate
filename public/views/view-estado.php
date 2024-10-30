<?php
/**
 * State view
 *
 * @package  MME Real Estate
 * @author   Multimedia Efectiva <info@multimediaefectiva.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$plugin_dir_path = plugin_dir_path( __FILE__ );

$plugin_dir_path = str_replace( 'vistas/', '', $plugin_dir_path );

require $plugin_dir_path . '/commons.php';

get_header();

$post_property = get_post();
$id_prop       = $post_property->ID;

// State and county.
$tax_estados = get_the_terms( $id_prop, 'estado_propiedad' );
foreach ( $tax_estados as $edos ) {
	if ( 0 === $edos->parent ) {
		$estado   = $edos->name;
		$slug_edo = $edos->slug;
	} elseif ( $edos->parent > 0 ) {
		$municipio = $edos->name;
		$slug_mun  = $edos->slug;
	}
}

global $wp;
$url = str_replace( 'https://', '', home_url( $wp->request ) );
$url = str_replace( 'http://', '', $url );
$x   = explode( '/', $url );

if ( $x[2] === $slug_mun ) {
	$tax_query[] = array(
		'taxonomy' => 'estado_propiedad',
		'field'    => 'slug',
		'terms'    => $slug_edo,
	);
	$tax_query[] = array(
		'taxonomy' => 'estado_propiedad',
		'field'    => 'slug',
		'terms'    => $slug_mun,
	);
	$en_mun      = "$municipio, ";
} else {
	$tax_query[] = array(
		'taxonomy' => 'estado_propiedad',
		'field'    => 'slug',
		'terms'    => $slug_edo,
	);
}

$propiedade_en = " en $en_mun $estado";

require plugin_dir_path( __FILE__ ) . 'inc-propiedades-list.php';

get_footer();
