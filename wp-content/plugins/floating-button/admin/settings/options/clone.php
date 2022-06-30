<?php
/**
 * Clone Elements Settings
 *
 * @package     Wow_Plugin
 * @copyright   Copyright (c) 2018, Dmytro Lobov
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Elements for clone Menu 1
$menu_1_item_icon            = array(
	'name'   => 'param[menu_1][item_icon][]',
	'class'  => 'icons',
	'type'   => 'select',
	'val'    => 'fas fa-hand-point-up',
	'option' => $icons_new,
);
$menu_1_item_tooltip_include = array(
	'name'  => 'param[menu_1][item_tooltip_include][]',
	'class' => 'tooltip-include',
	'type'  => 'checkbox',
	'val'   => '0',
	'func'  => 'itemtooltip(this);checkboxchecked(this);',
);
$menu_1_item_tooltip         = array(
	'name'  => 'param[menu_1][item_tooltip][]',
	'class' => 'item-tooltip',
	'type'  => 'text',
	'val'   => '',
);

$menu_1_button_color         = array(
	'name' => 'param[menu_1][button_color][]',
	'type' => 'color',
	'val'  => '#009688',
);
$menu_1_button_hcolor        = array(
	'name' => 'param[menu_1][button_hcolor][]',
	'type' => 'color',
	'val'  => '#009688',
);
$menu_1_icon_color           = array(
	'name' => 'param[menu_1][icon_color][]',
	'type' => 'color',
	'val'  => '#ffffff',
);
$menu_1_item_type            = array(
	'name'   => 'param[menu_1][item_type][]',
	'type'   => 'select',
	'val'    => 'link',
	'class'  => 'item-type',
	'option' => array(
		'link'         => esc_attr__( 'Link', 'floating-button' ),
		'login'        => esc_attr__( 'Login', 'floating-button' ),
		'logout'       => esc_attr__( 'Logout', 'floating-button' ),
		'register'     => esc_attr__( 'Register', 'floating-button' ),
		'lostpassword' => esc_attr__( 'Lostpassword', 'floating-button' ),
	),
	'func'   => 'itemtype(this);',
);
$menu_1_item_link            = array(
	'name' => 'param[menu_1][item_link][]',
	'type' => 'text',
	'val'  => '',
);



$menu_1_button_id = array(
	'name' => 'param[menu_1][button_id][]',
	'type' => 'text',
	'val'  => '',
);

$menu_1_button_id_help = array(
	'text' => esc_attr__( 'Set ID for element.', 'floating-button' ),
);

$menu_1_button_class = array(
	'name' => 'param[menu_1][button_class][]',
	'type' => 'text',
	'val'  => '',
);

$menu_1_button_class_help = array(
	'title' => esc_attr__( 'Set Class for element.', 'floating-button' ),
	'ul'    => array(
		__( 'You may enter several classes separated by a space.', 'floating-button' ),
	),
);

$menu_1_link_rel = array(
	'name' => 'param[menu_1][link_rel][]',
	'type' => 'text',
	'val'  => '',
);

// Elements for clone Menu 2
$menu_2_item_icon            = array(
	'name'   => 'param[menu_2][item_icon][]',
	'class'  => 'icons',
	'type'   => 'select',
	'val'    => 'fas fa-hand-point-up',
	'option' => $icons_new,
);

$menu_2_item_tooltip_include = array(
	'name'  => 'param[menu_2][item_tooltip_include][]',
	'class' => 'tooltip-include',
	'type'  => 'checkbox',
	'val'   => '0',
	'func'  => 'itemtooltip(this);checkboxchecked(this);',
);
$menu_2_item_tooltip         = array(
	'name'  => 'param[menu_2][item_tooltip][]',
	'class' => 'item-tooltip',
	'type'  => 'text',
	'val'   => '',

);


$menu_2_button_color         = array(
	'name' => 'param[menu_2][button_color][]',
	'type' => 'color',
	'val'  => '#009688',
);
$menu_2_button_hcolor        = array(
	'name' => 'param[menu_2][button_hcolor][]',
	'type' => 'color',
	'val'  => '#009688',
);
$menu_2_icon_color           = array(
	'name' => 'param[menu_2][icon_color][]',
	'type' => 'color',
	'val'  => '#ffffff',
);
$menu_2_item_type            = array(
	'name'   => 'param[menu_2][item_type][]',
	'type'   => 'select',
	'class'  => 'item-type',
	'val'    => 'link',
	'option' => array(
		'link'         => esc_attr__( 'Link', 'floating-button' ),
		'login'        => esc_attr__( 'Login', 'floating-button' ),
		'logout'       => esc_attr__( 'Logout', 'floating-button' ),
		'register'     => esc_attr__( 'Register', 'floating-button' ),
		'lostpassword' => esc_attr__( 'Lostpassword', 'floating-button' ),
	),
	'func'   => 'itemtype(this);',
);
$menu_2_item_link            = array(
	'name' => 'param[menu_2][item_link][]',
	'type' => 'text',
	'val'  => '',
);

$menu_2_button_id = array(
	'name' => 'param[menu_2][button_id][]',
	'type' => 'text',
	'val'  => '',
);

$menu_2_button_id_help = array(
	'text' => esc_attr__( 'Set ID for element.', 'floating-button' ),
);

$menu_2_button_class = array(
	'name' => 'param[menu_2][button_class][]',
	'type' => 'text',
	'val'  => '',
);

$menu_2_button_class_help = array(
	'title' => esc_attr__( 'Set Class for element.', 'floating-button' ),
	'ul'    => array(
		__( 'You may enter several classes separated by a space.', 'floating-button' ),
	),
);

$menu_2_link_rel = array(
	'name' => 'param[menu_2][link_rel][]',
	'type' => 'text',
	'val'  => '',
);