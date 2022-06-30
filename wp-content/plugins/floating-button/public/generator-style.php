<?php
/**
 * Inline Style generator
 *
 * @package     Wow_Plugin
 * @author      Wow-Company <helper@wow-company.com>
 * @copyright   2019 Wow-Company
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$css = '';

$count_i = isset($param['menu_1']['item_type']) ? count( $param['menu_1']['item_type'] ) : 0;
for ( $i = 0; $i < $count_i; $i ++ ) {
	$item = $i + 1;
	$css  .= '
			#floatBtn-' . $id . ' .flBtn-first li:nth-child(' . $item . ') a {
				background-color: ' . $param['menu_1']['button_color'][ $i ] . ';
				color: ' . $param['menu_1']['icon_color'][ $i ] . ';
			}	
			#floatBtn-' . $id . ' .flBtn-first li:nth-child(' . $item . ') a:hover {
				 background-color: ' . $param['menu_1']['button_hcolor'][ $i ] . ';
				 color: ' . $param['menu_1']['icon_color'][ $i ] . ';
			}
		';
}

$count_i = isset($param['menu_2']['item_type']) ? count( $param['menu_2']['item_type'] ) : 0;
for ( $i = 0; $i < $count_i; $i ++ ) {
	$item = $i + 1;
	$css  .= '
			#floatBtn-' . $id . ' .flBtn-second li:nth-child(' . $item . ') a {
				background-color: ' . $param['menu_2']['button_color'][ $i ] . ';
				color: ' . $param['menu_2']['icon_color'][ $i ] . ';
			}	
			#floatBtn-' . $id . ' .flBtn-second li:nth-child(' . $item . ') a:hover {
				 background-color: ' . $param['menu_2']['button_hcolor'][ $i ] . ';
				 color: ' . $param['menu_2']['icon_color'][ $i ] . ';
			}
		';
}

$css .= ' 
		#floatBtn-' . $id . ' a {
		   background-color: ' . $param['button_color'] . ';
			 color: ' . $param['icon_color'] . ';
		}
		#floatBtn-' . $id . ' a:hover ,
		#floatBtn-' . $id . ' input:hover + a{
		   background-color: ' . $param['button_hcolor'] . ';
			 color: ' . $param['icon_color'] . ';
		}
		#floatBtn-' . $id . ' [tooltip]:before {
			background: ' . $param['tooltip_background'] . ';
			color: ' . $param['tooltip_color'] . ';
		}
	
	';

if ( ! empty( $param['include_mobile'] ) ) {
	$screen = ! empty( $param['screen'] ) ? $param['screen'] : 480;
	$css    .= '
		@media only screen and (max-width: ' . $screen . 'px){
			#floatBtn-' . $id . ' {
				display:none;
			}
		}';
}
if ( ! empty( $param['include_more_screen'] ) ) {
	$screen_more = ! empty( $param['screen_more'] ) ? $param['screen_more'] : 1200;
	$css         .= '
		@media only screen and (min-width: ' . $screen_more . 'px){
			#floatBtn-' . $id . ' {
				display:none;
			}
		}';
}

$css = trim( preg_replace( '~\s+~s', ' ', $css ) );
