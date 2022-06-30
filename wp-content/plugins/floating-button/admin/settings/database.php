<?php
/**
 * Include data param for Wow Plugin
 *
 * @package     Wow_Plugin
 * @subpackage  Admin/Data_Item
 * @author      Wow-Company <helper@wow-company.com>
 * @copyright   2019 Wow-Company
 * @license     GNU Public License
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$act = ( isset( $_REQUEST["act"] ) ) ? sanitize_text_field( $_REQUEST["act"] ) : '';


if ( $act === "update" ) {
	$rec_id = absint( $_REQUEST["id"] );
	$query  = $wpdb->prepare( "SELECT * FROM {$data} WHERE id=%d", absint( $rec_id ) );
	$result = $wpdb->get_row( $query );
	if ( $result ) {
		$id         = $result->id;
		$title      = $result->title;
		$param      = unserialize( $result->param );
		$tool_id    = $id;
		$add_action = 2;
		$btn        = esc_attr__( 'Update', 'floating-button' );
	}
} elseif ( $act === "duplicate" ) {
	$rec_id     = absint( $_REQUEST["id"] );
	$query  = $wpdb->prepare( "SELECT * FROM {$data} WHERE id=%d", absint( $rec_id ) );
	$result     = $wpdb->get_row( $query );
	$id         = "";
	$title      = "";
	$add_action = 1;
	$btn        = esc_attr__( 'Save', 'floating-button' );
	$last       = $wpdb->get_col( "SELECT id FROM {$data}" );
	$tool_id    = ! empty( $last ) ? max( $last ) + 1 : 1;
	$param      = ( $result ) ? unserialize( $result->param ) : '';

} else {
	$id         = "";
	$title      = "";
	$last       = $wpdb->get_col( "SELECT id FROM {$data}" );
	$tool_id    = ! empty( $last ) ? max( $last ) + 1 : 1;
	$param      = '';
	$add_action = 1;
	$btn        = esc_attr__( 'Save', 'floating-button' );
}
