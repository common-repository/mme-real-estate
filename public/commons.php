<?php
/**
 * Helpers and common functions
 *
 * @package  MME Real Estate
 * @author   Multimedia Efectiva <info@multimediaefectiva.com>
 */


define( 'MME_URL_PLUG', plugin_dir_url( __FILE__ ) );

/**
 * Gets the GET variables of the property list filter and builds the arrays to apply the filter on the property list.
 *
 * @param string $get      Var name.
 * @param string $tipo     Filter data type (taxonomy or metadata).
 * @param string $res      Var by reference to accumulate the array of filters.
 * @param string $meta_key Metadata name.
 */
function mmerealestate_filtros_get( $get, $tipo, &$res, $meta_key = '' ) {
	$get = mmerealestate_rs_limpiar( $get, 'GET' );
	if ( ! empty( $get ) ) {
		switch ( $tipo ) {
			case 'tax_query':
				$res[] = array(
					'taxonomy' => 'mmelocalities',
					'field'    => 'slug',
					'terms'    => $get,
				);
				break;
			case 'meta_query':
				$res[] = array(
					'key'   => $meta_key,
					'value' => $get,
				);
				break;
		}
	}
	return $res;
}

/**
 * Gets the price GET variables from the property list filter and builds an array to apply the filter on the property list.
 *
 * @param string $get1     Min price.
 * @param string $get2     Max price.
 * @param string $res      Var by reference to accumulate the array of filters.
 * @param string $meta_key Metadata name.
 */
function mmerealestate_filtros_get_compare( $get1, $get2, &$res, $meta_key ) {
	$get1 = mmerealestate_rs_limpiar( $get1, 'GET' );
	$get2 = mmerealestate_rs_limpiar( $get2, 'GET' );

	if ( ! empty( $get1 ) && ! empty( $get2 ) ) {
		$res[] = array(
			'key'     => $meta_key,
			'value'   => array(
				$get1,
				$get2,
			),
			'type'    => 'numeric',
			'compare' => 'BETWEEN',
		);
	} elseif ( ! empty( $get1 ) && empty( $get2 ) ) {
		$res[] = array(
			'key'     => $meta_key,
			'value'   => $get1,
			'type'    => 'numeric',
			'compare' => '>=',
		);
	} elseif ( empty( $get1 ) && ! empty( $get2 ) ) {
		$res[] = array(
			'key'     => $meta_key,
			'value'   => $get2,
			'type'    => 'numeric',
			'compare' => '<=',
		);
	}
	return $res;
}



/**
 * List of neighborhoods of properties of a certain municipality.
 *
 * @param int $id_mun Municipality Id.
 */
function mmerealestate_colonias_list( $id_mun ) {
	$colonias = array();
	$args     = array(
		'post_status' => 'publish',
		'tax_query'   => array(
			array(
				'taxonomy' => 'mmelocalities',
				'field'    => 'slug',
				'terms'    => $id_mun,
			),
		),
	);

	$category_posts = new WP_Query( $args );
	$post_curr      = 0;
	$arr_col[]      = '<option value="" selected="selected">' . _x( 'All', 'mme-real-estate' ) . '</option>';

	if ( $category_posts->have_posts() ) :
		while ( $category_posts->have_posts() ) :

			$category_posts->the_post();
			$id_prop           = $category_posts->posts[ $post_curr ]->ID;
			$propiedad_colonia = get_post_meta( $id_prop, 'propiedad_colonia', true );
			if ( ! empty( $propiedad_colonia ) ) {
				$colonias[] = $propiedad_colonia;
			}
			$post_curr++;
		endwhile;
	endif;

	return $colonias;
}

/**
 * Lists of localities in which there are properties.
 */
function mmerealestate_estados_list() {
	// taxonomia estados.
	$estados = get_terms(
		array(
			'taxonomy' => 'mmelocalities',
			'parent'   => 0,
		)
	);
	return $estados;
}


