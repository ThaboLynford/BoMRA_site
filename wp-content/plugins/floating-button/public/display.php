<?php
/**
 * Conditions for display shortcode on the frontend
 *
 * @package     Wow_Plugin
 * @subpackage  Public/Display
 * @author      Wow-Company <helper@wow-company.com>
 * @copyright   2019 Wow-Company
 * @license     GNU Public License
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wpdb;
$table  = $wpdb->prefix . "wow_" . $this->plugin['prefix'];
$result = $wpdb->get_results( "SELECT * FROM " . $table . " order by id asc" );

if ( count( $result ) > 0 ) {
	foreach ( $result as $key => $val ) {
		$param         = unserialize( $val->param );
		$param['show'] = ! empty( $param['show'] ) ? $param['show'] : 'all';
		if ( $param['show'] == 'all' ) {
			echo do_shortcode( '[' . $this->plugin['shortcode'] . ' id=' . $val->id . ']' );
		}
	}
}
