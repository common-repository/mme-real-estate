<?php
/**
 * Info about how to use the shortcodes
 *
 * @package  MME Real Estate
 * @author   Multimedia Efectiva <info@multimediaefectiva.com>
 */
 
if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( 'Insufficient permissions.' );
}
?>
<div class="wrap">
	<div class="grid-container">
		<div class="grid-100">
			<img src="<?php echo esc_url( plugin_dir_url( __DIR__ ) ); ?>images/mme-real-estate-plugin-ico.png" id="mme-realestate-logo">
			<h1 style="display: inline-block;vertical-align: top;margin-left: 1rem;">
				<?php esc_html_e( 'MME Real Estate', 'mme-real-estate' ); ?>
				<small style="color:#ababab;"><?php esc_html_e( 'Simple listing', 'mme-real-estate' ); ?></small>
			</h1>
			<h2>
				Shortcodes
			</h2>
			<h3>&lbrack;mme_realestate_list count=3&rbrack;</h3>
			<ul style="list-style: disc; padding-left:1rem">
				<li><?php esc_html_e( 'Create a block with a given number of properties.', 'mme-real-estate' ); ?></li>
				<li><?php esc_html_e( 'The count parameter determines how many properties are to be displayed.', 'mme-real-estate' ); ?></li>
				<li><?php esc_html_e( 'If count is not specified, 8 properties will be returned.', 'mme-real-estate' ); ?></li>
				<li><?php esc_html_e( 'By default, the most recent properties are shown.', 'mme-real-estate' ); ?></li>
			</ul>
			<hr>
			<h3>&lbrack;mme_realestate_filter&rbrack;</h3>
			<ul style="list-style: disc; padding-left:1rem">
				<li><?php esc_html_e( 'Create a block with the properties filter.', 'mme-real-estate' ); ?></li>
			</ul>
			<hr>
		</div>
	</div>
</div>
