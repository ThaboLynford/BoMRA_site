<?php
/**
 * Blocks Initializer
 * 
 * @package WP Featured Content and Slider
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function wpfcas_register_guten_block() {

	// Block Editor Script
	wp_register_script( 'wpfcas-block-js', WPFCAS_URL.'assets/js/blocks.build.js', array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'wp-components' ), WPFCAS_VERSION, true );
	wp_localize_script( 'wpfcas-block-js', 'WpFcas_Block', array(
																'pro_demo_link'		=> 'https://demo.wponlinesupport.com/prodemo/pro-wp-featured-content-and-slider/',
																'free_demo_link'	=> 'https://demo.wponlinesupport.com/featured-content-and-slider-demo/',
																'pro_link'			=> WPFCAS_PLUGIN_LINK,
															));

	// Register block and explicit attributes for grid
	register_block_type( 'wpfcas/icon-grid', array(
		'attributes' => array(
			'design'	=> array(
							'type'		=> 'string',
							'default'	=> 'design-1',
						),
			'grid'	=> array(
							'type'		=> 'number',
							'default'	=> 1,
						),
			'show_content'	=> array(
							'type'		=> 'string',
							'default'	=> 'true',
						),
			'content_words_limit' => array(
							'type'		=> 'number',
							'default'	=> 50,
						),
			'display_read_more'	=> array(
							'type'		=> 'string',
							'default'	=> 'true',
						),
			'fa_icon_color'	=> array(
							'type'		=> 'string',
							'default'	=> '#3ab0e2',
						),
			'image_style'	=> array(
							'type'		=> 'string',
							'default'	=> 'square',
						),
			'limit'	=> array(
							'type'		=> 'number',
							'default'	=> -1,
						),
			'post_type'	=> array(
							'type'		=> 'string',
							'default'	=> WPFCAS_POST_TYPE,
						),
			'taxonomy'	=> array(
							'type'		=> 'string',
							'default'	=> WPFCAS_CAT,
						),
			'cat_id'	=> array(
							'type'		=> 'string',
							'default'	=> '',
						),
			'align' => array(
							'type'		=> 'string',
							'default'	=> '',
						),
			'className' => array(
							'type'		=> 'string',
							'default'	=> '',
						),
		),
		'render_callback' => 'wpfcas_featuredc_shortcode',
	));

	// Register block and explicit attributes for Slider
	register_block_type( 'wpfcas/icon-slider', array(
		'attributes' => array(
			'design'	=> array(
							'type'		=> 'string',
							'default'	=> 'design-1',
						),
			'show_content' => array(
							'type'		=> 'string',
							'default'	=> 'true',
						),
			'content_words_limit' => array(
							'type'		=> 'number',
							'default'	=> 50,
						),
			'display_read_more' => array(
							'type'		=> 'string',
							'default'	=> 'true',
						),
			'fa_icon_color' => array(
							'type'		=> 'string',
							'default'	=> '#3ab0e2',
						),
			'image_style' => array(
							'type'		=> 'string',
							'default'	=> 'square',
						),
			'slides_column' => array(
							'type'		=> 'number',
							'default'	=> 3,
						),
			'slides_scroll' => array(
							'type'		=> 'number',
							'default'	=> 1,
						),
			'dots' => array(
							'type'		=> 'string',
							'default'	=> 'true',
						),
			'arrows' => array(
							'type'		=> 'string',
							'default'	=> 'true',
						),
			'autoplay' => array(
							'type'		=> 'string',
							'default'	=> 'true',
						),
			'autoplay_interval' => array(
							'type'		=> 'number',
							'default'	=> 3000,
						),
			'speed' => array(
							'type'		=> 'number',
							'default'	=> 300,
						),
			'limit' => array(
							'type'		=> 'number',
							'default'	=> -1,
						),
			'post_type' => array(
							'type'		=> 'string',
							'default'	=> WPFCAS_POST_TYPE,
						),
			'taxonomy' => array(
							'type'		=> 'string',
							'default'	=> WPFCAS_CAT,
						),
			'cat_id' => array(
							'type'		=> 'string',
							'default'	=> '',
						),
			'align' => array(
							'type'		=> 'string',
							'default'	=> '',
						),
			'className' => array(
						'type'		=> 'string',
						'default'	=> '',
					),
		),
		'render_callback' => 'wpfcas_featuredcslider_shortcode',
	));

	if ( function_exists( 'wp_set_script_translations' ) ) {
		wp_set_script_translations( 'wpfcas-block-js', 'wp-featured-content-and-slider', WPFCAS_DIR . '/languages' );
	}
}
add_action( 'init', 'wpfcas_register_guten_block' );

/**
 * Enqueue Gutenberg block assets for backend editor.
 *
 * @uses {wp-blocks} for block type registration & related functions.
 * @uses {wp-element} for WP Element abstraction — structure of blocks.
 * @uses {wp-i18n} to internationalize the block's text.
 * @uses {wp-editor} for WP editor styles.
 * 
 * @package WP Featured Content and Slider
 * @since 1.0
 */
function wpfcas_editor_assets() {

	// Block Editor CSS
	if( ! wp_style_is( 'wpos-guten-block-css', 'registered' ) ) {
		wp_register_style( 'wpos-guten-block-css', WPFCAS_URL.'assets/css/blocks.editor.build.css', array( 'wp-edit-blocks' ), WPFCAS_VERSION );
	}

	// Block Editor Script
	wp_enqueue_style( 'wpos-guten-block-css' );
	wp_enqueue_script( 'wpfcas-block-js' );
}
add_action( 'enqueue_block_editor_assets', 'wpfcas_editor_assets' );

/**
 * Adds an extra category to the block inserter
 *
 * @package WP Featured Content and Slider
 * @since 1.0
 */
function wpfcas_add_block_category( $categories ) {

	$guten_cats = wp_list_pluck( $categories, 'slug' );

	if( ! in_array( 'wpos_guten_block', $guten_cats ) ) {
		$categories[] = array(
							'slug'	=> 'wpos_guten_block',
							'title'	=> __('WPOS Blocks', 'wp-featured-content-and-slider'),
							'icon'	=> null,
						);
	}

	return $categories;
}
add_filter( 'block_categories_all', 'wpfcas_add_block_category' );