<?php
/**
 * Register Post type functionality
 *
 * @package WP Featured Content and Slider
 * @since 1.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Function to register post type
 * 
 * @package WP Featured Content and Slider
 * @since 1.4
 */
function wpfcas_setup_post_types() {

	$wpfcas_labels =  apply_filters( 'wpfcas_labels', array(
						'name'                => _x('Featured Content', 'wp-featured-content-and-slider'),
						'singular_name'       => _x('Featured Content', 'wp-featured-content-and-slider'),
						'add_new'             => __('Add Content', 'wp-featured-content-and-slider'),
						'add_new_item'        => __('Add Content', 'wp-featured-content-and-slider'),
						'edit_item'           => __('Edit Content', 'wp-featured-content-and-slider'),
						'new_item'            => __('New Content', 'wp-featured-content-and-slider'),
						'all_items'           => __('All Content', 'wp-featured-content-and-slider'),
						'view_item'           => __('View Content', 'wp-featured-content-and-slider'),
						'search_items'        => __('Search Content', 'wp-featured-content-and-slider'),
						'not_found'           => __('No Content found', 'wp-featured-content-and-slider'),
						'not_found_in_trash'  => __('No Content found in Trash', 'wp-featured-content-and-slider'),
						'parent_item_colon'   => '',
						'menu_name'           => __('Featured Content', 'wp-featured-content-and-slider'),
						'exclude_from_search' => true
					));

	$wpfcas_args = array(
						'labels' 			=> $wpfcas_labels,
						'public' 			=> true,
						'publicly_queryable'=> true,
						'show_ui' 			=> true,
						'show_in_menu' 		=> true,
						'query_var' 		=> true,
						'capability_type' 	=> 'post',
						'has_archive' 		=> true,
						'hierarchical' 		=> false,
						'menu_icon'   => 'dashicons-star-filled',
						'supports' => array('title','editor','thumbnail','excerpt')
		
				);
	register_post_type( 'featured_post', apply_filters( 'wpfcas_post_type_args', $wpfcas_args ) );

}

// Action to register post type
add_action('init', 'wpfcas_setup_post_types');

/**
 * Function to register taxonomy
 * 
 * @package WP Featured Content and Slider
 * @since 1.0.0
 */
function wpfcas_taxonomies() {
    $labels = array(
        'name'              => _x( 'Category', 'wp-featured-content-and-slider' ),
        'singular_name'     => _x( 'Category', 'wp-featured-content-and-slider' ),
        'search_items'      => __( 'Search Category', 'wp-featured-content-and-slider' ),
        'all_items'         => __( 'All Category', 'wp-featured-content-and-slider' ),
        'parent_item'       => __( 'Parent Category', 'wp-featured-content-and-slider' ),
        'parent_item_colon' => __( 'Parent Category:', 'wp-featured-content-and-slider' ),
        'edit_item'         => __( 'Edit Category', 'wp-featured-content-and-slider' ),
        'update_item'       => __( 'Update Category', 'wp-featured-content-and-slider' ),
        'add_new_item'      => __( 'Add New Category', 'wp-featured-content-and-slider' ),
        'new_item_name'     => __( 'New Category Name', 'wp-featured-content-and-slider' ),
        'menu_name'         => __( 'Category', 'wp-featured-content-and-slider' ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'wpfcas-category' ),
    );

    register_taxonomy( 'wpfcas-category', array( 'featured_post' ), $args );
}

// Action to register taxonomies
add_action( 'init', 'wpfcas_taxonomies');