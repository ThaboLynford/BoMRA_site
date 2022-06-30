<?php
/**
 * Public code
 *
 * @package     Wow_Plugin
 * @copyright   Copyright (c) 2018, Dmytro Lobov
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$menu_icon = '<i class="' . $param['button_icon'] . '"></i>';


$menu = '<div class="flBtn flBtn-position-br flBtn-size-medium flBtn-shape-circle" id="floatBtn-' . $id . '">';
$menu .= '<label for="floating-main-button-' . $id . '" class="flBtn-screen-reader-text">' . esc_attr__( 'Floating button', 'floating-button' ) . '</label>';
$menu .= '<input type="checkbox" id="floating-main-button-' . $id . '">';
$menu .= '<a role="button" data-role="main">' . $menu_icon . '</a>';

// Build Vertical button menu

if ( ! empty( $param['menu_1']['item_type'] ) ) :
	$menu    .= '<ul class="flBtn-first">';
	$count_i = count( $param['menu_1']['item_type'] );

	for ( $i = 0; $i < $count_i; $i ++ ) {
		$menu .= '<li>';

		$icon = '<i class="' . $param['menu_1']['item_icon'][ $i ] . '"></i>';

		if ( ! empty( $param['menu_1']['item_tooltip_include'][ $i ] ) ) {
			$tooltip = ' data-tooltip="' . $param['menu_1']['item_tooltip'][ $i ] . '"';
		} else {
			$tooltip = '';
		}

		$item_type  = $param['menu_1']['item_type'][ $i ];
		$link_param = $tooltip;
		$link       = $param['menu_1']['item_link'][ $i ];

		switch ( $item_type ) {
			case 'link':
				$menu .= '<a href="' . $link . '" ' . $link_param . '>' . $icon . '</a>';
				break;
			case 'login':
				$menu .= '<a rel="nofollow" href="' . wp_login_url( $link ) . '" ' . $link_param . '>' . $icon . '</a>';
				break;
			case 'logout':
				$menu .= '<a rel="nofollow" href="' . wp_logout_url( $link ) . '" ' . $link_param . '>' . $icon
				         . '</a>';
				break;
			case 'register':
				$menu .= '<a rel="nofollow" href="' . wp_registration_url() . '" ' . $link_param . '>' . $icon . '</a>';
				break;
			case 'lostpassword':
				$menu .= '<a rel="nofollow" href="' . wp_lostpassword_url( $link ) . '" ' . $link_param . '>' . $icon
				         . '</a>';
				break;

		}
		$menu .= '</li>';
	}
	$menu .= '</ul>';
endif;

// Build Horizontal button menu
if ( ! empty( $param['menu_2']['item_type'] ) ) :
	$menu    .= '<ul class="flBtn-second">';
	$count_i = count( $param['menu_2']['item_type'] );
	for ( $i = 0; $i < $count_i; $i ++ ) {
		$menu .= '<li>';
		$icon = '<i class="' . $param['menu_2']['item_icon'][ $i ] . '"></i>';
		if ( ! empty( $param['menu_2']['item_tooltip_include'][ $i ] ) ) {

			$tooltip = ' data-tooltip="' . $param['menu_2']['item_tooltip'][ $i ] . '"';
		} else {
			$tooltip = '';
		}
		$item_type  = $param['menu_2']['item_type'][ $i ];
		$link_param = $tooltip;
		$link       = $param['menu_2']['item_link'][ $i ];

		switch ( $item_type ) {
			case 'link':
				$menu .= '<a href="' . $link . '" ' . $link_param . '>' . $icon . '</a>';
				break;
			case 'login':
				$menu .= '<a rel="nofollow" href="' . wp_login_url( $link ) . '" ' . $link_param . '>' . $icon . '</a>';
				break;
			case 'logout':
				$menu .= '<a rel="nofollow" href="' . wp_logout_url( $link ) . '" ' . $link_param . '>' . $icon
				         . '</a>';
				break;
			case 'register':
				$menu .= '<a rel="nofollow" href="' . wp_registration_url() . '" ' . $link_param . '>' . $icon . '</a>';
				break;
			case 'lostpassword':
				$menu .= '<a rel="nofollow" href="' . wp_lostpassword_url( $link ) . '" ' . $link_param . '>' . $icon
				         . '</a>';
				break;
		}
		$menu .= '</li>';
	}
	$menu .= '</ul>';
endif;
$menu .= '</div>';

