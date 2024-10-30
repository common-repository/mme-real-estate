<?php
/**
 * List and filter properties on the front of the site
 *
 * @package  MME Real Estate
 * @author   Multimedia Efectiva <info@multimediaefectiva.com>
 */

$type_permalink  = mmerealestate_get_permalink_structure();
$margin_top_page = 20;
$max_width       = 1200;
$simbolo_moneda  = '$';
$cpage           = mmerealestate_rs_limpiar( 'cpage', 'GET' );
$tags_permitidos = array(
	'img'    => array(
		'src'    => array(),
		'title'  => array(),
		'width'  => array(),
		'height' => array(),
		'class'  => array(),
		'alt'    => array(),
		'srcset' => array(),
		'sizes'  => array(),
	),
	'br'     => array(),
	'span'   => array(
		'aria-current' => array(),
		'class'        => array(),
	),
	'a'      => array(
		'class' => array(),
		'href'  => array(),
	),
	'ul'     => array(
		'class' => array(),
	),
	'li'     => array(
		'aria-current' => array(),
		'class'        => array(),
	),
	'option' => array(
		'idedo'    => array(),
		'value'    => array(),
		'selected' => array(),
	),
);

if ( ! empty( get_option( 'margintoppage' ) ) ) {
	$margin_top_page = str_replace( 'px', '', get_option( 'margintoppage' ) );
}
if ( ! empty( get_option( 'max_width' ) ) ) {
	$max_width = str_replace( 'px', '', get_option( 'max_width' ) );
}
if ( ! empty( get_option( 'simbolo_moneda' ) ) ) {
	$simbolo_moneda = get_option( 'simbolo_moneda' );
}

$label_re_estado     = get_option( 'label_re_estado' );
$label_re_ciudad     = get_option( 'label_re_ciudad' );
$label_re_colonia    = get_option( 'label_re_colonia' );
$label_re_toperacion = get_option( 'label_re_toperacion' );
$label_re_tpropiedad = get_option( 'label_re_tpropiedad' );

if( empty( $label_re_estado ) ){
	$label_re_estado = __( 'State', 'mme-real-estate' ); 
}
if( empty( $label_re_ciudad ) ){
	$label_re_ciudad = __( 'City or County', 'mme-real-estate' ); 
}
if( empty( $label_re_colonia ) ){
	$label_re_colonia = __( 'Neighborhood', 'mme-real-estate' ); 
}
if( empty( $label_re_toperacion ) ){
	$label_re_toperacion = __( 'Operation type', 'mme-real-estate' ); 
}
if( empty( $label_re_tpropiedad ) ){
	$label_re_tpropiedad = __( 'Property type', 'mme-real-estate' ); 
} 
?>
<script type="text/javascript">
	var type_permalink = "<?php echo esc_js( $type_permalink ); ?>"
</script>
 
<div id="mme-content-propiedades" style="margin-top:<?php echo esc_html( $margin_top_page ) . 'px;max-width:' . esc_html( $max_width ) . 'px'; ?>"> 
	<?php if ( get_option( 'mostrar_titulo' ) === 'on' ) { ?>
		<div class="grid-100">
			<h1 class="re-titulo"> 
				<?php
				echo esc_html( $post->post_title );
				if ( isset( $propiedade_en ) ) {
					echo esc_html( $propiedade_en );
				}
				?>
			</h1>
		</div>
	<?php } ?>

	<div class="grid-75" id="mme-content-list-propiedades">
	<?php
	if ( empty( $fl_dt ) ) {
		$fl_dt = array();
	}

	// Cacha variables GET.
	mmerealestate_filtros_get( 'estado', 'tax_query', $tax_query );
	mmerealestate_filtros_get( 'municipio', 'tax_query', $tax_query );
	mmerealestate_filtros_get( 'operacion', 'meta_query', $meta_query, 'propiedad_operacion' );
	mmerealestate_filtros_get( 'recamaras', 'meta_query', $meta_query, 'propiedad_recamaras' );
	mmerealestate_filtros_get( 'colonia', 'meta_query', $meta_query, 'propiedad_colonia' );
	mmerealestate_filtros_get( 'tipo-propiedad', 'meta_query', $meta_query, 'propiedad_tipopropiedad' );
	mmerealestate_filtros_get_compare( 'desde', 'hasta', $meta_query, 'propiedad_precio' );

	$id_edo       = mmerealestate_rs_limpiar( 'estado', 'GET' );
	$id_mun       = mmerealestate_rs_limpiar( 'municipio', 'GET' );
	$id_col       = mmerealestate_rs_limpiar( 'colonia', 'GET' );
	$id_ope       = mmerealestate_rs_limpiar( 'operacion', 'GET' );
	$id_tpr       = mmerealestate_rs_limpiar( 'tipo-propiedad', 'GET' );
	$estado_fl    = '';
	$municipio_fl = '';

	if ( ! empty( $id_edo ) && ! empty( $id_mun ) && ! empty( $id_col ) ) {
		$arrtp_operacion = mmerealestate_selects_adicionales( 'tipo_operacion', $id_edo, $id_mun, $id_col, 0, $id_ope );
		if ( ! empty( $id_ope ) ) {
			$arrtp_propiedad = mmerealestate_selects_adicionales( 'tipo_propiedad', $id_edo, $id_mun, $id_col, $id_ope, $id_tpr );
		}
	}

	if ( ! empty( $tax_query ) ) {
		if ( ! empty( $tax_query[0] ) ) {
			$estado_fl  = $tax_query[0]['terms'];
			$estado_f   = get_terms(
				array(
					'taxonomy' => 'mmelocalities',
					'slug'     => $estado_fl,
				)
			);
			$municipios = get_terms(
				array(
					'taxonomy' => 'mmelocalities',
					'parent'   => $estado_f[0]->term_id,
				)
			);
		}

		if ( ! empty( $tax_query[1] ) ) {
			$municipio_fl = $tax_query[1]['terms'];
		}

		if ( ! empty( $municipio_fl ) ) {
			$colonias = mmerealestate_colonias_list( $municipio_fl );
		}
	}

	if ( ! empty( $meta_query ) ) {
		foreach ( $meta_query as $f ) {
			$fl_dt[ $f['key'] ] = $f['value'];
		}
	}

	$fl_dt['desde'] = '';
	$fl_dt['hasta'] = '';
	$desde          = mmerealestate_rs_limpiar( 'desde', 'GET' );
	$hasta          = mmerealestate_rs_limpiar( 'hasta', 'GET' );

	if ( ! empty( $desde ) ) {
		$fl_dt['desde'] = $desde;
	}

	if ( ! empty( $hasta ) ) {
				$fl_dt['hasta'] = $hasta;
	}

	// Taxonomia estados.
	$estados = get_terms(
		array(
			'taxonomy' => 'mmelocalities',
			'parent'   => 0,
		)
	);  //echo "<pre>"; print_r( $estados ); echo "</pre>";

	$paged_pg            = isset( $cpage ) ? abs( (int) $cpage ) : 1;
	$properties_per_page = get_option( 'properties_per_page' );

	if ( empty( $properties_per_page ) ) {
		$properties_per_page = 6;
	}

	// filtro para tomar solo posts de propiedades.
	$args = array(
		'post_type'      => 'mmeproperties',
		'post_status'    => 'publish',
		'posts_per_page' => $properties_per_page,
		'paged'          => $paged_pg,
	);

	// filtro tax_query en caso de existir.
	if ( ! empty( $tax_query ) ) {
		$args['tax_query'] = $tax_query;
	}

	// filtro meta_query en caso de existir.
	if ( ! empty( $meta_query ) ) {
		$args['meta_query'] = $meta_query;
	}

	$category_posts = new WP_Query( $args );

	$post_curr = 0;

	// Divider ==============================================.
	if ( $category_posts->have_posts() ) :
		while ( $category_posts->have_posts() ) :

			$category_posts->the_post();
			$id_prop = $category_posts->posts[ $post_curr ]->ID;
			$tmb_img = get_the_post_thumbnail( $id_prop, 'wp_small' );
			$pos     = strpos( $tmb_img, '350x350' );

			if ( false === $pos ) {
				$tmb_img = get_the_post_thumbnail( $id_prop, 'thumbnail' );
			}

			if ( empty( $tmb_img ) ) {
				$tmb_img = '<img width="250px" src="' . MME_URL_PLUG . 'image-not-available.png">';
			}

			// Estado y municipio =================================================.
			$arr_edos = array();
			$tax_estados = get_the_terms( $id_prop, 'mmelocalities' );
			if ( ! empty( $tax_estados ) ) {
				foreach ( $tax_estados as $edos ) {
					if ( 0 === $edos->parent ) {  
						$estado = $edos->name;
						$arr_edos_['name'] = $edos->name;
						$arr_edos_['slug'] = $edos->slug;
						$arr_edos_['id']   = $edos->term_taxonomy_id;
						$arr_edos[] = $arr_edos_;
					} elseif ( $edos->parent > 0 ) {
						$municipio = $edos->name;
					}
				}
			}

			// Datos de la propiedad =================================================.
			$propiedad_precio    = get_post_meta( $id_prop, 'propiedad_precio', true );
			$propiedad_resumen   = get_post_meta( $id_prop, 'propiedad_resumen', true );
			$propiedad_operacion = get_post_meta( $id_prop, 'propiedad_operacion', true );

			$url_post = home_url() . '/propiedades/' . $category_posts->posts[ $post_curr ]->post_name;

			if ( 'plain' === $type_permalink ) {
				$url_post = home_url() . '/?mmeproperties=' . $category_posts->posts[ $post_curr ]->post_name;
			}

			?>
			<div class="grid-33">
				<div class="ficha-propiedad">
					<a href="<?php echo esc_url( $url_post ); ?>"><?php echo wp_kses( $tmb_img, $tags_permitidos ); ?></a>
					<p class="ver-propiedad">
						<button class="button-propiedad" href="<?php echo esc_url( $url_post ); ?>">
							<?php echo esc_html( $category_posts->posts[ $post_curr ]->post_title ); ?>
						</button>
					</p>
					<?php
					switch ( $propiedad_operacion ) {
						case 1:
							$operacion_label = __( 'Sale', 'mme-real-estate' );
							$class_op        = ' sale ';
							break;
						case 2:
							$operacion_label = __( 'Rent', 'mme-real-estate' );
							$class_op        = ' rent ';
							break;
					}
					?>
					<span class="<?php echo esc_attr( $class_op ); ?>"><?php echo esc_html( $operacion_label ); ?></span>
					<?php
					if ( ! empty( $propiedad_precio ) ) {
						$precio = '<br>' . mmerealestate_formato_moneda( $propiedad_precio, $simbolo_moneda );
						echo wp_kses( $precio, $tags_permitidos );
					}

					if ( isset( $estado ) ) {
						?>
						<p>
						<?php
						echo esc_html( $estado );
						if ( isset( $municipio ) ) {
							echo esc_html( '-' . $municipio );
						}
						?>
						</p>
						<?php
					}
					?>
				</div>
			</div>
			<?php
			$post_curr++;
		endwhile;
	endif;

		if ( 0 === $post_curr ) {
			?>
			<strong>
				<?php esc_html_e( 'No properties yet', 'mme-real-estate' ); ?>
			</strong>
			<?php
		}
		?>

		<!-- PAGINATION -->
		<div class="grid-100" style="margin-bottom: 20px;">
			<?php
			echo wp_kses(
				paginate_links(
					array(
						'base'      => add_query_arg( 'cpage', '%#%' ),
						'format'    => '',
						'prev_text' => __( '&laquo; Prev' ),
						'next_text' => __( 'Next &raquo;' ),
						'total'     => $category_posts->max_num_pages,
						'current'   => $paged_pg,
						'mid_size'  => 3,
						'type'      => 'list',
					)
				),
				$tags_permitidos
			);
			?>
		</div>
	</div>

	<div class="grid-25" id="mme-filtros">
		<h3><?php esc_html_e( 'Filter', 'mme-real-estate' ); ?></h3>
		<?php
		$get             = wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET ) ), 'my-nonce-action' );
		$arrkey_filtros  = array_keys( $_GET );
		$arrkey_filtrosn = array();

		foreach ( $arrkey_filtros as $k ) {
			if ( 'page_id' !== $k ) {
				$arrkey_filtrosn[] = $k;
			}
		}

		$page_id         = mmerealestate_rs_limpiar( 'page_id', 'GET' );
		$url_form_filtro = get_site_url() . '/' . get_option( 'pagina-propiedades' );

		if ( 'plain' === $type_permalink ) {
			$url_form_filtro = get_site_url() . "/?page_id=$page_id";
		}

		if ( count( $arrkey_filtrosn ) > 0 ) {
			?>
			<button class="button-propiedad" id="mme-remover-filtro-top" onclick="window.location.href='<?php echo esc_url( $url_form_filtro ); ?>'" >
				<?php esc_html_e( 'Remove filters', 'mme-real-estate' ); ?>
			</button>
			<?php
		}
		?>
 
		<form action="<?php echo esc_url( $url_form_filtro ); ?>" id="mme-filtros-div">
			<?php if ( 'plain' === $type_permalink ) { ?>
				<input type="hidden" name="page_id" id="page_id" value="<?php echo esc_attr( $page_id ); ?>"/>
			<?php } ?>
			<label>
				<?php echo $label_re_estado ?>:
			</label>
			<select name="estado" id="mme-state" style="width:100%">
				<option value="" selected="selected">
					<?php esc_html_e( 'All', 'mme-real-estate' ); ?>
				</option>
				<?php
				if ( ! empty( $arr_edos ) ) {
				 
					foreach ( $arr_edos AS $dt ) {
						$arr_data[] = array(
							' name '  => 'idedo',
							' value ' => $dt['id'],
						);
						echo wp_kses( mmerealestate_option_select( $dt['slug'], $dt['name'], $estado_fl, $arr_data ), $tags_permitidos );
						unset( $arr_data );
					}
				}
				?>
			</select>

			<label>
				<?php echo $label_re_ciudad ?>:
			</label>
			<select name="municipio" id="mme-city" style="width:100%">
				<?php
				if ( ! empty( $municipios ) ) {
					?>
					<option value="" selected="selected">
						<?php esc_html_e( 'All', 'mme-real-estate' ); ?>
					</option>
					<?php
					foreach ( $municipios as $mmunicipios ) {
						echo wp_kses( mmerealestate_option_select( $mmunicipios->slug, $mmunicipios->name, $municipio_fl ), $tags_permitidos );
					}
				}
				?>
			</select>

			<label>
				<?php echo $label_re_colonia ?>:
			</label>
			<select name="colonia" id="mme-neighborhood" style="width:100%">
				<?php
				if ( ! empty( $colonias ) ) {
					?>
					<option value="" selected="selected">
						<?php esc_html_e( 'All', 'mme-real-estate' ); ?>
					</option>
					<?php
					if ( isset( $fl_dt['propiedad_colonia'] ) ){
						foreach ( $colonias as $col ) {
							echo wp_kses( mmerealestate_option_select( $col, $col, $fl_dt['propiedad_colonia'] ), $tags_permitidos );
						}
					}
				}
				?>
			</select>

			<label>
				<?php echo $label_re_toperacion ?>:
			</label>
			<select name="operacion" id="mme-operation-type" style="width:100%">
				<?php
				if ( isset( $arrtp_operacion ) ) {
					foreach ( $arrtp_operacion['tipooperacion'] as $tp ) {
						echo wp_kses( $tp, $tags_permitidos );
					}
				} else if ( isset( $res_data['arrTO'] ) && ! empty( $res_data['arrTO'] ) ) {
					foreach ( $res_data['arrTO'] as $op ) {
						$x = explode( ',', $op );
						echo esc_attr( mmerealestate_option_sa( $x[0], $fl_dt['propiedad_operacion'], $x[1] ) );
					}
				}
				?>
			</select>

			<label>
				<?php echo $label_re_tpropiedad ?>:
			</label>
			<select name="tipo-propiedad" id="mme-property-type" style="width:100%">
				<?php
				if ( isset( $arrtp_propiedad ) ) {
					foreach ( $arrtp_propiedad['tipooperacion'] as $tp ) {
						echo wp_kses( $tp, $tags_permitidos );
					}
				} elseif ( isset( $res_data['arrTP'] ) && ! empty( $res_data['arrTP'] ) ) {
					foreach ( $res_data['arrTP'] as $op ) {
						$x = explode( ',', $op );
						echo esc_attr( mmerealestate_option_sa( $x[0], $fl_dt['propiedad_tipopropiedad'], $x[1] ) );
					}
				}
				?>
			</select>

			<label>
				<?php esc_html_e( 'Price range', 'mme-real-estate' ); ?>:
			</label>
			<input type="text" name="desde" autocomplete="off" class="mme-moneda moneda"
			placeholder="<?php esc_html_e( 'from', 'mme-real-estate' ); ?>"
			value="<?php echo esc_attr( $fl_dt['desde'] ); ?>">
			<input type="text" name="hasta" autocomplete="off" class="mme-moneda moneda"
			placeholder="<?php esc_html_e( 'to', 'mme-real-estate' ); ?>"
			value="<?php echo esc_attr( $fl_dt['hasta'] ); ?>">
			<p>
				<button>
					<?php esc_html_e( 'Search', 'mme-real-estate' ); ?>
				</button>
				<?php
				$base = explode( '?', get_pagenum_link( 1 ) );
				if ( count( $arrkey_filtrosn ) > 0 ) {
					?>
					<button class="button-propiedad" onclick="window.location.href='<?php echo esc_url( $url_form_filtro ); ?>';return false;">
						<?php esc_html_e( 'Remove filters', 'mme-real-estate' ); ?>
					</button>
					<?php
				}
				?>
			</p>
		</form>
	</div>
</div> 
