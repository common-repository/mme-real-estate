<?php
/**
 * Plugin administration panel
 *
 * @package  MME Real Estate
 * @author   Multimedia Efectiva <info@multimediaefectiva.com>
 */
 
if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( 'Insufficient permissions.' );
}

$chk           = false;
$actualiza_mme = mmerealestate_rs_limpiar( 'actualiza_mme', 'POST' );
$reset         = mmerealestate_rs_limpiar( 'reset', 'POST' );

if ( empty( get_option( 'symbol_location' ) ) ){
	update_option( 'symbol_location', 'left' );
}
if ( empty( get_option( 'mostrar_poweredby' ) ) ){  
	update_option( 'mostrar_poweredby', 'on' ); 
}

if ( ! empty( $actualiza_mme ) ) {
	if ( 'reset' !== $reset ) {
		$chk = mmerealestate_actualizar_opcion( 'pagina-propiedades', $chk );
		$chk = mmerealestate_actualizar_opcion( 'margintoppage', $chk );
		$chk = mmerealestate_actualizar_opcion( 'mostrar_form', $chk );
		$chk = mmerealestate_actualizar_opcion( 'email_informes', $chk );
		$chk = mmerealestate_actualizar_opcion( 'properties_per_page', $chk );
		$chk = mmerealestate_actualizar_opcion( 'mostrar_titulo', $chk );
		$chk = mmerealestate_actualizar_opcion( 'max_width', $chk );
		$chk = mmerealestate_actualizar_opcion( 'simbolo_moneda', $chk );
		$chk = mmerealestate_actualizar_opcion( 'symbol_location', $chk );
		$chk = mmerealestate_actualizar_opcion( 'mostrar_poweredby', $chk );
		
		$chk = mmerealestate_actualizar_opcion( 'label_re_estado', $chk );
		$chk = mmerealestate_actualizar_opcion( 'label_re_ciudad', $chk );
		$chk = mmerealestate_actualizar_opcion( 'label_re_colonia', $chk );
		$chk = mmerealestate_actualizar_opcion( 'label_re_toperacion', $chk );
		$chk = mmerealestate_actualizar_opcion( 'label_re_tpropiedad', $chk );

	} else {
		update_option( 'pagina-propiedades', '' );
		update_option( 'margintoppage', '' );
		update_option( 'mostrar_form', 'on' );
		update_option( 'email_informes', '' );
		update_option( 'properties_per_page', '' );
		update_option( 'mostrar_titulo', 'on' );
		update_option( 'max_width', '' );
		update_option( 'simbolo_moneda', '' );
		update_option( 'symbol_location', '' );
		update_option( 'mostrar_poweredby', 'on' );
		update_option( 'symbol_location', '' );
		update_option( 'label_re_estado', '' );
		update_option( 'label_re_ciudad', '' );
		update_option( 'label_re_colonia', '' );
		update_option( 'label_re_toperacion', '' );
		update_option( 'label_re_tpropiedad', '' );
	}
}

$properties_per_page = get_option( 'properties_per_page' );
$email_informes      = get_option( 'email_informes' );
$max_width           = get_option( 'max_width' );
$simbolo_moneda      = get_option( 'simbolo_moneda' );
$symbol_location     = get_option( 'symbol_location' );
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
if ( empty( $email_informes ) ) {
	$email_informes = get_option( 'admin_email' );
}
if ( empty( $symbol_location ) ) {
	$symbol_location = 'left';
}
$symbol_at_left  = "";
$symbol_at_right = "";
switch ( $symbol_location ){
	case 'right':
		$symbol_at_right = " checked='checked'";
		break;
	case 'left': default:
		$symbol_at_left = " checked='checked'";
		break;
}

$args   = array(
	'post_type' => 'page',
	'order'     => 'ASC',
	'posts_per_page' => 100,
);
$pages1 = new WP_Query( $args );
?>

<div class="wrap" id="mme-dashboard-setup">
	<!-- TITLE AND ICON ROW -->
	<div class="grid-container">
		<div class="grid-100">
			<img src="<?php echo esc_url( plugin_dir_url( __DIR__ ) ); ?>images/mme-real-estate-plugin-ico.png" id="mme-realestate-logo">
			<h1 style="display: inline-block;vertical-align: top;margin-left: 1rem;">
				<?php esc_html_e( 'MME Real Estate', 'mme-real-estate' ); ?>
				<small style="color:#ababab;"><?php esc_html_e( 'Simple listing', 'mme-real-estate' ); ?></small>
			</h1>
		</div>
	</div>

	<!-- FASTLINKS ICONS ROW -->
	<div class="grid-container fastlinks">
		<div class="grid-20 tablet-grid-25 mobile-grid-50">
			<a href="<?php echo esc_url( get_admin_url() ); ?>edit.php?post_type=mmeproperties" class="button-primary main-btn">
				<div alt="f541" class="dashicons dashicons-admin-multisite"></div>
				<span><?php esc_html_e( 'Property list', 'mme-real-estate' ); ?></span>
			</a>
		</div>
		<div class="grid-20 tablet-grid-25 mobile-grid-50">
			<a href="<?php echo esc_url( get_admin_url() ); ?>post-new.php?post_type=mmeproperties" class="button-primary main-btn">
				<div alt="f102" class="dashicons dashicons-admin-home"></div>
				<span><?php esc_html_e( 'New property', 'mme-real-estate' ); ?></span>
			</a>
		</div>
		<div class="grid-20 tablet-grid-25 mobile-grid-50">
			<a href="<?php echo esc_url( get_admin_url() ); ?>edit-tags.php?taxonomy=mmelocalities&post_type=mmeproperties" class="button-primary main-btn">
				<div alt="f230" class="dashicons dashicons-location"></div>
				<span><?php esc_html_e( 'Localities', 'mme-real-estate' ); ?></span>
			</a>
		</div>
		<div class="grid-20 tablet-grid-25 mobile-grid-50">
			<a href="<?php echo esc_url( get_admin_url() ); ?>edit.php?post_type=mmeproperties&page=mme-real-estate/admin/views/shortcodes.php" class="button-primary main-btn">
				<div alt="f230" class="dashicons dashicons-shortcode"></div>
				<span>Shortcodes</span>
			</a>
		</div>
		<div class="grid-20 tablet-grid-25 mobile-grid-50">
			<a href="<?php echo esc_url( get_admin_url() ); ?>edit.php?post_type=mmeproperties&page=mme-real-estate/admin/views/how-to-start.php" class="button-primary main-btn">
				<div alt="f522" class="dashicons dashicons-controls-play"></div>
				<span><?php esc_html_e( 'How to start', 'mme-real-estate' ); ?></span>
			</a>
		</div>
	</div>

	<form method="post" class="conf-gral" action="">

		<!-- NOTICE ROW -->
		<div class="grid-container">
			<div class="grid-100 configurar-home">
				<hr>
				<h2><?php esc_html_e( 'Set up', 'mme-real-estate' ); ?></h2>
				<?php
				if ( ! empty( $actualiza_mme ) ) {
					?>
					<div class="updated notice is-dismissible">
						<p><?php esc_html_e( 'Data updated correctly', 'mme-real-estate' ); ?></p>
					</div>
					<?php
				}
				?>
			</div>
		</div>

		<!-- SETUP FIRST ROW - select page, view, show title, show form, show poweredby-->
		<div class="grid-container">
			<div class="grid-25 tablet-grid-66 mobile-grid-66">
				<label for="organizacion">
					<?php esc_html_e( 'Properties display page', 'mme-real-estate' ); ?>:<br>
					<select name="pagina-propiedades" id="pagina-propiedades" style="width:100%">
						<option value="">
							--<?php esc_html_e( 'Select', 'mme-real-estate' ); ?>
						</option>
						<?php
						foreach ( $pages1->posts as $p ) {
							$pages_post_selected = false;
							if ( get_option( 'pagina-propiedades' ) === $p->post_name ) {
								$pages_post_selected = 'selected';
							}
							// armamos el value para ponerlo en una sola variable.
							$pages_post_value = 'value=' . $p->post_name . ' ' . $pages_post_selected;
							?>
								<option <?php echo esc_attr( $pages_post_value ); ?>>
									<?php echo esc_html( $p->post_title ); ?>
								</option>
								<?php
						}
						?>
					</select>
				</label>
			</div>
			<div class="grid-10 tablet-grid-33 mobile-grid-33" style="padding-top: 1rem;">
				<?php
				if ( ! empty( get_option( 'pagina-propiedades' ) ) ) {
					?>
					<a href="<?php echo esc_url( home_url() . '/' . get_option( 'pagina-propiedades' ) ); ?>" class="button button-primary" target="_blank">
						<?php esc_html_e( 'View page', 'mme-real-estate' ); ?>
					</a>
					<?php
				}
				?>
			</div>
			<div class="grid-25 tablet-grid-33 mobile-grid-100" style="padding-top: 1.3rem;">
				<label for="mostrar_titulo">
					<input name="mostrar_titulo" id="mostrar_titulo" type="checkbox" value="on" <?php echo esc_attr( mmerealestate_checado_on( get_option( 'mostrar_titulo' ) ) ); ?>>
					<?php esc_html_e( 'Show title in content', 'mme-real-estate' ); ?>
				</label>
			</div>
			<div class="grid-20 tablet-grid-33 mobile-grid-100" style="padding-top: 1.3rem;">
				<label for="mostrar_form">
					<input name="mostrar_form" id="mostrar_form" type="checkbox" value="on" <?php echo esc_attr( mmerealestate_checado_on( get_option( 'mostrar_form' ) ) ); ?>>
					<?php esc_html_e( 'Show form', 'mme-real-estate' ); ?>
				</label>
			</div>
			<div class="grid-20 tablet-grid-33 mobile-grid-100" style="padding-top: 1.3rem;">
				<label for="mostrar_poweredby">
					<input name="mostrar_poweredby" id="mostrar_poweredby" type="checkbox" value="on" <?php echo esc_attr( mmerealestate_checado_on( get_option( 'mostrar_poweredby' ) ) ); ?>>
					<?php esc_html_e( "Show 'Powered by'", 'mme-real-estate' ); ?>
				</label>
			</div>
		</div>
		<hr>

		<!-- SETUP SECOND ROW - set top-margin, max-width, properties per page, currency symbol, currency symbol position -->
		<div class="grid-container">
			<div class="grid-25 tablet-grid-33 mobile-grid-100">
				<label>
					<?php esc_html_e( 'Top margin of pages (px)', 'mme-real-estate' ); ?>:
					<input type="number" name="margintoppage" id=""
					value="<?php echo esc_attr( get_option( 'margintoppage' ) ); ?>"
					placeholder="20" />
				</label>
			</div>
			<div class="grid-15 tablet-grid-33 mobile-grid-100">
				<label>
					<?php esc_html_e( 'Max-width (px)', 'mme-real-estate' ); ?>:
					<input type="number" name="max_width" id="max_width" pattern="[0-9]+"
					value="<?php echo esc_attr( $max_width ); ?>" 
					placeholder="1200"
					/>
				</label>
			</div>
			<div class="grid-20 tablet-grid-33 mobile-grid-100">
				<label>
					<?php esc_html_e( 'Properties per page', 'mme-real-estate' ); ?>:
					<input type="number" name="properties_per_page" id="properties_per_page"
					value="<?php echo esc_attr( $properties_per_page ); ?>" min="3" max="99"
					placeholder="6" />
				</label>
			</div>
			<div class="grid-15 tablet-grid-50 mobile-grid-100">
				<label>
					<?php esc_html_e( 'Currency symbol', 'mme-real-estate' ); ?>:
					<input type='text' name='simbolo_moneda' autocomplete="off" maxlength="2"
					value='<?php echo esc_attr( $simbolo_moneda ); ?>' id='simbolo_moneda'
					placeholder="$" />
				</label>
			</div>
			<div class="grid-25 tablet-grid-50 mobile-grid-100">
				<label>
					<?php esc_html_e( 'Symbol to the left', 'mme-real-estate' ); ?>:
					<input type="radio" name="symbol_location" value="left"<?php echo $symbol_at_left; ?>>
				</label>
				<label>
					<?php esc_html_e( 'Symbol to the right', 'mme-real-estate' ); ?>:
					<input type="radio" name="symbol_location" value="right"<?php echo $symbol_at_right; ?>>
				</label>
			</div>
		</div>
		<hr>

		<!-- SETUP THIRD ROW - email  -->
		<div class="grid-container">
			<div class="grid-33 tablet-grid-50 mobile-grid-100">
				<label>
					<?php esc_html_e( 'Email for the information form', 'mme-real-estate' ); ?>:
					<input type="text" name="email_informes" id="email_informes"
					value="<?php echo esc_attr( $email_informes ); ?>"
					placeholder="<?php echo esc_attr( get_option( 'admin_email' ) ); ?>" />
					<input type="hidden" name="reset" id="reset" value="" />
				</label>
			</div>
		</div>
		<hr>

		<!-- SETUP FOURTH ROW - Filter labels  -->
		<div class="grid-container">
			<div class="mobile-grid-100">
				<h3><?php esc_html_e( 'Filter and breadcrumb labels', 'mme-real-estate' ); ?></h3>
			</div>
			<div class="grid-33 tablet-grid-50 mobile-grid-100">
				<label>
					<?php esc_html_e( 'State', 'mme-real-estate' ); ?>:
					<input type="text" name="label_re_estado" id="label_re_estado" value="<?php echo $label_re_estado ?>"/>
				</label>
			</div>
			<div class="grid-33 tablet-grid-50 mobile-grid-100">
				<label>
					<?php esc_html_e( 'City or County', 'mme-real-estate' ); ?>:
					<input type="text" name="label_re_ciudad" id="label_re_ciudad" value="<?php echo $label_re_ciudad ?>"/>
				</label>
			</div>
			<div class="grid-33 tablet-grid-50 mobile-grid-100">
				<label>
					<?php esc_html_e( 'Neighborhood', 'mme-real-estate' ); ?>:
					<input type="text" name="label_re_colonia" id="label_re_colonia" value="<?php echo $label_re_colonia ?>"/>
				</label>
			</div>
			<div class="grid-33 tablet-grid-50 mobile-grid-100">
				<label>
					<?php esc_html_e( 'Operation type', 'mme-real-estate' ); ?>:
					<input type="text" name="label_re_toperacion" id="label_re_toperacion" value="<?php echo $label_re_toperacion ?>"/>
				</label>
			</div>
			<div class="grid-33 tablet-grid-50 mobile-grid-100">
				<label>
					<?php esc_html_e( 'Property type', 'mme-real-estate' ); ?>:
					<input type="text" name="label_re_tpropiedad" id="label_re_tpropiedad" value="<?php echo $label_re_tpropiedad ?>"/>
				</label>
			</div>
		</div> 
		 
		<!-- SAVE AND CLEAN CONFIGURATION -->
		<div class="grid-container">
			<div class="grid-100">
				<br>
				<div style="margin-bottom: 3em;">
				  <input type="submit" name="actualiza_mme" id="submit" class="button button-primary" value="<?php esc_html_e( 'Save', 'mme-real-estate' ); ?>">
				</div>
				<span class="button button-secondary" style="float: right;" onclick="function_reset()" id="reset-form"><?php esc_html_e( 'Reset settings', 'mme-real-estate' ); ?></span>
			</div> 
		</div>

		<!-- POWERED BY ROW -->
		<div class="grid-container">
			<div class="grid-50 mobile-grid-100"></div>
			<div class="grid-50 potenciado">
				<?php
				if ( get_option( 'mostrar_poweredby' ) === 'on' ) { ?>
						<p id="powered-by-mme">
							<a href="https://multimediaefectiva.com/" target="_blank">
								<img src="<?php echo esc_url( plugin_dir_url( __DIR__ ) . 'images/logo-mme.png' ); ?>">
								<?php esc_html_e( 'Powered by', 'mme-real-estate' ); ?><br>
								Multimedia Efectiva
							</a>
						</p>
					<?php 
				} ?>
			</div>
		</div>
	</form>
</div>
