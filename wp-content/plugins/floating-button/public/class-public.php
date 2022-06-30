<?php
/**
 * Public Class
 *
 * @package     Wow_Plugin
 * @subpackage  Public
 * @author      Wow-Company <helper@wow-company.com>
 * @copyright   2019 Wow-Company
 * @license     GNU Public License
 * @version     1.0
 */

namespace floatingbutton;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Wow_Plugin_Public
 *
 * @package wow_plugin
 *
 * @property array plugin   - base information about the plugin
 * @property array url      - home, pro and other URL for plugin
 * @property array rating   - website and link for rating
 * @property string basedir  - filesystem directory path for the plugin
 * @property string baseurl  - URL directory path for the plugin
 */
class Wow_Plugin_Public {

	/**
	 * Setup to frontend of the plugin
	 *
	 * @param array $info general information about the plugin
	 *
	 * @since 1.0
	 */

	public function __construct( $info ) {

		$this->plugin = $info['plugin'];
		$this->url    = $info['url'];
		$this->rating = $info['rating'];

		add_shortcode( $this->plugin['shortcode'], array( $this, 'shortcode' ) );

		// Display on the site
		add_action( 'wp_footer', array( $this, 'display' ) );
		add_filter( 'script_loader_tag', array( $this, 'add_defer_attribute' ), 10, 2 );
		add_action( 'amp_post_template_footer', array( $this, 'display' ) );

	}


	/**
	 * Display a shortcode
	 *
	 * @param $atts
	 *
	 * @return false|string
	 */
	public function shortcode( $atts ) {
		extract( shortcode_atts( array( 'id' => "" ), $atts ) );
		$id = absint( $atts['id'] );

		global $wpdb;
		$table  = $wpdb->prefix . 'wow_' . $this->plugin['prefix'];
		$sSQL   = $wpdb->prepare( "select * from $table WHERE id = %d", $id );
		$result = $wpdb->get_results( $sSQL, 'OBJECT_K' );

		if ( empty( $result ) ) {
			return false;
		}

		$param = unserialize( $result[ $id ]->param );
		$check = $this->check( $param, $id );

		if ( $check === false ) {
			return false;
		}

		$menu = '';
		include( 'partials/public.php' );

		$this->include_style_script( $param, $id );

		return $menu;

	}

	private function include_style_script( $param, $id ) {

		$slug    = $this->plugin['slug'];
		$version = $this->plugin['version'];

		$pre_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		$url_style = plugin_dir_url( __FILE__ ) . 'assets/css/style' . $pre_suffix . '.css';
		wp_enqueue_style( $slug, $url_style, null, $version );

		$inline_style = self::style( $param, $id );
		wp_add_inline_style( $slug, $inline_style );

		if ( empty( $param['disable_fontawesome'] ) ) {
			$url_icons = $this->plugin['url'] . 'vendors/fontawesome/css/fontawesome-all' . $pre_suffix . '.css';
			wp_enqueue_style( $slug . '-fontawesome', $url_icons, null, '5.14' );
		}

	}


	/**
	 * Display the Item on the specific pages, not via the Shortcode
	 */
	public function display() {
		require plugin_dir_path( __FILE__ ) . 'display.php';
	}

	/**
	 * Create Inline style for elements
	 */
	public function style( $param, $id ) {
		$css = '';
		require 'generator-style.php';

		return $css;
	}

	/**
	 * Add the tag defer to script
	 */
	function add_defer_attribute( $tag, $handle ) {
		if ( $this->plugin['slug'] !== $handle ) {
			return $tag;
		}

		return str_replace( ' src', ' defer="defer" src', $tag );
	}

	private function check_test_mode( $param ) {
		if ( ! empty( $param['test_mode'] ) && ! current_user_can( 'administrator' ) ) {
			return false;
		}

		return true;
	}

	private function check_status( $param ) {
		$status = isset( $param['menu_status'] ) ? $param['menu_status'] : 1;
		if ( empty( $status ) ) {
			return false;
		}

		return true;
	}

	private function check( $param, $id ) {
		$check     = true;
		$check_arr = array(
			'status'    => $this->check_status( $param ),
			'test_mode' => $this->check_test_mode( $param ),
		);

		foreach ( $check_arr as $value ) {
			if ( $value === false ) {
				$check = false;
				break;
			}
		}

		return $check;
	}


}
