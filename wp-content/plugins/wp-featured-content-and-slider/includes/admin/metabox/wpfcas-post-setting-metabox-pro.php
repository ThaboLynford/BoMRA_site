<?php
/**
 * Function Custom meta box for Premium
 * 
 * @package WP Featured Content and Slider
 * @since 1.4.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="pro-notice"><?php echo sprintf( __( 'Utilize these <a href="%s" target="_blank">Premium Features</a> with Essential Plugin to boost your <b>to get best of this plugin.</b>.'), WPFCAS_PLUGIN_LINK); ?></div>
<table class="form-table wpfcas-metabox-table">
	<tbody>

		<tr class="wpfcas-pro-feature">
			<th>
				<?php _e('Layouts ', 'wp-featured-content-and-slider'); ?><span class="wpfcas-pro-tag"><?php _e('PRO','wp-featured-content-and-slider');?></span>
			</th>
			<td>
				<span class="description"><?php _e('3 (Icon , Image , Icon and Image). In lite version only 2 layout.', 'wp-featured-content-and-slider'); ?></span>
			</td>
		</tr>
		<tr class="wpfcas-pro-feature">
			<th>
				<?php _e('Designs ', 'wp-featured-content-and-slider'); ?><span class="wpfcas-pro-tag"><?php _e('PRO','wp-featured-content-and-slider');?></span>
			</th>
			<td>
				<span class="description"><?php _e('20+. In lite version only 4 design.', 'wp-featured-content-and-slider'); ?></span>
			</td>
		</tr>
		<tr class="wpfcas-pro-feature">
			<th>
				<?php _e('WP Templating Features ', 'wp-featured-content-and-slider'); ?><span class="wpfcas-pro-tag"><?php _e('PRO','wp-featured-content-and-slider');?></span>
			</th>
			<td>
				<span class="description"><?php _e('You can modify plugin html/designs in your current theme.', 'wp-featured-content-and-slider'); ?></span>
			</td>
		</tr>
		<tr class="wpfcas-pro-feature">
			<th>
				<?php _e('Shortcode Generator ', 'wp-featured-content-and-slider'); ?><span class="wpfcas-pro-tag"><?php _e('PRO','wp-featured-content-and-slider');?></span>
			</th>
			<td>
				<span class="description"><?php _e('Play with all shortcode parameters with preview panel. No documentation required.' , 'wp-featured-content-and-slider'); ?></span>
			</td>
		</tr>
		<tr class="wpfcas-pro-feature">
			<th>
				<?php _e('Drag & Drop Slide Order Change ', 'wp-featured-content-and-slider'); ?><span class="wpfcas-pro-tag"><?php _e('PRO','wp-featured-content-and-slider');?></span>
			</th>
			<td>
				<span class="description"><?php _e('Arrange your desired slides with your desired order and display.' , 'wp-featured-content-and-slider'); ?></span>
			</td>
		</tr>
		<tr class="wpfcas-pro-feature">
			<th>
				<?php _e('Page Builder Support ', 'wp-featured-content-and-slider'); ?><span class="wpfcas-pro-tag"><?php _e('PRO','wp-featured-content-and-slider');?></span>
			</th>
			<td>
				<span class="description"><?php _e('Gutenberg Block, Elementor, Bevear Builder, SiteOrigin, Divi, Visual Composer and Fusion Page Builder Support', 'wp-featured-content-and-slider'); ?></span>
			</td>
		</tr>
		<tr class="wpfcas-pro-feature">
			<th>
				<?php _e('Exclude Featured Post and Exclude Some Categories ', 'wp-featured-content-and-slider'); ?><span class="wpfcas-pro-tag"><?php _e('PRO','wp-featured-content-and-slider');?></span>
			</th>
			<td>
				<span class="description"><?php _e('Do not display the featured post & Do not display the featured post for particular categories.' , 'wp-featured-content-and-slider'); ?></span>
			</td>
		</tr>
	</tbody>
</table><!-- end .wpfcas-metabox-table -->

