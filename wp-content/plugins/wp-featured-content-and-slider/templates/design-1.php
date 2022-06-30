<?php
/**
 * Template design-1
 * 
 * @package WP Featured Content and Slider
 * @since 1.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="<?php echo $css_class;?>">
	<div class="featured-content-image <?php echo $image_style; ?>"> 
		<?php if($sliderurl != '') { ?>
			<a href="<?php echo esc_url($sliderurl); ?>" >
				<?php if($wpfcasIcon != '') { 
					echo '<i style="color:'.$fa_icon_color.'" class="'.$wpfcasIcon.'"></i>'; 
				} else { 
					the_post_thumbnail(array(100,100)); 
				} ?>
			</a>
		<?php } else {
			if($wpfcasIcon != '') { 
				echo '<i style="color:'.$fa_icon_color.'" class="'.$wpfcasIcon.'"></i>';
			} else {
				the_post_thumbnail(array(100,100)); 
			}
		} ?>
	</div>
	<div class="featured-content">
		<h3 class="entry-title">
			<?php if($sliderurl != '') { ?>
				<a href="<?php echo esc_url($sliderurl); ?>" >
					<?php echo get_the_title(); ?>
				</a>
			<?php } else {
				echo get_the_title();
			} ?>
		</h3>
		
		<?php if( $show_content == "true" && !empty( $fcontent ) ) { ?>
			<div class="featured_short_content">
				<div class="sub-content"><?php echo $fcontent; ?></div>
			</div>
		<?php }

		if($display_read_more == 'true' && $sliderurl != '') {
		?>
			<div class="featured-read-more">
				<a href="<?php echo esc_url($sliderurl); ?>" >
					<?php esc_html_e( 'Read More', 'wp-featured-content-and-slider' ); ?>
				</a>
			</div>
		<?php } ?>
	</div>
</div>