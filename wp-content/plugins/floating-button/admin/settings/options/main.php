<?php
/**
 * Main Settings param
 *
 * @package     Wow_Plugin
 * @copyright   Copyright (c) 2018, Dmytro Lobov
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */


$position_checked = ! empty( $param['position'] ) ? $param['position'] : 'flBtn-position-br';

switch ( $position_checked ) {
	case 'fl-right fl-bottom':
		$position_value = 'flBtn-position-br';
		break;
	case 'fl-left fl-bottom':
		$position_value = 'flBtn-position-bl';
		break;
	case 'fl-right fl-top':
		$position_value = 'flBtn-position-tr';
		break;
	case 'fl-left fl-top':
		$position_value = 'flBtn-position-tl';
		break;
	default:
		$position_value = $position_checked;
		break;
}


$position = array(
	'id'     => 'position',
	'name'   => 'param[position]',
	'type'   => 'select',
	'val'    => $position_value,
	'option' => array(
		'flBtn-position-br' => esc_attr__( 'bottom-right', 'floating-button' ),
	),
);

$position_help = array(
	'text' => esc_attr__( 'Specify floating button location on screen.', 'floating-button' ),
);

include_once( 'icons.php' );
$icons_new = array();
foreach ( $icons as $key => $value ) {
	$icons_new[ $value ] = $value;
}

$button_icon = array(
	'id'     => 'button_icon',
	'name'   => 'param[button_icon]',
	'class'  => 'icons',
	'type'   => 'select',
	'val'    => isset( $param['button_icon'] ) ? $param['button_icon'] : 'fas fa-hand-point-up',
	'option' => $icons_new,
	'func'   => '',
);

$button_icon_help = array(
	'text' => esc_attr__( 'Select the Icon for button', 'floating-button' ),
);

$shape_checked = ! empty( $param['shape'] ) ? $param['shape'] : 'flBtn-position-br';

switch ( $shape_checked ) {
	case 'fl-circle':
		$shape_value = 'flBtn-shape-circle';
		break;
	case 'fl-ellipse':
		$shape_value = 'flBtn-shape-ellipse';
		break;
	case 'fl-square':
		$shape_value = 'flBtn-shape-square';
		break;
	case 'fl-rsquare':
		$shape_value = 'flBtn-shape-rsquare';
		break;
	default:
		$shape_value = $shape_checked;
		break;
}

$shape = array(
	'id'     => 'shape',
	'name'   => 'param[shape]',
	'type'   => 'select',
	'val'    => $shape_value,
	'option' => array(
		'flBtn-shape-circle'  => esc_attr__( 'Circle', 'floating-button' ),
	),
	'func'   => '',
);

$size = array(
	'id'     => 'size',
	'name'   => 'param[size]',
	'type'   => 'select',
	'val'    => isset( $param['size'] ) ? $param['size'] : 'flBtn-size-medium',
	'option' => array(
		'flBtn-size-medium' => esc_attr__( 'Medium', 'floating-button' ),
	),
	'func'   => '',
);

$animation = array(
	'id'     => 'animation',
	'name'   => 'param[animation]',
	'type'   => 'select',
	'val'    => isset( $param['animation'] ) ? $param['animation'] : '',
	'option' => array(
		''                                    => esc_attr__( 'Fade', 'floating-button' ),
	),
	'func'   => '',
);

$item_tooltip_include = array(
	'name'  => 'param[item_tooltip_include]',
	'type'  => 'checkbox',
	'class' => 'tooltip-include',
	'val'   => isset( $param['item_tooltip_include'] ) ? $param['item_tooltip_include'] : 0,
	'func'  => 'itemtooltip(this);',
);

$item_tooltip = array(
	'name'  => 'param[item_tooltip]',
	'class' => 'item-tooltip',
	'type'  => 'text',
	'val'   => isset( $param['item_tooltip'] ) ? $param['item_tooltip'] : '',

);

$button_color = array(
	'id'   => 'button_color',
	'name' => 'param[button_color]',
	'type' => 'color',
	'val'  => isset( $param['button_color'] ) ? $param['button_color'] : '#009688',
);

$button_hcolor = array(
	'id'   => 'button_hcolor',
	'name' => 'param[button_hcolor]',
	'type' => 'color',
	'val'  => isset( $param['button_hcolor'] ) ? $param['button_hcolor'] : '#009688',
);

$icon_color = array(
	'id'   => 'icon_color',
	'name' => 'param[icon_color]',
	'type' => 'color',
	'val'  => isset( $param['icon_color'] ) ? $param['icon_color'] : '#fff',
);

$tooltip_background = array(
	'id'   => 'tooltip_background',
	'name' => 'param[tooltip_background]',
	'type' => 'color',
	'val'  => isset( $param['tooltip_background'] ) ? $param['tooltip_background'] : '#585858',
);

$tooltip_color = array(
	'id'   => 'tooltip_color',
	'name' => 'param[tooltip_color]',
	'type' => 'color',
	'val'  => isset( $param['tooltip_color'] ) ? $param['tooltip_color'] : '#fff',
);

$main_button_id = array(
	'name' => 'param[main_button_id]',
	'id'   => 'main_button_id',
	'type' => 'text',
	'val'  => isset( $param['main_button_id'] ) ? $param['main_button_id'] : '',
);

$main_button_id_help = array(
	'text' => esc_attr__( 'Set ID for element.', 'floating-button' ),
);

$main_button_class = array(
	'name' => 'param[main_button_class]',
	'id'   => 'main_button_class',
	'type' => 'text',
	'val'  => isset( $param['main_button_class'] ) ? $param['main_button_class'] : '',
);

$main_button_class_help = array(
	'title' => esc_attr__( 'Set Class for element.', 'floating-button' ),
	'ul'    => array(
		__( 'You may enter several classes separated by a space.', 'floating-button' ),
	),
);


// Button animation
$button_animation = array(
	'name'   => 'param[button_animation]',
	'type'   => 'select',
	'val'    => isset( $param['button_animation'] ) ? $param['button_animation'] : '',
	'option' => array(
		''                 => esc_attr__( 'None', 'floating-button' ),
	),
);

$button_animation_helper = array(
	'text' => esc_attr__( 'Animation for main button', 'floating-button' ),
);

$item_type  = array(
	'name'   => 'param[item_type]',
	'type'   => 'select',
	'class'  => 'item-type',
	'id'     => 'mainType',
	'val'    => isset( $param['item_type'] ) ? $param['item_type'] : '',
	'option' => array(
		'main'         => esc_attr__( 'Main Button', 'floating-button' ),
	),
	'func'   => 'itemtype(this);',
);

$item_type_help = array(
	'title' => esc_attr__( 'Types of the button which can be select', 'floating-button' ),
	'ul'    => array(
		__( '<strong>Main Button</strong> - button has some sub buttons', 'floating-button' ),
	),
);

$item_type_menu_help = array(
	'title' => esc_attr__( 'Types of the button which can be select', 'floating-button' ),
	'ul'    => array(
		__( '<strong>Link</strong> - insert any link', 'floating-button' ),
	),
);