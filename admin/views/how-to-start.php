<?php
/**
 * Info about how to start to use the plugin
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
				<?php esc_html_e( 'How to start', 'mme-real-estate' ); ?>
			</h2>
			<ol>
				<li>
					<?php esc_html_e( 'Create a page with the title you want, that page will show the list of properties.', 'mme-real-estate' ); ?>
				</li>
				<li>
					<?php /* translators: 1: open emphasis html tag 2: close emphasis html tag */?>
					<?php printf( esc_html__( 'In "%1$sMME Real Estate &gt; Set up &gt; Properties display page%2$s" select the page that you created in step 1.', 'mme-real-estate' ), '<em>', '</em>' ); ?>
				</li>
				<li>
					<?php /* translators: 1: open emphasis html tag 2: close emphasis html tag */?>
					<?php printf( esc_html__( 'In "%1$sMME Real Estate &gt; Localities%2$s" create the geographic localities that will be used to classify your properties.', 'mme-real-estate' ), '<em>', '</em>' ); ?>
					<?php esc_html_e( 'You can create all the localities you want.', 'mme-real-estate' ); ?>
					<?php esc_html_e( 'Keep in mind that the property filter has only two levels which correspond to State and City or County.', 'mme-real-estate' ); ?>
				</li>
				<li><?php /* translators: 1: open emphasis html tag 2: close emphasis html tag */?>
					<?php printf( esc_html__( 'In "%1$sMME Real Estate &gt; New Property%2$s", you\'ll create a new Property type post.', 'mme-real-estate' ), '<em>', '</em>' ); ?>
					<?php esc_html_e( 'Here you can add content to the WordPress editor area, you can also add content to the property sections (specifications, map, video).', 'mme-real-estate' ); ?>
				</li>
				<li>
					<?php esc_html_e( 'When creating your property, don\'t forget to categorize it within a locality and add a feauterd image, which will be displayed in the list of properties.', 'mme-real-estate' ); ?>
				</li>
				<li>
					<?php esc_html_e( 'When you\'re ready, publish your property, it will be listed on the page you created in step 1.', 'mme-real-estate' ); ?>
				</li>
				<li>
					<?php /* translators: 1: open emphasis html tag 2: close emphasis html tag */?>
					<?php printf( esc_html__( 'In "%1$sMME Real Estate &gt; Set up%2$s" the following parameters will help you to adjust how the list of properties and each property is displayed.', 'mme-real-estate' ), '<em>', '</em>' ); ?>
					<ul style="list-style: disc; padding-left:1rem">
						<li>
							<strong><?php esc_html_e( 'Show title in content', 'mme-real-estate' ); ?></strong> -
							<?php esc_html_e( 'Some themes have their own space to display titles.', 'mme-real-estate' ); ?>
							<?php esc_html_e( 'This option shows or hides the title of the listing and the properties.', 'mme-real-estate' ); ?>
							<?php esc_html_e( 'The default value is Enabled.', 'mme-real-estate' ); ?>
							<?php esc_html_e( 'It affects the listing and all properties.', 'mme-real-estate' ); ?>
						</li>
						<li>
							<strong><?php esc_html_e( 'Show form', 'mme-real-estate' ); ?></strong> -
							<?php esc_html_e( 'Activate or deactivate the Request more information section.', 'mme-real-estate' ); ?>
							<?php esc_html_e( 'The default value is Enabled.', 'mme-real-estate' ); ?>
							<?php esc_html_e( 'It affects all properties.', 'mme-real-estate' ); ?>
						</li>
						<li>
							<strong><?php esc_html_e( 'Show \'Powered by\'', 'mme-real-estate' ); ?></strong> -
							<?php esc_html_e( 'Show or hide the developer logo in footer setup.', 'mme-real-estate' ); ?>
							<?php esc_html_e( 'The developer logo only shows in the setup page, not in the frontend.', 'mme-real-estate' ); ?>
							<?php esc_html_e( 'The default value is Enabled.', 'mme-real-estate' ); ?>
						</li>
						<li>
							<strong><?php esc_html_e( 'Top margin of pages (px)', 'mme-real-estate' ); ?></strong> - 
							<?php esc_html_e( 'Some themes can leave a lot or little space between the content and the top or even hide it behind another section.', 'mme-real-estate' ); ?>
							<?php esc_html_e( 'With this configuration you can make the listing move closer to or further away from the header.', 'mme-real-estate' ); ?>
							<?php esc_html_e( 'Accepts negative values.', 'mme-real-estate' ); ?>
							<?php esc_html_e( 'The default value is \'20\'.', 'mme-real-estate' ); ?>
						</li>
						<li>
							<strong><?php esc_html_e( 'Max-width (px)', 'mme-real-estate' ); ?></strong> - 
							<?php esc_html_e( 'Some themes default to a page width that may be too large to display the property listing.', 'mme-real-estate' ); ?>
							<?php esc_html_e( 'By adjusting this parameter you can limit the maximum width in which the property list is displayed.', 'mme-real-estate' ); ?>
							<?php esc_html_e( 'The default value is \'1200\'.', 'mme-real-estate' ); ?>
						</li>
						<li>
							<strong><?php esc_html_e( 'Properties per page', 'mme-real-estate' ); ?></strong> -
							<?php esc_html_e( 'The properties you want to show on each page of the list.', 'mme-real-estate' ); ?>
							<?php esc_html_e( 'The default quantity is 6.', 'mme-real-estate' ); ?>
						</li>
						<li>
							<strong><?php esc_html_e( 'Currency symbol', 'mme-real-estate' ); ?></strong> -
							<?php esc_html_e( 'The currency symbol to display in the property price.', 'mme-real-estate' ); ?>
							<?php esc_html_e( 'The default is $.', 'mme-real-estate' ); ?>
						</li>
						<li>
							<strong><?php esc_html_e( 'Symbol to the left/right', 'mme-real-estate' ); ?></strong> -
							<?php esc_html_e( 'The position of the currency symbol relative to the price of the property.', 'mme-real-estate' ); ?>
							<?php esc_html_e( 'By default it is to the left.', 'mme-real-estate' ); ?>
						</li>
						<li>
							<strong><?php esc_html_e( 'Email for the information form', 'mme-real-estate' ); ?></strong> -
							<?php esc_html_e( 'The email account where the forms will be sent to expand reports of each property that you publish.', 'mme-real-estate' ); ?>
							<?php /* translators: 1: open emphasis html tag 2: close emphasis html tag */?>
							<?php printf( esc_html__( 'The default value is the \'Email Address\' field located in "%1$sWordPress &gt; Settings &gt; General.%2$s"', 'mme-real-estate' ), '<em>', '</em>' ); ?>
						</li>
						<li>
							<strong><?php esc_html_e( 'Filter and breadcrumb labels', 'mme-real-estate' ); ?></strong> -
							<?php esc_html_e( 'With these parameters you can customize the labels that will be displayed by the filter and the breadcrumbs.', 'mme-real-estate' ); ?>
						</li>
					</ul>
				</li>
				<li><?php /* translators: 1: open emphasis html tag 2: close emphasis html tag */?>
					<?php printf( esc_html__( 'In "%1$sMME Real Estate &gt; Shortcodes%2$s" the two available shortcodes are displayed, which you can insert anywhere on your website to display the latest properties and the property search filter.', 'mme-real-estate' ), '<em>', '</em>' ); ?>
				</li>
			</ol>
			<hr>
		</div>
	</div>
</div>
