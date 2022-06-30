<?php
/**
 * Admin Class
 *
 * Handles the admin functionality of plugin
 *
 * @package WP Featured Content and Slider
 * @since 1.2.8
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Wpfcas_Admin {

	function __construct() {

		// Action to add admin menu
		add_action( 'admin_menu', array($this, 'wpfcas_register_menu'), 12 );

		// Action to add meta box
		add_action( 'add_meta_boxes', array($this, 'wpfcas_add_meta_box') );

		// Action to save post meta
		add_action( 'save_post', array($this, 'wpfcas_save_meta_box_data') );

		// Admin Init Processes
		add_action( 'admin_init', array($this, 'wpfcas_admin_init_process') );

		// Manage Category Shortcode Columns
		add_filter("manage_wpfcas-category_custom_column", array($this, 'wpfcas_category_columns'), 10, 3);
		add_filter("manage_edit-wpfcas-category_columns", array($this, 'wpfcas_category_manage_columns') ); 
	}

	/**
	 * Function to add menu
	 * 
	 * @package WP Featured Content and Slider
	 * @since 1.2.8
	 */
	function wpfcas_register_menu() {
		
		// Plugin features menu
		add_submenu_page( 'edit.php?post_type='.WPFCAS_POST_TYPE, __('Upgrade To Premium - WP Featured Content and Slider', 'wp-featured-content-and-slider'), '<span style="color:#ff2700">'.__('Upgrade To Premium', 'wp-featured-content-and-slider').'</span>', 'edit_posts', 'wpfcas-premium-page', array($this, 'wpfcas_premium_page') );
	}

	/**
	 * Getting Started Page Html
	 * 
	 * @package WP Featured Content and Slider
	 * @since 1.2.8
	 */
	function wpfcas_premium_page() {
		include_once( WPFCAS_DIR . '/includes/admin/settings/premium.php' );		
	}

	/**
	 * Function to add meta box
	 * 
	 * @package WP Featured Content and Slider
	 * @since 1.4
	 */
	function wpfcas_add_meta_box() {
		// Add custom meta box
		add_meta_box('custom-metabox',__( 'Featured Content Settings', 'wp-featured-content-and-slider' ), array($this, 'wpfcas_box_callback'), array(WPFCAS_POST_TYPE, 'post'), 'normal', 'high' );

		// Add premium meta box
		add_meta_box( 'wpfcas-post-metabox-pro', __('More Premium - Settings', 'wp-featured-content-and-slider'), array($this, 'wpfcas_post_sett_box_callback_pro'), WPFCAS_POST_TYPE, 'normal', 'default' );
	}

	/**
	 * Function to add meta box html file
	 * 
	 * @package WP Featured Content and Slider
	 * @since 1.5
	 */
	function wpfcas_box_callback( $post ) {
		include_once( WPFCAS_DIR. '/includes/admin/metabox/wpfcas-post-sett-metabox.php' );	
	}

	/**
	 * Function to add meta box html file
	 * 
	 * @package WP Featured Content and Slider
	 * @since 1.4.1
	 */
	function wpfcas_post_sett_box_callback_pro( $post ) {
		include_once( WPFCAS_DIR. '/includes/admin/metabox/wpfcas-post-setting-metabox-pro.php' );	
	}

	/**
	 * Function to post save meta value
	 * 
	 * @package WP Featured Content and Slider
	 * @since 1.2.8
	 */
	function wpfcas_save_meta_box_data( $post_id ) {
		global $post_type;

		$supported_post_types = array( WPFCAS_POST_TYPE, 'post' );

		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )                	// Check Autosave
		|| ( ! isset( $_POST['post_ID'] ) || $post_id != $_POST['post_ID'] )  	// Check Revision
		|| ( !in_array($post_type, $supported_post_types) ) )              					// Check if current post type is supported.
		{
		  return $post_id;
		}

		// Taking variables
		$featured_icon 	= isset($_POST['wpfcas_slide_icon']) ? stripslashes_deep($_POST['wpfcas_slide_icon']) : '';
		$read_more_link = isset($_POST['wpfcas_slide_link']) ? stripslashes_deep($_POST['wpfcas_slide_link']) : '';

		update_post_meta($post_id, 'wpfcas_slide_icon', $featured_icon);
		update_post_meta($post_id, 'wpfcas_slide_link', $read_more_link);
	}

	/**
	 * Function to notification transient
	 * 
	 * @package WP Featured Content and Slider
	 * @since 1.2.8
	 */
	function wpfcas_admin_init_process() {
		// If plugin notice is dismissed
	    if( isset($_GET['message']) && $_GET['message'] == 'wpfcas-plugin-notice' ) {
	    	set_transient( 'wpfcas_install_notice', true, 604800 );
	    }
	}

	/**
	 * Function to category columns name
	 * 
	 * @package WP Featured Content and Slider
	 * @since 1.4
	 */
	function wpfcas_category_manage_columns($theme_columns) {
	    $new_columns = array(
	            'cb' => '<input type="checkbox" />',
	            'name' => __('Name'),
	            'featured_shortcode' => __( 'Category Shortcode', 'wp-featured-content-and-slider' ),
	            'slug' => __('Slug'),
	            'posts' => __('Posts')
				);

	    return $new_columns;
	}

	/**
	 * Function to category columns
	 * 
	 * @package WP Featured Content and Slider
	 * @since 1.4
	 */
	function wpfcas_category_columns($out, $column_name, $theme_id) {
	    $theme = get_term($theme_id, 'wpfcas-category');
	    switch ($column_name) {      
	        case 'title':
	            echo get_the_title();
	        break;
	        case 'featured_shortcode':
				echo '[featured-content cat_id="' . $theme_id. '"]<br />';
				echo '[featured-content-slider cat_id="' . $theme_id. '"]';
	        break;
	        default:
	            break;
	    }
	    return $out;   

	}

}

$wpfcas_Admin = new Wpfcas_Admin();