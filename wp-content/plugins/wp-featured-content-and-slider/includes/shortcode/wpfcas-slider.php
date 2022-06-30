<?php 
/**
 * function for 'featured-content-slider'
 * 
 * @package WP Featured Content and Slider
 * @since 1.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function wpfcas_featuredcslider_shortcode( $atts) {

	// SiteOrigin Page Builder Gutenberg Block Tweak - Do not Display Preview
	if( isset( $_POST['action'] ) && ($_POST['action'] == 'so_panels_layout_block_preview' || $_POST['action'] == 'so_panels_builder_content_json') ) {
		return "[featured-content-slider]";
	}

	// Divi Frontend Builder - Do not Display Preview
	if( function_exists( 'et_core_is_fb_enabled' ) && isset( $_POST['is_fb_preview'] ) && isset( $_POST['shortcode'] ) ) {
		return '<div class="wpfcas-builder-shrt-prev">
					<div class="wpfcas-builder-shrt-title"><span>'.esc_html__('Featured Slider - Shortcode', 'wp-featured-content-and-slider').'</span></div>
					featured-content-slider
				</div>';
	}

	// Fusion Builder Live Editor - Do not Display Preview
	if( class_exists( 'FusionBuilder' ) && (( isset( $_GET['builder'] ) && $_GET['builder'] == 'true' ) || ( isset( $_POST['action'] ) && $_POST['action'] == 'get_shortcode_render' )) ) {
		return '<div class="wpfcas-builder-shrt-prev">
					<div class="wpfcas-builder-shrt-title"><span>'.esc_html__('Featured Slider - Shortcode', 'wp-featured-content-and-slider').'</span></div>
					featured-content-slider
				</div>';
	}

	// Grobal variable
	global $post;

	extract(shortcode_atts(array(
		'limit' 				=> -1,		
		'cat_id' 				=> '',
		'post_type' 			=> WPFCAS_POST_TYPE,
		'taxonomy'				=> WPFCAS_CAT,
		'design' 				=> 'design-1',
		'fa_icon_color' 		=> '#3ab0e2',
		'image_style' 			=> 'square',
		'display_read_more' 	=> 'true',
		'slides_column'     	=> 3,
		'slides_scroll'     	=> 1,		
		'dots'     				=> 'true',
		'arrows'     			=> 'true',				
		'autoplay'     			=> 'true',		
		'autoplay_interval' 	=> 3000,				
		'speed'             	=> 300,
		'content_words_limit' 	=> 50,
		'show_content' 			=> 'true',
		'extra_class'			=> '',
		'className'				=> '',
		'align'					=> '',		
	), $atts, 'featured-content-slider'));
	
	$shortcode_designs		= wpfcas_designs();
	$design 				= array_key_exists( trim( $design ), $shortcode_designs ) ? $design 	: 'design-1';
	$post_type 				= ! empty( $post_type )				? $post_type 				: WPFCAS_POST_TYPE;
	$taxonomy 				= ! empty( $taxonomy )				? $taxonomy 				: WPFCAS_CAT;
	$limit 					= ! empty( $limit )					? $limit					: -1;
	$cat_id 				= ! empty( $cat_id )				? explode( ',', $cat_id ) 	: '';
	$fa_icon_color 			= ! empty( $fa_icon_color )			? $fa_icon_color 			: '#3ab0e2';
	$image_style 			= ! empty( $image_style ) 			? $image_style 				: 'square';
	$show_content 			= ! empty( $show_content ) 			? $show_content 			: 'true';
	$content_words_limit 	= ! empty( $content_words_limit ) 	? $content_words_limit 		: 50;
	$slides_column 			= ! empty( $slides_column ) 		? $slides_column 			: 3;
	$slides_scroll 			= ! empty( $slides_scroll ) 		? $slides_scroll 			: 1;
	$autoplay_interval		= ! empty( $autoplay_interval ) 	? $autoplay_interval 		: 3000;
	$speed 					= ! empty( $speed ) 				? $speed 					: 300;
	$display_read_more 		= ( $display_read_more == 'false' )	? 'false'					: 'true';
	$dots 					= ( $dots == 'false' )				? 'false'					: 'true';
	$arrows 				= ( $arrows == 'false' )			? 'false'					: 'true';
	$autoplay 				= ( $autoplay == 'false' )			? 'false'					: 'true';
	$align					= ! empty( $align )					? 'align'.$align			: '';
	$extra_class			= $extra_class .' '. $align .' '. $className;
	$extra_class			= wpfcas_sanitize_html_classes( $extra_class );

	// Slider conf
	$slider_conf = compact( 'dots', 'arrows', 'speed', 'autoplay', 'autoplay_interval', 'slides_column', 'slides_scroll' );

	// Required enqueue_script
	wp_enqueue_script( 'wpos-slick-jquery' );
	wp_enqueue_script( 'wpfcas-public-js' );

	// Design template Paths
	$design_file = WPFCAS_DIR . '/templates/' . $design . '.php';
	$design_file 	= (file_exists($design_file)) ? $design_file : '';

	// Some ariables
	$css_class 	= "slider-col-{$slides_column}";
	$unique 	= wpfcas_get_unique();

	// Post argument
	$args = array (
		'post_type'			=> $post_type, 
		'orderby'			=> 'post_date', 
		'order'				=> 'DESC',
		'posts_per_page'	=> $limit,
	);

	// Category argument
	if($cat_id != "") {
		$args['tax_query'] = array(
									array(
										'taxonomy' 	=> $taxonomy,
										'field' 	=> 'term_id',
										'terms' 	=> $cat_id
									));
	}

	ob_start();

	//WP Query
	$query = new WP_Query($args); ?>

	<div id="wpfcas-content-slider-<?php echo $unique; ?>" class="featured-content-slider <?php echo $design.' '.$extra_class; ?>" data-conf="<?php echo htmlspecialchars(json_encode($slider_conf)); ?>">
		 <?php while ($query->have_posts()) : $query->the_post();
			
			$sliderurl	= get_post_meta( $post->ID, 'wpfcas_slide_link', true );
			$wpfcasIcon = get_post_meta( $post->ID, 'wpfcas_slide_icon', true );
			$feat_image	= wp_get_attachment_url( get_post_thumbnail_id() );
			$fcontent	= wpfcas_get_post_excerpt( $post->ID, get_the_content(), $content_words_limit, '...' );

			// Include shortcode html file
			if( $design_file ) {
				include( $design_file );
			}
		endwhile; ?>
	</div>
	<?php 
	wp_reset_postdata();
	return ob_get_clean();
}

add_shortcode('featured-content-slider', 'wpfcas_featuredcslider_shortcode');