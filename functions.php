<?php
/**
 * Helpers and functions
 * 
 * @package  MME Real Estate
 * @author   Multimedia Efectiva <info@multimediaefectiva.com>
 */

/**
 * WP get_option and update_option
 *
 * @param   string  $option  Var name in the form.
 * @param   string  $chk     Checked field.
 *
 * @return  bool             True if the value was updated, false otherwise.
 */
function mmerealestate_actualizar_opcion( $option, $chk ) {
	$opcion_valor = false;
	$activar      = false;

	if ( $chk ) {
		$activar = true;
	} // With one that is different change this var

	$arr_opt[]    = 'mostrar_titulo';
	$arr_opt[]    = 'mostrar_form';
	$arr_opt[]    = 'mostrar_poweredby';
	$option_verif = filter_input( INPUT_POST, $option, FILTER_UNSAFE_RAW );
	if ( ! empty( $option_verif ) ) {
		$opcion_valor = trim( sanitize_text_field( $option_verif ) );
	} elseif ( in_array( $option_verif, $arr_opt, true ) ) {
		$opcion_valor = 'off';
	}

	if ( get_option( $option ) !== $opcion_valor ) {
		$activar = update_option( $option, $opcion_valor );
	}

	return $activar;
}

/**
 * Location of translation files.
 */
function mmerealestate_load_textdomain() {
	$text_domain    = 'mme-real-estate';
	$path_languages = basename( dirname( __FILE__ ) ) . '/languages/';
	load_plugin_textdomain( $text_domain, false, $path_languages );
}

/**
 * Show in the plugin menu the items for configuration and how to start.
 */
function mmerealestate_admin_menu_page() {
	add_submenu_page(
		'edit.php?post_type=mmeproperties',
		__( 'Set up', 'mme-real-estate' ),
		__( 'Set up', 'mme-real-estate' ),
		'manage_options',
		MME_RUTARE . 'admin/views/set-up.php',
		'',
		4
	);
	add_submenu_page(
		'edit.php?post_type=mmeproperties',
		__( 'Shortcodes', 'mme-real-estate' ),
		__( 'Shortcodes', 'mme-real-estate' ),
		'manage_options',
		MME_RUTARE . 'admin/views/shortcodes.php',
		''
	);
	add_submenu_page(
		'edit.php?post_type=mmeproperties',
		__( 'How to start', 'mme-real-estate' ),
		__( 'How to start', 'mme-real-estate' ),
		'manage_options',
		MME_RUTARE . 'admin/views/how-to-start.php',
		''
	);
	
}

/**
 * Sanitizes the variables submitted by the form.
 *
 * @param   string  $variable  The name of the variable in the form.
 * @param   string  $metodo    The method by which the variable is obtained: GET, POST or SERVER.
 *
 * @return  string             The sanitized variable
 */
function mmerealestate_rs_limpiar( $variable, $metodo ) {
	if ( $variable ) {
		switch ( $metodo ) {
			case 'GET':
				$limpia = filter_input( INPUT_GET, $variable, FILTER_SANITIZE_SPECIAL_CHARS );
				break;
			case 'POST':
				$limpia = filter_input( INPUT_POST, $variable, FILTER_SANITIZE_SPECIAL_CHARS );
				break;
			case 'SERVER':
				$limpia = filter_input( INPUT_SERVER, $variable, FILTER_SANITIZE_URL );
		}
	} else {
		$limpia = false;
	}
	return $limpia;
}

/**
 * Load JS scripts and CSS styles only in the section of the dashboard that corresponds to it.
 * We only load the styles and scripts if we are on the plugin page: mme-real-estate/admin/views/admin.php.
 */
function mmerealestate_load_css_admin() {
	global $pagenow;
	wp_register_style( 'unsemantic', MME_URL_PLUG_AD . 'admin/css/unsemantic-grid-responsive.css', array(), '1.0' );
	wp_register_style( 'style', MME_URL_PLUG_AD . 'admin/css/mme-realestate-admin-style.css', array(), '1.0' );
	wp_register_style( 'style-new-property', MME_URL_PLUG_AD . 'admin/css/style-new-property.css', array(), '1.0' );

	$views[] = 'mme-real-estate/admin/views/set-up.php';
	$views[] = 'mme-real-estate/admin/views/how-to-start.php';
	$views[] = 'mme-real-estate/admin/views/shortcodes.php';
	$views[] = 'mme-real-estate/admin/mainadmin.php';

	if ( 'post-new.php' === $pagenow || 'post.php' === $pagenow || 'edit.php' === $pagenow ) {
		wp_enqueue_style( 'style-new-property' );
		wp_enqueue_script( 'admin_js', MME_URL_PLUG_AD . 'admin/js.js', array(), '1.0', true );
	}
	$page = mmerealestate_rs_limpiar( 'page', 'GET' );
	if ( ! empty( $page ) && in_array( $page, $views, true ) ) {
		wp_enqueue_style( 'unsemantic' );
		wp_enqueue_style( 'style' );
	}
}

/**
 * Arguments and tags of the post type "Property"
 */
function mmerealestate_propiedades() {
	$labels = array(
		'name'               => __( 'MME Real Estate', 'mme-real-estate' ),
		'all_items'          => __( 'Property list', 'mme-real-estate' ),
		'singular_name'      => __( 'Property', 'mme-real-estate' ),
		'add_new'            => __( 'New property', 'mme-real-estate' ),
		'add_new_item'       => __( 'New property', 'mme-real-estate' ),
		'edit_item'          => __( 'Edit Property', 'mme-real-estate' ),
		'new_item'           => __( 'New Property', 'mme-real-estate' ),
		'view_item'          => __( 'See Property', 'mme-real-estate' ),
		'search_items'       => __( 'Search Property', 'mme-real-estate' ),
		'not_found'          => __( 'No properties found', 'mme-real-estate' ),
		'not_found_in_trash' => __( 'No properties found in trash', 'mme-real-estate' ),
	);

	$args = array(
		'labels'        => $labels,
		'public'        => true,
		'hierarchical'  => false,
		'menu_position' => 2,
		'menu_icon'     => 'dashicons-admin-multisite',
		'query_var'     => true,
		'supports'      => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'revisions', 'page-attributes', 'post-formats' ),
		'show_in_rest'  => true,
		'rewrite'       => array( 'slug' => get_option( 'pagina-propiedades' ) ),
	);

	register_post_type( 'mmeproperties', $args );
	flush_rewrite_rules();
}

/**
 * Function that registers the metabox to feed the data of the post type "Property"
 */
function mmerealestate_metaboxes() {
	add_meta_box( 're_meta_box', __( 'Property data', 'mme-real-estate' ), 'mmerealestate_mb1content_callbackdos', 'mmeproperties', 'normal' );
}

/**
 * Form that is shown in the edition of the post type "Property"
 *
 * @param array $post Post data.
 */
function mmerealestate_mb1content_callbackdos( $post ) {
	$values        = get_post_custom( $post->ID );
	$postget       = mmerealestate_rs_limpiar( 'post', 'GET' );
	$post_get      = mmerealestate_rs_limpiar( 'post', 'GET' );
	$selected      = isset( $values['propiedad_operacion'] ) ? esc_attr(
		$values['propiedad_operacion'][0]
	) : '';
	$tipopropiedad = isset( $values['propiedad_tipopropiedad'] ) ? esc_attr(
		$values['propiedad_tipopropiedad'][0]
	) : '';

	?>

	<div class="row">
		<div class="col-33">
			<div class="content-col">
				<label><?php esc_html_e( 'Operation type', 'mme-real-estate' ); ?>:
					<select name="propiedad_operacion" id="propiedad_operacion">
						<option value="1" <?php selected( $selected, 'venta' ); ?>>
							<?php esc_html_e( 'Sale', 'mme-real-estate' ); ?>
						</option>
						<option value="2" <?php selected( $selected, 'renta' ); ?>>
							<?php esc_html_e( 'Rent', 'mme-real-estate' ); ?>
						</option>
					</select>
				</label>
			</div>
		</div>
		<div class="col-33">
			<div class="content-col">
				<label><?php esc_html_e( 'Property type', 'mme-real-estate' ); ?>:
					<select name="propiedad_tipopropiedad" id="propiedad_tipopropiedad">
						<option value="1" <?php selected( $tipopropiedad, 1 ); ?>>
							<?php esc_html_e( 'Warehouse', 'mme-real-estate' ); ?>
						</option>
						<option value="2" <?php selected( $tipopropiedad, 2 ); ?>>
							<?php esc_html_e( 'House room', 'mme-real-estate' ); ?>
						</option>
						<option value="3" <?php selected( $tipopropiedad, 3 ); ?>>
							<?php esc_html_e( 'Apartment', 'mme-real-estate' ); ?>
						</option>
						<option value="4" <?php selected( $tipopropiedad, 4 ); ?>>
							<?php esc_html_e( 'Business premises', 'mme-real-estate' ); ?>
						</option>
						<option value="5" <?php selected( $tipopropiedad, 5 ); ?>>
							<?php esc_html_e( 'Office', 'mme-real-estate' ); ?>
						</option>
						<option value="6" <?php selected( $tipopropiedad, 6 ); ?>>
							<?php esc_html_e( 'Land', 'mme-real-estate' ); ?>
						</option>
						<option value="7"
						<?php
						selected( $tipopropiedad, 7 );
						if ( empty( $tipopropiedad ) ) {
							echo 'selected';
						}
						?>
						>
							<?php esc_html_e( 'Other', 'mme-real-estate' ); ?>
						</option>
					</select>
					</label>
			</div>
		</div>
		<div class="col-33">
			<div class="content-col">
				<label><?php esc_html_e( 'Neighborhood', 'mme-real-estate' ); ?>:
					<input type='text' name='propiedad_colonia' autocomplete="off"
					<?php $propiedad_precio = mmerealestate_rs_limpiar( 'propiedad_precio', 'POST' ); ?>
					value="<?php echo esc_html( get_post_meta( $post_get, 'propiedad_colonia', true ) ); ?>"
					maxlength="64" id='propiedad_colonia'>
				</label>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-33">
			<div class="content-col">
				<label><?php esc_html_e( 'Address', 'mme-real-estate' ); ?>:
					<input type='text' name='propiedad_calle' autocomplete="off"
					<?php $propiedad_calle = get_post_meta( $post_get, 'propiedad_calle', true ); ?>
					value="<?php echo esc_html( $propiedad_calle ); ?>" maxlength="64" id='propiedad_calle'>
				</label>
			</div>
		</div>
		<div class="col-33">
			<div class="content-col">
				<label> 
					<?php esc_html_e( 'Price', 'mme-real-estate' ); ?>:
					<input type='text' class="moneda" name='propiedad_precio' autocomplete="off"
					value='<?php echo esc_html( get_post_meta( $post_get, 'propiedad_precio', true ) ); ?>'
					maxlength="64" id='propiedad_precio'>
				</label>
			</div>
		</div>
		<div class="col-33">
			<div class="content-col">
				<label>
					<?php esc_html_e( 'Total area', 'mme-real-estate' ); ?>:
					<input type='text' name='propiedad_superficie_total' autocomplete="off"
					value="<?php echo esc_html( get_post_meta( $post_get, 'propiedad_superficie_total', true ) ); ?>" maxlength="64" id='propiedad_superficie_total'>
				</label>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-33">
			<div class="content-col">
				<label>
					<?php esc_html_e( 'Builded area', 'mme-real-estate' ); ?>:
					<input type='text' name='propiedad_superficie_construida' autocomplete="off"
					value='<?php echo esc_html( get_post_meta( $post_get, 'propiedad_superficie_construida', true ) ); ?>' maxlength="64" id='propiedad_superficie_construida'>
				</label>
			</div>
		</div>
		<div class="col-33">
			<div class="content-col">
				<label>
					<?php esc_html_e( 'Levels ( floors )', 'mme-real-estate' ); ?>:
					<input type='text' name='propiedad_niveles' autocomplete="off"
					value='<?php echo esc_html( get_post_meta( $post_get, 'propiedad_niveles', true ) ); ?>'
					maxlength="64" id='propiedad_niveles'>
				</label>
			</div>
		</div>
		<div class="col-33">
			<div class="content-col">
				<label>
					<?php esc_html_e( 'Beedrooms', 'mme-real-estate' ); ?>:
					<input type='text' name='propiedad_recamaras' autocomplete="off"
					value="<?php echo esc_html( get_post_meta( $post_get, 'propiedad_recamaras', true ) ); ?>"
					maxlength="64" id='propiedad_recamaras'>
				</label>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-33">
			<div class="content-col">
				<label>
					<?php esc_html_e( 'Places to park cars', 'mme-real-estate' ); ?>:
					<input type='text' name='propiedad_espacios_auto' autocomplete="off"
					value="<?php echo esc_html( get_post_meta( $post_get, 'propiedad_espacios_auto', true ) ); ?>" maxlength="64" id='propiedad_espacios_auto'>
				</label>
			</div>
		</div>
		<div class="col-33">
			<div class="content-col">
				<label>
					<?php esc_html_e( 'Full bathrooms', 'mme-real-estate' ); ?>:
					<input type='text' name='propiedad_banos' autocomplete="off"
					value="<?php echo esc_html( get_post_meta( $post_get, 'propiedad_banos', true ) ); ?>" maxlength="64" id='propiedad_banos'>
				</label>
			</div>
		</div>
		<div class="col-33">
			<div class="content-col">
				<label>
					<?php esc_html_e( 'Half bathrooms', 'mme-real-estate' ); ?>:
					<input type='text' name='propiedad_medios_banos' autocomplete="off"
					value='<?php echo esc_html( get_post_meta( esc_html( $post_get ), 'propiedad_medios_banos', true ) ); ?>' maxlength="64" id='propiedad_medios_banos'>
				</label>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-100">
			<div class="content-col">
				<label>
					<?php esc_html_e( 'Open spaces and amenities', 'mme-real-estate' ); ?>:
					<textarea name='propiedad_espacios' id="propiedad_espacios" style="width:100%"><?php echo esc_html( get_post_meta( $post_get, 'propiedad_espacios', true ) ); ?></textarea>
				</label>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-100">
			<div class="content-col">
				<label>
					<?php esc_html_e( 'Equipment and services', 'mme-real-estate' ); ?>:
					<textarea name='propiedad_servicios' id="propiedad_servicios" style="width:100%"><?php echo esc_html( get_post_meta( $post_get, 'propiedad_servicios', true ) ); ?></textarea>
				</label>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-100">
			<div class="content-col">
				<label>
					<?php esc_html_e( 'Iframe map', 'mme-real-estate' ); ?>:
					<textarea name='propiedad_mapa' id='propiedad_mapa' autocomplete="off"><?php echo esc_html( get_post_meta( $post_get, 'propiedad_mapa', true ) ); ?></textarea>
				</label>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-100">
			<div class="content-col">
				<label>
					<?php esc_html_e( 'Youtube video link', 'mme-real-estate' ); ?>:
					<input type='text' name='propiedad_url_youtube' autocomplete="off"
					value='<?php echo esc_html( get_post_meta( $post_get, 'propiedad_url_youtube', true ) ); ?>' id='propiedad_url_youtube'>
				</label>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Save the variables of the post type "Property".
 *
 * @param int    $post_id Post ID.
 * @param string $tipo    The post type that calls the "guardar" function.
 */
function mmerealestate_guardar( $post_id, $tipo ) {
	$tags_permitidos = array(
		'iframe' => array(
			'src'             => array(),
			'height'          => array(),
			'style'           => array(),
			'allowfullscreen' => array(),
			'loading'         => array(),
		),
	);
	if ( 'mmeproperties' === $tipo->post_type ) {
		$propiedad_colonia = mmerealestate_rs_limpiar( 'propiedad_colonia', 'POST' );
		update_post_meta( $post_id, 'propiedad_colonia', sanitize_text_field( $propiedad_colonia ) );
		$propiedad_calle = mmerealestate_rs_limpiar( 'propiedad_calle', 'POST' );
		update_post_meta( $post_id, 'propiedad_calle', sanitize_text_field( $propiedad_calle ) );
		$propiedad_numero = mmerealestate_rs_limpiar( 'propiedad_numero', 'POST' );
		update_post_meta( $post_id, 'propiedad_numero', sanitize_text_field( $propiedad_numero ) );
		$propiedad_mapa = mmerealestate_rs_limpiar( 'propiedad_mapa', 'POST' );
		update_post_meta( $post_id, 'propiedad_mapa', $propiedad_mapa );
		$propiedad_precio = mmerealestate_rs_limpiar( 'propiedad_precio', 'POST' );
		$precio = str_replace( ',', '', sanitize_text_field( $propiedad_precio ) );
		update_post_meta( $post_id, 'propiedad_precio', $precio );
		$propiedad_superficie_total = mmerealestate_rs_limpiar( 'propiedad_superficie_total', 'POST' );
		update_post_meta( $post_id, 'propiedad_superficie_total', sanitize_text_field( $propiedad_superficie_total ) );
		$propiedad_superficie_construida = mmerealestate_rs_limpiar( 'propiedad_superficie_construida', 'POST' );
		update_post_meta( $post_id, 'propiedad_superficie_construida', sanitize_text_field( $propiedad_superficie_construida ) );
		$propiedad_niveles = mmerealestate_rs_limpiar( 'propiedad_niveles', 'POST' );
		update_post_meta( $post_id, 'propiedad_niveles', sanitize_text_field( $propiedad_niveles ) );
		$propiedad_recamaras = mmerealestate_rs_limpiar( 'propiedad_recamaras', 'POST' );
		update_post_meta( $post_id, 'propiedad_recamaras', sanitize_text_field( $propiedad_recamaras ) );
		$propiedad_espacios_auto = mmerealestate_rs_limpiar( 'propiedad_espacios_auto', 'POST' );
		update_post_meta( $post_id, 'propiedad_espacios_auto', sanitize_text_field( $propiedad_espacios_auto ) );
		$propiedad_banos = mmerealestate_rs_limpiar( 'propiedad_banos', 'POST' );
		update_post_meta( $post_id, 'propiedad_banos', sanitize_text_field( $propiedad_banos ) );
		$propiedad_medios_banos = mmerealestate_rs_limpiar( 'propiedad_medios_banos', 'POST' );
		update_post_meta( $post_id, 'propiedad_medios_banos', sanitize_text_field( $propiedad_medios_banos ) );
		$propiedad_url_youtube = mmerealestate_rs_limpiar( 'propiedad_url_youtube', 'POST' );
		update_post_meta( $post_id, 'propiedad_url_youtube', sanitize_text_field( $propiedad_url_youtube ) );
		$propiedad_espacios = mmerealestate_rs_limpiar( 'propiedad_espacios', 'POST' );
		update_post_meta( $post_id, 'propiedad_espacios', sanitize_text_field( $propiedad_espacios ) );
		$propiedad_servicios = mmerealestate_rs_limpiar( 'propiedad_servicios', 'POST' );
		update_post_meta( $post_id, 'propiedad_servicios', sanitize_text_field( $propiedad_servicios ) );
		$propiedad_operacion = mmerealestate_rs_limpiar( 'propiedad_operacion', 'POST' );
		update_post_meta( $post_id, 'propiedad_operacion', sanitize_text_field( $propiedad_operacion ) );
		$propiedad_tipopropiedad = mmerealestate_rs_limpiar( 'propiedad_tipopropiedad', 'POST' );
		update_post_meta( $post_id, 'propiedad_tipopropiedad', sanitize_text_field( $propiedad_tipopropiedad ) );
	}
}

/**
 * Send email by AJAX
 *
 * @return  json                  html in array in json format
 */
function mmerealestate_post_form() {
	header( 'Content-Type: application/json' );

	$nombre   = sanitize_text_field( mmerealestate_rs_limpiar( 'nombre', 'POST' ) );
	$url      = sanitize_text_field( mmerealestate_rs_limpiar( 'url', 'POST' ) );
	$email    = sanitize_text_field( mmerealestate_rs_limpiar( 'email', 'POST' ) );
	$telefono = sanitize_text_field( mmerealestate_rs_limpiar( 'telefono', 'POST' ) );
	$titulo   = sanitize_text_field( mmerealestate_rs_limpiar( 'titulo', 'POST' ) );
	$mensaje  = sanitize_text_field( mmerealestate_rs_limpiar( 'mensaje', 'POST' ) );
	$body     = '';
	
	if ( ! empty( $url ) ) {
		$body .= 'URL: <br>' . $url . '<br>';
	}
	if ( ! empty( $nombre ) ) {
		$body .= __( 'Name', 'mme-real-estate' ) . ': ' . $nombre . '<br>';
	}
	if ( ! empty( $email ) ) {
		$body .= __( 'Email', 'mme-real-estate' ) . ': ' . $email . '<br>';
	}
	if ( ! empty( $telefono ) ) {
		$body .= __( 'Phone', 'mme-real-estate' ) . ': ' . $telefono . '<br>';
	}
	if ( ! empty( $mensaje ) ) {
		$body .= $mensaje . '<br>';
	}

	$email_informes = get_option( 'email_informes' );

	if ( empty( $email_informes ) ) {
		$email_informes = get_option( 'admin_email' );
	}

	$to          = $email_informes;
	$subject     = __( 'Property information request', 'mme-real-estate' ) . ' ' . $titulo;
	$headers     = array( 'Content-Type: text/html; charset=UTF-8' );
	$resm        = wp_mail( $to, $subject, $body, $headers );
	$res['html'] = '<div class="mensaje-correo err-correo">' . __( 'Email was not sent, please try again.', 'mme-real-estate' ) . '</div>';

	if ( $resm ) {
		$res['html'] = '<div class="mensaje-correo">' . __( 'The mail was sent successfully.', 'mme-real-estate' ) . '</div>';
	}

	return wp_json_encode( $res );
}

/**
 * AJAX functions for property filter.
 *
 * @return  json          html in array in json format
 */
function mmerealestate_get_datare() {
	$id_edo = mmerealestate_rs_limpiar( 'idEdo', 'GET' );
	$id_mun = mmerealestate_rs_limpiar( 'idMun', 'GET' );
	$id_col = mmerealestate_rs_limpiar( 'idCol', 'GET' );
	$id_op  = mmerealestate_rs_limpiar( 'idOp', 'GET' );
	$act    = mmerealestate_rs_limpiar( 'act', 'GET' );

	switch ( $act ) {
		case 'municipio':
			$estados   = get_terms(
				array(
					'taxonomy' => 'mmelocalities',
					'parent'   => $id_edo,
				)
			);
			$arr_mun[] = '<option value="" selected="selected">' . __( 'All', 'mme-real-estate' ) . '</option>';
			foreach ( $estados as $m ) {
				$arr_mun[] = '<option value="' . $m->slug . '">' . $m->name . '</option>';
			}

			$res['ide']        = $id_edo;
			$res['dat']        = $estados;
			$res['res']        = 'ok';
			$res['municipios'] = $arr_mun;
			$respuesta         = wp_json_encode( $res );
			break;

		case 'colonia':
			$args = array(
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
			$colonias_arr[] = 'all';
			$arr_col[]      = '<option value="" selected="selected">' . __( 'All', 'mme-real-estate' ) . '</option> ';

			if ( $category_posts->have_posts() ) :
				while ( $category_posts->have_posts() ) :

					$category_posts->the_post();
					$id_prop           = $category_posts->posts[ $post_curr ]->ID;
					$propiedad_colonia = get_post_meta( $id_prop, 'propiedad_colonia', true );

					if ( ! empty( $propiedad_colonia ) ) {
						$col_arr = strtolower( trim( $propiedad_colonia ) );
						if ( ! in_array( $col_arr, $colonias_arr, true ) ) {
							$colonias_arr[] = $col_arr;
							$arr_col[]      = '<option value="' . $propiedad_colonia . '">' . $propiedad_colonia . '</option>';
						}
					}

					$post_curr++;
				endwhile;
			endif;

			$res['res']      = 'ok';
			$res['colonias'] = $arr_col;
			$respuesta       = wp_json_encode( $res );
			break;

		case 'tipo-operacion':
			$respuesta = wp_json_encode( mmerealestate_selects_adicionales( 'tipo_operacion', $id_edo, $id_mun, $id_col ) );
			break;

		case 'tipo-propiedad':
			$respuesta = wp_json_encode( mmerealestate_selects_adicionales( 'tipo_propiedad', $id_edo, $id_mun, $id_col, $id_op ) );
			break;
	}
	return $respuesta;
}

/**
 * Returns an html option tag
 *
 * @param   string  $val1   Option value.
 * @param   string  $val2   Default value.
 * @param   string  $label  Option label.
 *
 * @return  string          The option tag with its value, its text and its selected state
 */
function mmerealestate_option_sa( $val1, $val2, $label ) {
	$selected = '';
	if ( $val1 === $val2 ) {
		$selected = 'selected';
	}
	return '<option value="' . $val1 . '" ' . $selected . '>' . $label . '</option>';
}

/**
 * Returns the type of operation and type of property available in the database.
 *
 * @return  array          Array with property and operation type data
 */
function mmerealestate_selects_operacion_tipo() {

	$args0 = array( // Filter to take only posts of type "Property"
		'post_type'   => 'mmeproperties',
		'post_status' => 'publish',
	);

	$category_posts0   = new WP_Query( $args0 );
	$op_disponibles1[] = 0;
	$op_disponibles2[] = 0;
	$arr_to[]          = ',' . __( 'All', 'mme-real-estate' );
	$arr_tp[]          = ',' . __( 'All', 'mme-real-estate' );
	$post_curr         = 0;
	$ct_opdi           = 0;
	$ct_tp             = 0;

	if ( $category_posts0->have_posts() ) {
		while ( $category_posts0->have_posts() ) {

			$category_posts0->the_post();
			$id_prop = $category_posts0->posts[ $post_curr ]->ID;

			$propiedad_operacion = get_post_meta( $id_prop, 'propiedad_operacion', true );
			if ( ! in_array( $propiedad_operacion, $op_disponibles1, true ) ) {
				$op_disponibles1[] = $propiedad_operacion;
				switch ( $propiedad_operacion ) {
					case 1:
						$arr_to[] = '1,' . __( 'Sale', 'mme-real-estate' );
						break;
					case 2:
						$arr_to[] = '2,' . __( 'Rent', 'mme-real-estate' );
						break;
				}
				$ct_opdi++;
			}

			$tipo_propiedad = get_post_meta( $id_prop, 'propiedad_tipopropiedad', true );
			if ( ! in_array( $tipo_propiedad, $op_disponibles2, true ) ) {
				$op_disponibles2[] = $tipo_propiedad;
				switch ( $tipo_propiedad ) {
					case 1:
						$arr_tp[] = '1,' . __( 'Warehouse', 'mme-real-estate' );
						break;
					case 2:
						$arr_tp[] = '2,' . __( 'House room', 'mme-real-estate' );
						break;
					case 3:
						$arr_tp[] = '3,' . __( 'Apartment', 'mme-real-estate' );
						break;
					case 4:
						$arr_tp[] = '4,' . __( 'Business premises', 'mme-real-estate' );
						break;
					case 5:
						$arr_tp[] = '5,' . __( 'Office', 'mme-real-estate' );
						break;
					case 6:
						$arr_tp[] = '6,' . __( 'Land', 'mme-real-estate' );
						break;
					case 7:
						$arr_tp[] = '7,' . __( 'Other', 'mme-real-estate' );
						break;
				}
				$ct_tp++;
			}

			$post_curr++;

			if ( 8 === $ct_tp ) {
				break;
			}
		}
	}

	$res['arrTO'] = $arr_to;
	$res['arrTP'] = $arr_tp;

	return $res;
}

/**
 * Returns the type of operation and type of property available in the database. It is a dependent select of other variables in the property list filter.
 *
 * @param string $case Type of data to return (type of operation or type of property).
 * @param int    $id_edo Locality ID.
 * @param int    $id_mun Second locality ID.
 * @param int    $id_col Neighborhood ID.
 * @param int    $id_op Operation ID.
 * @param int    $val Default Value.
 *
 * @return  array          Property data
 */
function mmerealestate_selects_adicionales( $case, $id_edo, $id_mun, $id_col, $id_op = 0, $val = 0 ) {
	$tax_query0[] = array(
		'taxonomy' => 'mmelocalities',
		'field'    => 'slug',
		'terms'    => $id_edo,
	);

	$tax_query0[] = array(
		'taxonomy' => 'mmelocalities',
		'field'    => 'slug',
		'terms'    => $id_mun,
	);

	if ( ! empty( $id_col ) ) {
		$meta_query0[] = array(
			'key'   => 'propiedad_colonia',
			'value' => $id_col,
		);
	}

	if ( ! empty( $id_op ) ) {
		$meta_query0[] = array(
			'key'   => 'propiedad_operacion',
			'value' => $id_op,
		);
	}

	$args0 = array( // filtro para tomar solo posts de propiedades.
		'post_type'   => 'mmeproperties',
		'post_status' => 'publish',
	);

	if ( ! empty( $tax_query0 ) ) {
		$args0['tax_query'] = $tax_query0;
	}
	if ( ! empty( $meta_query0 ) ) {
		$args0['meta_query'] = $meta_query0;
	}

	$category_posts0 = new WP_Query( $args0 );

	$op_disponibles[] = 0;
	$arr_tp[]         = '<option value="">' . __( 'All', 'mme-real-estate' ) . '</option>';
	$post_curr        = 0;
	$ct_opdi          = 0;
	$ct_tp            = 0;

	if ( $category_posts0->have_posts() ) {
		while ( $category_posts0->have_posts() ) {

			$category_posts0->the_post();
			$id_prop = $category_posts0->posts[ $post_curr ]->ID;

			switch ( $case ) {
				case 'tipo_operacion':
					$propiedad_operacion = get_post_meta( $id_prop, 'propiedad_operacion', true );
					if ( ! in_array( $propiedad_operacion, $op_disponibles, true ) ) {
						$op_disponibles[] = $propiedad_operacion;
						switch ( $propiedad_operacion ) {
							case 1:
								$arr_tp[] = mmerealestate_option_sa( 1, $val, __( 'Sale', 'mme-real-estate' ) );
								break;
							case 2:
								$arr_tp[] = mmerealestate_option_sa( 2, $val, __( 'Rent', 'mme-real-estate' ) );
								break;
						}
						$ct_opdi++;
					}
					break;
				case 'tipo_propiedad':
					$tipo_propiedad = get_post_meta( $id_prop, 'propiedad_tipopropiedad', true );
					if ( ! in_array( $tipo_propiedad, $op_disponibles, true ) ) {
						$op_disponibles[] = $tipo_propiedad;
						switch ( $tipo_propiedad ) {
							case 1:
								$arr_tp[] = mmerealestate_option_sa( 1, $val, __( 'Warehouse', 'mme-real-estate' ) );
								break;
							case 2:
								$arr_tp[] = mmerealestate_option_sa( 2, $val, __( 'House room', 'mme-real-estate' ) );
								break;
							case 3:
								$arr_tp[] = mmerealestate_option_sa( 3, $val, __( 'Apartment', 'mme-real-estate' ) );
								break;
							case 4:
								$arr_tp[] = mmerealestate_option_sa( 4, $val, __( 'Business premises', 'mme-real-estate' ) );
								break;
							case 5:
								$arr_tp[] = mmerealestate_option_sa( 5, $val, __( 'Office', 'mme-real-estate' ) );
								break;
							case 6:
								$arr_tp[] = mmerealestate_option_sa( 6, $val, __( 'Land', 'mme-real-estate' ) );
								break;
							case 6:
								$arr_tp[] = mmerealestate_option_sa( 7, $val, __( 'Other', 'mme-real-estate' ) );
								break;
						}
						$ct_tp++;
					}
					break;
			}

			$post_curr++;
			if ( 3 === $ct_opdi ) {
				break;
			}
			if ( 8 === $ct_tp ) {
				break;
			}
		}
	}

	$res['res']           = 'ok';
	$res['tipooperacion'] = $arr_tp;
	return $res;
}

/**
 * Function to know which view to load depending on the url of the site in front.
 *
 * @param string $template Current WordPress Theme.
  *
 * @return  string          Property list page
 */
function mmerealestate_portfolio_page_template( $template ) {
	$post       = get_post();
	$url        = str_replace( 'https://', '', get_permalink( get_the_ID() ) );
	$url        = str_replace( 'http://', '', $url );
	$x          = explode( '/', $url );
	$typepostur = '/mmeproperties/';
	$dir        = plugin_dir_path( __FILE__ );
	$ruta_vista = 'public/views/';
	$pagina     = $ruta_vista . 'view-propiedades-list.php';

	if ( count( $x ) > 3 ) {
		$pagina = $ruta_vista . 'view-propiedad.php';
	}

	if ( isset( $x[2] ) ) {
		if ( 'page' === $x[2] ) {
			$pagina = $ruta_vista . 'view-propiedades-list.php';
		}
	}

	$pagina_sn = $ruta_vista . 'view-propiedad.php';
	if ( isset( $x[1] ) ) {
		if ( 'mmelocalities' === $x[1] ) {
			$pagina_sn = $ruta_vista . 'view-estado.php';
		}
	}

	$pagina_propiedades = get_option( 'pagina-propiedades' );
	$post               = get_post();

	if ( ! empty( $post->post_name ) && ! empty( $pagina_propiedades ) && $post->post_name === $pagina_propiedades ) {
		$new_template = $dir . $pagina;
		if ( '' !== $new_template ) {
			return $new_template;
		}
	} elseif ( isset( $post->post_type ) && 'mmeproperties' === $post->post_type ) {
		if ( '/' . $post->post_name . '/' !== $typepostur ) {
			return $dir . $pagina_sn;
		} else {
			header( 'Location: ' . home_url() . '/' . get_option( 'pagina-propiedades' ) );
			die();
		}
	} else {
		return $template;
	}
}

/**
 * Endpoint to create url to use functions via Ajax with GET data.
 */
function mmerealestate_events_endpoint() {
	register_rest_route(
		'remmdata',
		'ajax',
		array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => 'mmerealestate_get_datare',
			'permission_callback' => '__return_true',
		)
	);
}

/**
 * Endpoint to create url to use functions via Ajax with POST data.
 */
function mmerealestate_events_endpoint_post() {
	register_rest_route(
		'remmdatapost',
		'post',
		array(
			'methods'             => 'POST',
			'callback'            => 'mmerealestate_post_form',
			'permission_callback' => '__return_true',
		)
	);
}

/**
 * Include a JS and CSS for the page where the property list lands.
 */
function mmerealestate_my_load_scripts() {
	$post      = get_post();
	$post_type = '';
	$pag_prop  = get_option( 'pagina-propiedades' );

	if ( ! empty( $post->post_type ) ) {
		$post_type = $post->post_type;
	}

	wp_enqueue_script( 'my_js', MME_URL_PLUG_AD . 'public/js.js?ver=1.01', array( 'jquery' ), '1.0', true );
	wp_register_style( 'stylefront', MME_URL_PLUG_AD . 'public/mme-realestate-style.css', array(), '1.0' );
	wp_enqueue_style( 'stylefront' );
	wp_localize_script(
		'my_js',
		'ajax_var',
		array(
			'url'   => rest_url( '/remmdata/ajax' ),
			'nonce' => wp_create_nonce( 'wp_rest' ),
		)
	);
}

/**
 * Activate ( checked ) the checkbox.
 *
 * @param string $variable Current state.
 *
 * @return  string          Checked html
 */
function mmerealestate_checado_on( $variable ) {
	if ( 'on' === $variable ) {
		return 'checked="checked"';
	}
}

/**
 * Initializes the 'state' taxonomy.
 */
function mmerealestate_estado_taxonomy() {
	$labels = array(
		'name'                       => __( 'Localities', 'mme-real-estate' ),
		'singular_name'              => _x( 'Locality', 'Taxonomy Singular Name', 'mme-real-estate' ),
		'menu_name'                  => __( 'Localities', 'mme-real-estate' ),
		'all_items'                  => __( 'All localities', 'mme-real-estate' ),
		'parent_item'                => __( 'Parent locality', 'mme-real-estate' ),
		'parent_item_colon'          => __( 'Locality:', 'mme-real-estate' ),
		'new_item_name'              => __( 'New locality', 'mme-real-estate' ),
		'add_new_item'               => __( 'Add new locality', 'mme-real-estate' ),
		'edit_item'                  => __( 'Edit locality', 'mme-real-estate' ),
		'update_item'                => __( 'Update locality', 'mme-real-estate' ),
		'view_item'                  => __( 'View locality', 'mme-real-estate' ),
		'separate_items_with_commas' => __( 'Separate localities whit commas', 'mme-real-estate' ),
		'add_or_remove_items'        => __( 'Add or remove localities', 'mme-real-estate' ),
		'choose_from_most_used'      => __( 'Choose from most used localities', 'mme-real-estate' ),
		'popular_items'              => __( 'Popular localities', 'mme-real-estate' ),
		'search_items'               => __( 'Search localities', 'mme-real-estate' ),
		'not_found'                  => __( 'Localities not found', 'mme-real-estate' ),
		'no_terms'                   => __( 'There are no localities', 'mme-real-estate' ),
		'items_list'                 => __( 'Localities list', 'mme-real-estate' ),
		'items_list_navigation'      => __( 'Localities list navigation', 'mme-real-estate' ),

	);

	$rewrite = array(
		'slug'         => 'mmelocalities',
		'with_front'   => true,
		'hierarchical' => false,
	);

	$args = array(
		'labels'            => $labels,
		'hierarchical'      => true,
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => true,
		'rewrite'           => $rewrite,
		'show_in_rest'      => true,
	);
	register_taxonomy( 'mmelocalities', array( 'mmeproperties' ), $args );
}

/**
 * Build the structure of the permalink.
 *
 * @return  string          Permalink type configured in WP
 */
function mmerealestate_get_permalink_structure() {
	$permalink_structure                 = get_option( 'permalink_structure' );
	$options_permalink['plain']          = '';
	$options_permalink['day_and_name']   = '/%year%/%monthnum%/%day%/%postname%/';
	$options_permalink['month_and_name'] = '/%year%/%monthnum%/%postname%/';
	$options_permalink['numeric']        = '/archives/%post_id%';
	$options_permalink['post_name']      = '/%postname%/';
	$type_permalink                      = array_search( $permalink_structure, $options_permalink, true );
	return $type_permalink;
}

/**
 * HTML selector for filters by locality.
 */
function mmerealestate_menu_localidades_admin() {
	global $typenow;
	if ( $typenow == 'mmeproperties' ) {
		$taxonomy_names = array( 'mmelocalities' );
		foreach ( $taxonomy_names as $single_taxonomy ) {
			$single_taxonomy_lmp = mmerealestate_rs_limpiar( $single_taxonomy, 'GET' );
			$current_taxonomy    = isset( $single_taxonomy_lmp ) ? $single_taxonomy_lmp : '';
			$taxonomy_object     = get_taxonomy( $single_taxonomy );
			$taxonomy_name       = strtolower( $taxonomy_object->labels->name );
			$taxonomy_terms      = get_terms( $single_taxonomy );
			if ( count( $taxonomy_terms ) > 0) {
				$arr_padres = array();
				$arr_hijos  = array();
				foreach ( $taxonomy_terms as $single_term ) {
					if ( $single_term->parent==0 ) {
						$arr_padres[ $single_term->term_id ] = [
							'id'    => $single_term->term_id,
							'slug'  => $single_term->slug,
							'name'  => $single_term->name,
							'count' => $single_term->count,
						];
					}else {
						$arr_hijos[ $single_term->parent ][] = [
							'slug'  => $single_term->slug,
							'name'  => $single_term->name,
							'count' => $single_term->count,
						];
					}
				}
				echo "<select name='$single_taxonomy' id='$single_taxonomy' class='postform'>";
				echo "<option value=''>" . __( 'All localities', 'mme-real-estate' ) . "</option>";
				foreach( $arr_padres AS $p ) {
					echo '<option value='. $p['slug'], $current_taxonomy == $p['slug'] ? ' selected="selected"' : '','>' . $p['name'] .' (' . $p[ 'count' ] . ')</option>';
					if( !empty( $arr_hijos[ $p['id'] ] ) ){
						foreach( $arr_hijos[ $p['id'] ] AS $h ) {
							echo '<option value='. $h['slug'], $current_taxonomy == $h['slug'] ? ' selected="selected"' : '','>--' . $h['name'] .' (' . $h[ 'count' ] . ')</option>';
						}
					}
				}
				echo "</select>";
			}
		}
	}
}

/**
 * Returns a numeric amount in currency format.
 *
 * @param int    $precio   Property price.
 * @param string $simbolo  Currency symbol.
 *
 * @return  string          Chosen currency symbol
 */
function mmerealestate_formato_moneda( $precio, $simbolo = '' ) {
	$response = '';
	$simbolo  = get_option( 'simbolo_moneda' , '$' );
	$position = get_option( 'symbol_location' , 'left' );

	if ( empty( $simbolo ) ) {
		$simbolo = '$';
	}

	if ( ! empty( $precio ) ) {
		if ( is_numeric( $precio ) ) {
			$precio       = number_format( $precio, 2 );
			$precio_array = explode( '.', $precio );
			$precio_num   = $precio_array[0];
		} else {
			$precio_num   = $precio;
		}
		switch( $position ) {
			case 'right':
				$response = $precio_num . ' ' . $simbolo;
				break;
			case 'left': default:
				$response = $simbolo . ' ' . $precio_num;
				break;
		}
	}
	return $response;
}

/**
 * Returns the property filters in html format
 *
 *
 * @return  string          Property filter table html
*/
function mme_realestate_filter() {
	$html                = '';
	$label_re_estado     = get_option( 'label_re_estado' );
	$label_re_ciudad     = get_option( 'label_re_ciudad' );
	$label_re_colonia    = get_option( 'label_re_colonia' );
	$label_re_toperacion = get_option( 'label_re_toperacion' );
	$label_re_tpropiedad = get_option( 'label_re_tpropiedad' );

	if ( empty( $label_re_estado ) ) {
		$label_re_estado = __( 'State', 'mme-real-estate' );
	}
	if ( empty( $label_re_ciudad ) ) {
		$label_re_ciudad = __( 'City or County', 'mme-real-estate' );
	}
	if ( empty( $label_re_colonia ) ) {
		$label_re_colonia = __( 'Neighborhood', 'mme-real-estate' );
	}
	if ( empty( $label_re_toperacion ) ) {
		$label_re_toperacion = __( 'Operation type', 'mme-real-estate' );
	}
	if ( empty( $label_re_tpropiedad ) ) {
		$label_re_tpropiedad = __( 'Property type', 'mme-real-estate' );
	}

	$type_permalink  = mmerealestate_get_permalink_structure();
	$url_form_filtro = get_site_url() . '/' . get_option( 'pagina-propiedades' );

	if ( 'plain' === $type_permalink ) {
		$url_form_filtro = get_site_url() . "/?page_id=$page_id";
	}
	
	$html .= '<script type="text/javascript">';
	$html .= 'var type_permalink = "' . mmerealestate_get_permalink_structure() . '"';
	$html .= '</script>';
	$html .= '<div class="mme-content-propiedades">';
	$html .= '<div id="mme-filtros">';
	$html .= '<form action="' . $url_form_filtro . '" id="mme-filtros-div">';
	if ( 'plain' === $type_permalink ) {
		$page_id = mmerealestate_rs_limpiar( 'page_id', 'GET' );
		$html   .= '<input type="hidden" name="page_id" id="page_id" value="' . $page_id . '"/>';
	}

	// Taxonomia estados.
	$estados = get_terms(
		array(
			'taxonomy' => 'mmelocalities',
			'parent'   => 0,
		)
	);

	$html .= '<label>';
	$html .= $label_re_estado;
	$html .= '</label>';
	$html .= '<select name="estado" id="mme-state" style="width:100%">';
	$html .= '<option value="" selected="selected">';
	$html .= __( 'All', 'mme-real-estate' );
	$html .= '</option>';
	
	$arr_edos = mmerealestate_return_estados();
	if ( ! empty( $estados ) ) {
		foreach ( $arr_edos AS $dt ) {
			$arr_data[] = array(
				' name '  => 'idedo',
				' value ' => $dt['id'],
			);
			$html .= mmerealestate_option_select( $dt['slug'], $dt['name'], '', $arr_data ); 
			unset( $arr_data );
		}
	}
	$html .= '</select>';

	$html .= '<label>';
	$html .= $label_re_ciudad;
	$html .= '</label>';
	$html .= '<select name="municipio" id="mme-city" style="width:100%">';
	$html .= '<option value="" selected="selected">';
	$html .= __( 'All', 'mme-real-estate' );
	$html .= '</option>';
	$html .= '</select>';

	$html .= '<label>';
	$html .= $label_re_colonia;
	$html .= '</label>';
	$html .= '<select name="colonia" id="mme-neighborhood" style="width:100%">';
	$html .= '<option value="" selected="selected">';
	$html .= __( 'All', 'mme-real-estate' );
	$html .= '</option>';
	$html .= '</select>';

	$html .= '<label>';
	$html .= $label_re_toperacion;
	$html .= '</label>';
	$html .= '<select name="operacion" id="mme-operation-type" style="width:100%">';
	$html .= '</select>';

	$html .= '<label>';
	$html .= $label_re_tpropiedad;
	$html .= '</label>';
	$html .= '<select name="tipo-propiedad" id="mme-property-type" style="width:100%">';
	$html .= '</select>';

	$html .= '<label>';
	$html .= __( 'Price range', 'mme-real-estate' );
	$html .= '</label>';

	$html .= '<input type="text" name="desde" autocomplete="off" class="mme-moneda moneda" placeholder="' . __( 'from', 'mme-real-estate' ) . '" value="">';
	$html .= '<input type="text" name="hasta" autocomplete="off" class="mme-moneda moneda" placeholder="' . __( 'to', 'mme-real-estate' ) . '" value="">';

	$html .= '<button>';
	$html .= __( 'Search', 'mme-real-estate' );
	$html .= '</button>';

	$html .= '</form>';
	$html .= '</div>';
	$html .= '</div>';

	return $html;
}

/**
 * Returns the list of properties in html format
 *
 * @param array $atts configuration parameter array.
 *
 * @return  string          Properties in html format
 */
function mme_realestate_list( $atts ) {
	$type_permalink      = mmerealestate_get_permalink_structure();
	$simbolo_moneda      = '$';
	$properties_per_page = 8;
	$html                = '';

	extract ( shortcode_atts( array(
		'count' => FALSE,
	), $atts ) );
	if ( $count ) {
		$properties_per_page = $count;
	}

	// filtro para tomar solo posts de propiedades.
	$args = array(
		'post_type'      => 'mmeproperties',
		'post_status'    => 'publish',
		'posts_per_page' => $properties_per_page,
	);

	$category_posts = new WP_Query( $args );
	$post_curr      = 0;
	$html .= '<script type="text/javascript">';
	$html .= 'var type_permalink = "'. mmerealestate_get_permalink_structure() . '"';
	$html .= '</script>';

	$html .= '<div id="mme-content-propiedades">';
	$html .= '<div id="mme-content-list-propiedades">';

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
				$tmb_img = '<img width="250px" src="' . MME_URL_PLUG_AD . 'public/image-not-available.png">';
			}

			// Estado y municipio =================================================.
			$tax_estados = get_the_terms( $id_prop, 'mmelocalities' );
			if ( ! empty( $tax_estados ) ) {
				foreach ( $tax_estados as $edos ) {
					if ( 0 === $edos->parent ) {
						$estado = $edos->name;
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

			$html .= '<div class="grid-33">';
			$html .= '<div class="ficha-propiedad">';
			$html .= '<a href="' . $url_post . '">' . $tmb_img . '</a>';
			$html .= '<p class="ver-propiedad">';
			$html .= '<button class="button-propiedad" href="' . $url_post . '">';
			$html .= $category_posts->posts[ $post_curr ]->post_title;
			$html .= '</button>';
			$html .= '</p>';

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

			$html .= '<span class="' . $class_op . '">' . $operacion_label . '</span>';

			if ( ! empty( $propiedad_precio ) ) {
				$precio = '<br>' . mmerealestate_formato_moneda( $propiedad_precio, $simbolo_moneda );
				$html .= $precio;
			}

			if ( isset( $estado ) ) {
				$html .= '<p>';
				$html .= $estado;
				if ( isset( $municipio ) ) {
					$html .= '-' . $municipio;
				}
				$html .= '</p>';
			}

			$html .= '</div>';
			$html .= '</div>';
			$post_curr++;
		endwhile;
	endif;
	$html .= '</div>';
	$html .= '</div>';
	return $html;
}


/**
 * Returns an html option with additional string data.
 *
 * @param string $val      Option value.
 * @param string $label    Option label.
 * @param string $selected Default value.
 * @param string $data     Additional data to include in the option.
 *
 * @return  string         Html option
 */
function mmerealestate_option_select( $val, $label, $selected, $data = '' ) {
	$output       = false;
	$showselected = false;
	$datas        = '';
	if ( $selected === $val ) {
		$showselected = ' selected';
	}
	if ( ! empty( $data ) ) {
		foreach ( $data as $d ) {
			$datas .= $d[' name '] . '="' . $d[' value '] . '" ';
		}
	} else {
		$datas = '';
	}
	$output = "<option value='$val' $datas $showselected>$label</option>";
	return $output;
}

/**
 * Return list mmelocalities.
 *
 * @return  array          List mmelocalities
 */
function mmerealestate_return_estados() {
	// filtro para tomar solo posts de propiedades.
	$args = array(
		'post_type'      => 'mmeproperties',
		'post_status'    => 'publish',
	);
	$category_posts = new WP_Query( $args );
	$post_curr      = 0;

	// Divider ==============================================.
	if ( $category_posts->have_posts() ) :
		while ( $category_posts->have_posts() ) :
			$category_posts->the_post();
			$id_prop = $category_posts->posts[ $post_curr ]->ID;
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
			$post_curr++;
		endwhile;
	endif;
	return $arr_edos;
}
