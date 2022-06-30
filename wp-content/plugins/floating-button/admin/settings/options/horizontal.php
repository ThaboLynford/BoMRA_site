<?php
/**
 * Horizontal Buttons Settings
 *
 * @package     Wow_Plugin
 * @copyright   Copyright (c) 2018, Dmytro Lobov
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

$count_ii = ( ! empty( $param['menu_2']['item_type'] ) ) ? count( $param['menu_2']['item_type'] ) : '0';
if ( $count_ii > 0 ) {
	for ( $i = 0; $i < $count_ii; $i ++ ) {
		$item_icon_[ $i ]            = array(
			'name'   => 'param[menu_2][item_icon][]',
			'class'  => 'icons',
			'type'   => 'select',
			'val'    => isset( $param['menu_2']['item_icon'][ $i ] ) ? $param['menu_2']['item_icon'][ $i ]
				: 'fas fa-hand-point-up',
			'option' => $icons_new,
		);
		$item_tooltip_include_[ $i ] = array(
			'name'  => 'param[menu_2][item_tooltip_include][]',
			'type'  => 'checkbox',
			'class' => 'tooltip-include',
			'val'   => isset( $param['menu_2']['item_tooltip_include'][ $i ] )
				? $param['menu_2']['item_tooltip_include'][ $i ] : 0,
			'func'  => 'itemtooltip(this);',
		);
		$item_tooltip_[ $i ]         = array(
			'name'  => 'param[menu_2][item_tooltip][]',
			'class' => 'item-tooltip',
			'type'  => 'text',
			'val'   => isset( $param['menu_2']['item_tooltip'][ $i ] ) ? $param['menu_2']['item_tooltip'][ $i ] : '',

		);
		$button_color_[ $i ]         = array(
			'name' => 'param[menu_2][button_color][]',
			'type' => 'color',
			'val'  => isset( $param['menu_2']['button_color'][ $i ] ) ? $param['menu_2']['button_color'][ $i ]
				: '#009688',
		);
		$button_hcolor_[ $i ]        = array(
			'name' => 'param[menu_2][button_hcolor][]',
			'type' => 'color',
			'val'  => isset( $param['menu_2']['button_hcolor'][ $i ] ) ? $param['menu_2']['button_hcolor'][ $i ]
				: '#009688',
		);
		$icon_color_[ $i ]           = array(
			'name' => 'param[menu_2][icon_color][]',
			'type' => 'color',
			'val'  => isset( $param['menu_2']['icon_color'][ $i ] ) ? $param['menu_2']['icon_color'][ $i ] : '#ffffff',
		);
		$item_type_[ $i ]            = array(
			'name'   => 'param[menu_2][item_type][]',
			'type'   => 'select',
			'class'  => 'item-type',
			'val'    => isset( $param['menu_2']['item_type'][ $i ] ) ? $param['menu_2']['item_type'][ $i ] : '',
			'option' => array(
				'link'         => esc_attr__( 'Link', 'floating-button' ),
				'login'        => esc_attr__( 'Login', 'floating-button' ),
				'logout'       => esc_attr__( 'Logout', 'floating-button' ),
				'register'     => esc_attr__( 'Register', 'floating-button' ),
				'lostpassword' => esc_attr__( 'Lostpassword', 'floating-button' ),
			),
			'func'   => 'itemtype(this);',
		);


		$item_link_[ $i ] = array(
			'name'   => 'param[menu_2][item_link][]',
			'type'   => 'text',
			'val'    => isset( $param['menu_2']['item_link'][ $i ] ) ? $param['menu_2']['item_link'][ $i ] : '',
			'option' => array(
				'placeholder' => esc_attr__( 'Enter URL', 'floating-button' ),
			),
		);

		$button_id_[ $i ]  = array(
			'name' => 'param[menu_2][button_id][]',
			'type' => 'text',
			'val'  => isset( $param['menu_2']['button_id'][ $i ] ) ? $param['menu_2']['button_id'][ $i ] : '',
		);

		$button_class_[ $i ] = array(
			'name' => 'param[menu_2][button_class][]',
			'type' => 'text',
			'val'  => isset( $param['menu_2']['button_class'][ $i ] ) ? $param['menu_2']['button_class'][ $i ] : '',
		);

		$link_rel_[ $i ] = array(
			'name' => 'param[menu_2][link_rel][]',
			'type' => 'text',
			'val'  => isset( $param['menu_2']['link_rel'][ $i ] ) ? $param['menu_2']['link_rel'][ $i ] : '',
		);
	}

}