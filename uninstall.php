<?php
/**
 * MME Real Estate plugin uninstall file
 *
 * @package  MME Real Estate
 * @author   Multimedia Efectiva <info@multimediaefectiva.com>
 */

// Exit if the constant uninstall does not exist.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Delete options.
$options = array(
	'pagina-propiedades',
	'mostrar_titulo',
	'mostrar_form',
	'mostrar_poweredby',
	'margintoppage',
	'max_width',
	'properties_per_page',
	'email_informes',
	'simbolo_moneda',
);

foreach ( $options as $option ) {
	if ( get_option( $option ) ) {
		delete_option( $option );
	}
}

global $post;

// Delete custom post type entries.
$miplugin_cpt_args = array(
	'post_type'      => 'mmeproperties',
	'posts_per_page' => -1,
);

$miplugin_cpt_posts = get_posts( $miplugin_cpt_args );
foreach ( $miplugin_cpt_posts as $delete_post ) {
	$arr_id_post[] = $delete_post->ID;
	wp_delete_post( $delete_post->ID, false );
}

$arr_pm = array(
	'propiedad_operacion',
	'propiedad_tipopropiedad',
	'propiedad_colonia',
	'propiedad_calle',
	'propiedad_precio',
	'propiedad_superficie_total',
	'propiedad_superficie_construida',
	'propiedad_niveles',
	'propiedad_recamaras',
	'propiedad_espacios_auto',
	'propiedad_banos',
	'propiedad_medios_banos',
	'propiedad_espacios',
	'propiedad_servicios',
	'propiedad_mapa',
	'propiedad_url_youtube',
);

if ( isset( $arr_id_post ) ) {
	foreach ( $arr_id_post as $id_p ) {
		foreach ( $arr_pm  as $pm ) {
			delete_post_meta( $id_p, $pm );
		}
	}
}
