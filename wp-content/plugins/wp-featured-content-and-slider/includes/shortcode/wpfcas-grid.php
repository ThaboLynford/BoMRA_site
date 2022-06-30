<?php
/**
 * function for 'featured-content'
 * 
 * @package WP Featured Content and Slider
 * @since 1.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function wpfcas_featuredc_shortcode( $atts) {

	// SiteOrigin Page Builder Gutenberg Block Tweak - Do not Display Preview
	if( isset( $_POST['action'] ) && ($_POST['action'] == 'so_panels_layout_block_preview' || $_POST['action'] == 'so_panels_builder_content_json') ) {
		return "[featured-content]";
	}

	// Grobal variable
	global $post;

	extract(shortcode_atts(array(
		'limit' 				=> -1,
		'post_type' 			=> WPFCAS_POST_TYPE,
		'taxonomy'				=> WPFCAS_CAT,
		'grid' 					=> 1,
		'cat_id' 				=> '',
		'design' 				=> 'design-1',
		'fa_icon_color' 		=> '#3ab0e2',
		'image_style' 			=> 'square',
		'show_content' 			=> 'true',
		'display_read_more' 	=> 'true',
		'content_words_limit' 	=> 50,
		'extra_class'			=> '',
		'className'				=> '',
		'align'					=> '',
	), $atts, 'featured-content'));

	$shortcode_designs		= wpfcas_designs();
	$design 				= array_key_exists( trim( $design ), $shortcode_designs ) ? $design 	: 'design-1';
	$post_type 				= ! empty( $post_type )				? $post_type 				: WPFCAS_POST_TYPE;
	$taxonomy 				= ! empty( $taxonomy )				? $taxonomy					: WPFCAS_CAT;
	$grid					= ( ! empty( $grid ) && is_numeric($grid) && $grid <= 4 ) ? $grid  : 1;
	$image_style 			= ! empty( $image_style ) 			? $image_style 				: 'square';
	$limit					= ! empty( $limit ) 				? $limit 					: -1;
	$cat_id 				= ! empty( $cat_id )				? explode( ',', $cat_id ) 	: '';
	$fa_icon_color 			= ! empty( $fa_icon_color )			? $fa_icon_color 			: '#3ab0e2';
	$content_words_limit 	= ! empty( $content_words_limit )	? $content_words_limit 		: 50;
	$display_read_more 		= ( $display_read_more == 'false' )	? 'false'					: 'true';
	$show_content 			= ( $show_content == 'false' )		? 'false'					: 'true';
	$align					= ! empty( $align )					? 'align'.$align			: '';
	$extra_class			= $extra_class .' '. $align .' '. $className;
	$extra_class			= wpfcas_sanitize_html_classes( $extra_class );
	$grid_clmn				= wpfcas_column( $grid );

	// Design file paths
	$design_file 	= WPFCAS_DIR . '/templates/' . $design . '.php';
	$design_file 	= (file_exists($design_file)) ? $design_file : '';
	// Post Type argument
	$args = array ( 
            'post_type'      => $post_type, 
            'orderby'        => 'post_date', 
            'order'          => 'DESC',
            'posts_per_page' => $limit,
    	);

	if($cat_id != "") {
					$args['tax_query'] = array(
											array(
													'taxonomy' 	=> $taxonomy,
													'field' 	=> 'term_id',
													'terms' 	=> $cat_id
												));
    }
	
    // Count variable
    $count = 0;

    // WP Query
	$query = new WP_Query($args);

	ob_start();
?>
	<div class="featured-content-list <?php echo $design.' '.$extra_class; ?>">
		<?php while ($query->have_posts()) : $query->the_post();		

			$count++;
			$sliderurl	= get_post_meta( $post->ID, 'wpfcas_slide_link', true );
			$wpfcasIcon = get_post_meta( $post->ID, 'wpfcas_slide_icon', true );
			$feat_image	= wp_get_attachment_url( get_post_thumbnail_id() );
			$fcontent	= wpfcas_get_post_excerpt( $post->ID, get_the_content(), $content_words_limit, '...' );

			// CSS Class 
			$css_class = 'featured-content';
			$css_class .= ( $count % $grid  == 1 ) ? ' first' : '';
			$css_class .= ' wp-medium-'.$grid_clmn.' wpcolumns';

			// Include shortcode html file
			if( $design_file ) {
				include( $design_file );
			}

		endwhile; ?>
	</div>

	<?php wp_reset_postdata();

	return ob_get_clean();
}

add_shortcode('featured-content', 'wpfcas_featuredc_shortcode');