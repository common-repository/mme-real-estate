<?php
/**
 * Property view
 *
 * @package  MME Real Estate
 * @author   Multimedia Efectiva <info@multimediaefectiva.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$type_permalink  = mmerealestate_get_permalink_structure();
$margin_top_page = 20;
$max_width       = 1200;
$simbolo_moneda  = '$';
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
	'figure' => array(
		'class' => array(),
	),
	'iframe' => array(
		'src'             => array(),
		'height'          => array(),
		'style'           => array(),
		'allowfullscreen' => array(),
		'loading'         => array(),
	),
	'div'    => array(),
	'ul'     => array(),
	'li'     => array(),
	'strong' => array(),
	'p'      => array(),
	'span'   => array(),
	'hr'     => array(),
	'br'     => array(),
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
?>
<script type="text/javascript">
	var type_permalink = "<?php echo esc_js( $type_permalink ); ?>"
</script>
<?php
$plugin_dir_path = plugin_dir_path( __FILE__ );
$plugin_dir_path = str_replace( 'views/', '', $plugin_dir_path );
require $plugin_dir_path . '/commons.php';

get_header();

$post_property = get_post();
$id_prop       = $post_property->ID;
$args          = array(
	'post_type'   => 'attachment',
	'order'       => 'DESC',
	'limit'       => 1,
	'post_parent' => $id_prop,
);
$attachments   = get_posts( $args );

$tmb_img = get_the_post_thumbnail( $id_prop, 'large' );
if ( empty( $tmb_img ) ) {
	$tmb_img = '<img width="250px" src="' . MME_URL_PLUG . 'image-not-available.png">';
}

$tipo_prop[1] = __( 'Warehouse', 'mme-real-estate' );
$tipo_prop[2] = __( 'House room', 'mme-real-estate' );
$tipo_prop[3] = __( 'Apartment', 'mme-real-estate' );
$tipo_prop[4] = __( 'Business premises', 'mme-real-estate' );
$tipo_prop[5] = __( 'Office', 'mme-real-estate' );
$tipo_prop[6] = __( 'Land', 'mme-real-estate' );
$tipo_prop[7] = __( 'Other', 'mme-real-estate' );

// State and localitie.
$tax_estados = get_the_terms( $id_prop, 'mmelocalities' );
if ( ! empty( $tax_estados ) ) {
	foreach ( $tax_estados as $edos ) {
		if ( isset( $edos->parent ) && 0 === $edos->parent ) {
			$estado   = $edos->name;
			$slug_edo = $edos->slug;
		} elseif ( isset( $edos->parent ) && $edos->parent > 0 ) {
			$municipio = $edos->name;
			$slug_mun  = $edos->slug;
		}
	}
}
?>
<div id="mme-content-propiedades" style="margin-top:<?php echo esc_html( $margin_top_page ) . 'px;max-width:' . esc_html( $max_width ) . 'px'; ?>">
	<div class="grid-100">
		<?php
		if ( get_option( 'mostrar_titulo' ) === 'on' ) {
			?>
		<h1 class="re-titulo"><?php echo esc_html( $post_property->post_title ); ?></h1>
			<?php
		}
		$datos_prop = get_post_meta( $id_prop );
		$keys_prop  = array_keys( $datos_prop );
		foreach ( $keys_prop as $k ) {
			$datos[ $k ] = $datos_prop[ $k ][0];
		}

		$type_permalink = mmerealestate_get_permalink_structure();
		$mypost         = get_page_by_path( get_option( 'pagina-propiedades' ), '', 'page' );

		$url_bc = get_site_url() . '/' . get_option( 'pagina-propiedades' ) . '/?';
		if ( 'plain' === $type_permalink ) {
			$url_bc = get_site_url() . '/?page_id=' . $mypost->ID . '&';
		}

		switch ( $datos['propiedad_operacion'] ) {
			case 1:
				$operacion_label = __( 'Sale', 'mme-real-estate' );
				break;
			case 2:
				$operacion_label = __( 'Rent', 'mme-real-estate' );
				break;
		}
		
		$label_re_toperacion = get_option( 'label_re_toperacion' );
	  $label_re_tpropiedad = get_option( 'label_re_tpropiedad' );
	  $label_re_estado     = get_option( 'label_re_estado' );
	  $label_re_ciudad     = get_option( 'label_re_ciudad' );
		
		if( empty( $label_re_toperacion ) ){
		  $label_re_toperacion = __( 'Operation type', 'mme-real-estate' ); 
		}
		if( empty( $label_re_tpropiedad ) ){
		  $label_re_tpropiedad = __( 'Property type', 'mme-real-estate' ); 
		}
		if( empty( $label_re_estado ) ){
		  $label_re_estado = __( 'State', 'mme-real-estate' ); 
		}
		if( empty( $label_re_ciudad ) ){
		  $label_re_ciudad = __( 'City or County', 'mme-real-estate' ); 
		}  
		?>

		<!-- BREADCRUMBS -->
		<ul class="breadcrumb">
			<li>
				<a href="<?php echo esc_url( $url_bc ); ?>">
					<?php esc_html_e( 'Properties', 'mme-real-estate' ); ?>
				</a>
			</li>
			<?php $url_bc .= 'operacion=' . $datos['propiedad_operacion']; ?>
			<li class="categoria">
				<a href="<?php echo esc_url( $url_bc ); ?>">
						<strong> <?php esc_html_e( $label_re_toperacion ); ?>: </strong>
						<span><?php echo esc_html( $operacion_label ); ?></span>
				</a>
			</li>
			<?php $url_bc .= '&tipo-propiedad=' . $datos['propiedad_tipopropiedad']; ?>
			<li>
				<a href="<?php echo esc_url( $url_bc ); ?>">
					<strong><?php esc_html_e( $label_re_tpropiedad ); ?>:</strong>
					<?php echo esc_html( $tipo_prop[ $datos['propiedad_tipopropiedad'] ] ); ?>
				</a>
			</li>
			<?php
			if ( isset( $slug_edo ) ) {
				$url_bc .= '&estado=' . $slug_edo
				?>
			<li>
				<a href="<?php echo esc_url( $url_bc ); ?>">
					<strong><?php esc_html_e( $label_re_estado ); ?>:</strong>
					<?php echo esc_html( $estado ); ?>
				</a>
			</li>
				<?php
			}
			if ( isset( $slug_mun ) ) {
				$url_bc .= '&municipio=' . $slug_mun;
				?>
			<li>
				<a href="<?php echo esc_url( $url_bc ); ?>">
					<strong><?php esc_html_e( $label_re_ciudad ); ?>:</strong>
					<?php echo esc_html( $municipio ); ?>
				</a>
			</li>
				<?php
			}
			?>
		</ul>

		<!-- TABS NAV -->
		<ul id="tabs-nav">
			<li class="tab-active">
				<a href="#tab-generales" rel="nofollow">
					<?php esc_html_e( 'General data', 'mme-real-estate' ); ?>
				</a>
			</li>
			<li class=''>
				<a href="#tab-especificaciones" rel="nofollow">
					<?php esc_html_e( 'Specifications', 'mme-real-estate' ); ?>
				</a>
			</li>
			<?php
			$hay_mapa = false;
			if ( ! empty( $datos['propiedad_mapa'] ) ) {
				$pos = strpos( $datos['propiedad_mapa'], 'google.com/maps/embed' );
				if ( false !== $pos ) {
					$hay_mapa = true;
					?>
			<li class=''>
				<a href="#tab-mapa" rel="nofollow"><?php esc_html_e( 'Map', 'mme-real-estate' ); ?></a>
			</li>
					<?php
				}
			}
			if ( ! empty( $datos['propiedad_url_youtube'] ) ) {
				$video = explode( 'v=', $datos['propiedad_url_youtube'] );
				if ( ! isset( $video[1] ) ) {
					$video = explode( '/', $datos['propiedad_url_youtube'] );
					if ( isset( $video[3] ) ) {
						$video[1] = $video[3];
					}
				}
				if ( isset( $video[1] ) ) {
					?>
			<li class=''>
				<a href="#tab-video" rel="nofollow"><?php esc_html_e( 'Video', 'mme-real-estate' ); ?></a>
			</li>
					<?php
				}
			}
			if ( get_option( 'mostrar_form' ) === 'on' ) {
				?>
			<li class=''>
				<a href="#tab-informes" rel="nofollow"><?php esc_html_e( 'Request more information', 'mme-real-estate' ); ?></a>
			</li>
				<?php
			}
			?>
		</ul>


		<div id="tabs-stage">

			<!-- GENERAL DATA TAB -->
			<div id="tab-generales" style="display: none;">
				<div class="grid-parent">
					<div class="grid-25">
						<div class="mme-content-espacio">
							<p><?php echo wp_kses( $tmb_img, $tags_permitidos ); ?></p>
						</div>
					</div>
					<div class="grid-75">
						<p>
							<strong>
								<?php
								echo esc_html( $operacion_label );
								if ( ! empty( $datos['propiedad_precio'] ) ) {
									echo esc_html( ': ' . mmerealestate_formato_moneda( $datos['propiedad_precio'], $simbolo_moneda ) );
								}
								?>
							</strong>
							<?php
							if ( ! empty( $datos['propiedad_superficie_total'] ) ) {
								$total_area_show  = ' | <strong>';
								$total_area_show .= __( 'Total area', 'mme-real-estate' );
								$total_area_show .= ': </strong>';
								$total_area_show .= $datos['propiedad_superficie_total'];
								echo wp_kses( $total_area_show, $tags_permitidos );
							}
							if ( ! empty( $datos['propiedad_superficie_construida'] ) ) {
								$builded_area_show  = ' | <strong>';
								$builded_area_show .= __( 'Builded area', 'mme-real-estate' );
								$builded_area_show .= ': </strong>';
								$builded_area_show .= $datos['propiedad_superficie_construida'];
								echo wp_kses( $builded_area_show, $tags_permitidos );
							}
							?>
						</p>
						<hr class="mme-separador">
						<?php
						if ( ! empty( $post_property->post_content ) ) {
							?>
							<p>
							<?php
							$content = apply_filters( 'the_content', get_the_content() );
							echo wp_kses( $content, $tags_permitidos );
							?>
							</p>
							<?php
						}
						?>
					</div>
				</div>
			</div>

			<!-- MAP TAB -->
			<?php
			if ( $hay_mapa ) {
				?>
				<div id="tab-mapa" style="display: block;">
					<?php
						$mapa = $datos['propiedad_mapa'];
						$mapa = str_replace( '&#60;', '<', $mapa );
						$mapa = str_replace( '&#62;', '>', $mapa );
						$mapa = str_replace( '&#34;', '"', $mapa );
						echo wp_kses( $mapa, $tags_permitidos );
					?>
				</div>
				<?php
			}
			?>

			<!-- YOUTUBE TAB -->
			<?php
			if ( isset( $video[1] ) ) {
				?>
				<div id="tab-video" class="text-center" style="display: block;">
					<figure class="wp-block-embed-youtube wp-block-embed is-type-video is-provider-youtube wp-embed-aspect-4-3 wp-has-aspect-ratio">
						<div class="wp-block-embed__wrapper">
						  <div>
								<iframe title="<?php echo esc_attr( $post_property->post_title ); ?>" width="600px" height="480px" src="https://www.youtube.com/embed/<?php echo esc_html( $video[1] ); ?>?feature=oembed" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
							</div>
						</div>
					</figure>
				</div>
				<?php
			}
			?>

			<!-- SPECS TAB -->
			<div id="tab-especificaciones" style="display: block;">
				<div class="grid-parent">

					<!-- TABLE 1 -->
					<div class="grid-50" id="tabla-1">
						<div class="mme-content-espacio">
							<table>
								<tbody>
									<tr>
										<td>
											<?php esc_html_e( 'Operation type', 'mme-real-estate' ); ?>:
										</td>
										<td><?php echo esc_html( ucwords( $operacion_label ) ); ?></td>
									</tr>
									<?php
									if ( ! empty( $datos['propiedad_precio'] ) ) {
										?>
									<tr>
										<td><?php esc_html_e( 'Price', 'mme-real-estate' ); ?>:</td>
										<td><?php echo esc_html( mmerealestate_formato_moneda( $datos['propiedad_precio'], $simbolo_moneda ) ); ?></td>
									</tr>
										<?php
									}
									?>
									<tr>
										<td>
											<?php esc_html_e( 'Property type', 'mme-real-estate' ); ?>:
										</td>
										<td>
											<?php echo esc_html( $tipo_prop[ $datos['propiedad_tipopropiedad'] ] ); ?>
										</td>
									</tr>
									<?php
									if ( ! empty( $datos['propiedad_superficie_total'] ) ) {
										?>
									<tr>
										<td><?php esc_html_e( 'Total area', 'mme-real-estate' ); ?>:</td>
										<td><?php echo esc_html( $datos['propiedad_superficie_total'] ); ?></td>
									</tr>
										<?php
									}
									if ( ! empty( $datos['propiedad_superficie_construida'] ) ) {
										?>
									<tr>
										<td><?php esc_html_e( 'Builded area', 'mme-real-estate' ); ?>:</td>
										<td><?php echo esc_html( $datos['propiedad_superficie_construida'] ); ?></td>
									</tr>
										<?php
									}
									if ( ! empty( $datos['propiedad_calle'] ) ) {
										?>
									<tr>
										<td><?php esc_html_e( 'Address', 'mme-real-estate' ); ?>:</td>
										<td>
											<?php
											if ( ! empty( $datos['propiedad_calle'] ) ) {
												echo esc_html( $datos['propiedad_calle'] );
											}
											?>
										</td>
									</tr>
										<?php
									}
									if ( ! empty( $datos['propiedad_colonia'] ) ) {
										?>
									<tr>
										<td><?php esc_html_e( 'Neighborhood', 'mme-real-estate' ); ?>:</td>
										<td><?php echo esc_html( $datos['propiedad_colonia'] ); ?></td>
									</tr>
										<?php
									}
									if ( isset( $municipio ) ) {
										?>
									<tr>
										<td>
											<?php esc_html_e( 'City or County', 'mme-real-estate' ); ?>
										</td>
										<td>
											<?php echo esc_html( $municipio ); ?>
										</td>
									</tr>
										<?php
									}
									if ( isset( $estado ) ) {
										?>
									<tr>
										<td>
											<?php esc_html_e( 'State', 'mme-real-estate' ); ?>:
										</td>
										<td>
											<?php echo esc_html( $estado ); ?>
										</td>
									</tr>
										<?php
									}
									?>
								</tbody>
							</table>
						</div>
					</div>

					<!-- TABLE 2 -->
					<div class="grid-50" id="tabla-2">
						<table id="tabla2">
							<tbody>
								<?php
								// levels
								if ( ! empty( $datos['propiedad_niveles'] ) ) {
									?>
								<tr>
									<td><?php esc_html_e( 'Levels (floors)', 'mme-real-estate' ); ?>:</td>
									<td><?php echo esc_html( $datos['propiedad_niveles'] ); ?></td>
								</tr>
									<?php
								}
								// restrooms
								if ( ! empty( $datos['propiedad_recamaras'] ) ) {
									?>
								<tr>
									<td><?php esc_html_e( 'Beedrooms', 'mme-real-estate' ); ?>:</td>
									<td><?php echo esc_html( $datos['propiedad_recamaras'] ); ?></td>
								</tr>
									<?php
								}
								// parking
								if ( ! empty( $datos['propiedad_espacios_auto'] ) ) {
									?>
								<tr>
									<td><?php esc_html_e( 'Places to park cars', 'mme-real-estate' ); ?>:</td>
									<td><?php echo esc_html( $datos['propiedad_espacios_auto'] ); ?></td>
								</tr>
									<?php
								}
								// full bathrooms
								if ( ! empty( $datos['propiedad_banos'] ) ) {
									?>
								<tr>
									<td><?php esc_html_e( 'Full bathrooms', 'mme-real-estate' ); ?>:</td>
									<td><?php echo esc_html( $datos['propiedad_banos'] ); ?></td>
								</tr>
									<?php
								}
								// half bathrooms
								if ( ! empty( $datos['propiedad_medios_banos'] ) ) {
									?>
								<tr>
									<td><?php esc_html_e( 'Half bathrooms', 'mme-real-estate' ); ?>:</td>
									<td><?php echo esc_html( $datos['propiedad_medios_banos'] ); ?></td>
								</tr>
									<?php
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
				<?php if ( ! empty( $datos['propiedad_espacios'] ) or ! empty( $datos['propiedad_servicios'] ) ){
					?>
					<div class="grid-parent">
						<div class="grid-100" id="tabla-3">
							<table id="tabla3">
								<tbody>
									<?php
									// open spaces & amenities
									if ( ! empty( $datos['propiedad_espacios'] ) ) {
										?>
									<tr>
										<td><?php esc_html_e( 'Open spaces and amenities', 'mme-real-estate' ); ?>:</td>
										<td><?php echo esc_html( $datos['propiedad_espacios'] ); ?></td>
									</tr>
										<?php
									}
									// equipment & services
									if ( ! empty( $datos['propiedad_servicios'] ) ) {
										?>
									<tr>
										<td><?php esc_html_e( 'Equipment and services', 'mme-real-estate' ); ?>:</td>
										<td><?php echo esc_html( $datos['propiedad_servicios'] ); ?></td>
									</tr>
										<?php
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				<?php
				}
				?>
			</div>

			<?php
			if ( get_option( 'mostrar_form' ) === 'on' || empty( get_option( 'mostrar_form' ) ) ) {
				?>
			<!-- INFO FORM -->
			<div id="tab-informes" style="display: block;">
				<div class="grid-parent" id="informes">
					<div class="grid-75">
						<div class="mme-content-espacio">
							<form method="POST" id="form-data">
								<label>
									<?php esc_html_e( 'Name', 'mme-real-estate' ); ?>: <input type="text" name="nombre" id="nombre" autocomplete="off"/>
								</label>
								<label>
								  <?php esc_html_e( 'Phone', 'mme-real-estate' ); ?>*: <input type="text" name="telefono" id="telefono" autocomplete="off" required />
								</label>
								<label>
									Subject*: <input type="text" name="subject" id="subject" autocomplete="uiwiqo" />
								</label>
								<label>
									<?php esc_html_e( 'Email', 'mme-real-estate' ); ?>: <input type="email" name="email" id="email" autocomplete="off" />
								</label>
								<input type="hidden" name="url" id="url"
								value="<?php echo esc_url( get_permalink( get_the_ID() ) ) ?>" />
								<input type="hidden" name="titulo" id="titulo"
								value="<?php echo esc_attr( $post_property->post_title ); ?>" />
								<label><?php esc_html_e( 'Message', 'mme-real-estate' ); ?>*:
									<textarea name="mensaje" id="mensaje" rows="5" autocomplete="off" required></textarea></label>
								<button><?php esc_html_e( 'Send', 'mme-real-estate' ); ?></button>
							</form>
							<div id="respuesta"></div>
							<hr>
						</div>
					</div>
					<div class="grid-25">
						<p>
							<?php echo wp_kses( $tmb_img, $tags_permitidos ); ?>
						</p>
					</div>
				</div>
			</div>
				<?php
			}
			?>
		</div>

		<div id="contenido-pdf" style="display:none">
			<!-- HTML Content goes here -->
		</div>
		<table id="tabla-pdf" style="display:none"></table>
		<div id="elementH">
		</div>

		<?php
		global $wp;
		$url = home_url( add_query_arg( array(), $wp->request ) );
		?>
	</div>
</div> 
<?php
get_footer();
