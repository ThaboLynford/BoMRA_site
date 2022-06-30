<?php
/**
 * Main Settings
 *
 * @package     Wow_Plugin
 * @copyright   Copyright (c) 2018, Dmytro Lobov
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
include_once( 'options/main.php' );

?>

<div class="panel">
    <div class="panel-heading">
        <div class="level-item icon-select"></div>
        <div class="level-item">
            <span class="item-label-text">
                <?php $item_header = ! empty( $param['item_tooltip'] ) ? $param['item_tooltip'] : '(' . esc_attr__( 'no label', 'floating-button' ) . ')';
                echo esc_html( $item_header ); ?>
            </span>
        </div>
        <div class="level-item element-type"></div>
    </div>
    <div class="toogle-content">
        <div class="panel-block">
            <div class="field">
                <label class="label is-small">
					<?php esc_attr_e( 'Label Text', 'floating-button' ); ?>
                </label>
				<?php self::option( $item_tooltip ); ?>
            </div>
        </div>
        <label class="panel-block checkTooltip">
			<?php self::option( $item_tooltip_include ); ?><?php esc_attr_e( 'Enable Tooltip', 'floating-button' ); ?>
        </label>
        <p class="panel-tabs">
            <a class="is-active" data-tab="1"><?php esc_attr_e( "Type", 'floating-button' ); ?></a>
            <a data-tab="2"><?php esc_attr_e( "Icons", 'floating-button' ); ?></a>
            <a data-tab="3"><?php esc_attr_e( "Style", 'floating-button' ); ?></a>
            <a data-tab="4"><?php esc_attr_e( "Attributes", 'floating-button' ); ?></a>
        </p>
        <div data-tab-content="1" class="tabs-content">
            <div class="panel-block field">
                <label class="label">
					<?php esc_attr_e( 'Button type', 'floating-button' ); ?><?php self::tooltip( $item_type_help ); ?>

                </label>
				<?php self::option( $item_type ); ?>
            </div>
        </div>
        <div data-tab-content="2" class="tabs-content is-hidden">
            <div class="panel-block icon-default">
                <div class="field">
                    <label class="label">
						<?php esc_attr_e( 'Main Icon', 'floating-button' ); ?>
                    </label>
					<?php self::option( $button_icon ); ?>
                </div>
            </div>
        </div>
        <div data-tab-content="3" class="tabs-content is-hidden">

            <div class="panel-block">
                <div class="columns is-multiline">
                    <div class="field column is-4">
                        <label class="label">
							<?php esc_attr_e( 'Position', 'floating-button' ); ?><?php echo self::tooltip( $position_help ); ?>
                        </label>
						<?php self::option( $position ); ?>
                    </div>
                    <div class="field column is-4">
                        <label class="label">
							<?php esc_attr_e( 'Button shape', 'floating-button' ); ?><?php echo self::tooltip( $position_help ); ?>
                        </label>
						<?php self::option( $shape ); ?>
                    </div>
                    <div class="field column is-4">
                        <label class="label">
							<?php esc_attr_e( 'Size', 'floating-button' ); ?>
                        </label>
						<?php self::option( $size ); ?>
                    </div>
                    <div class="field column is-4">
                        <label class="label">
							<?php esc_attr_e( 'Button animation', 'floating-button' ); ?><?php echo self::tooltip( $button_animation_helper ); ?>
                        </label>
						<?php self::option( $button_animation ); ?>
                    </div>
                    <div class="field column is-4">
                        <label class="label">
							<?php esc_attr_e( 'Sub-buttons Animation', 'floating-button' ); ?>
                        </label>
						<?php self::option( $animation ); ?>
                    </div>
                </div>
            </div>

            <div class="panel-block">
                <div class="columns is-multiline">

                    <div class="field column is-4">
                        <label class="label">
							<?php esc_attr_e( 'Button color', 'floating-button' ); ?>
                        </label>
						<?php self::option( $button_color ); ?>
                    </div>

                    <div class="field column is-4">
                        <label class="label">
							<?php esc_attr_e( 'Button hover color', 'floating-button' ); ?>
                        </label>
						<?php self::option( $button_hcolor ); ?>
                    </div>

                    <div class="field column is-4">
                        <label class="label">
							<?php esc_attr_e( 'Icon color', 'floating-button' ); ?>
                        </label>
						<?php self::option( $icon_color ); ?>
                    </div>

                    <div class="field column is-4">
                        <label class="label">
							<?php esc_attr_e( 'Tooltip background', 'floating-button' ); ?>
                        </label>
						<?php self::option( $tooltip_background ); ?>
                    </div>

                    <div class="field column is-4">
                        <label class="label">
							<?php esc_attr_e( 'Tooltip color', 'floating-button' ); ?>
                        </label>
						<?php self::option( $tooltip_color ); ?>
                    </div>

                </div>
            </div>


        </div>
        <div data-tab-content="4" class="tabs-content is-hidden">
            <div class="panel-block">
                <div class="field">
                    <label class="label">
						<?php esc_attr_e( 'Class for element', 'floating-button' ); ?><?php self::tooltip( $main_button_class_help ); ?>
                    </label>
					<?php self::option( $main_button_class ); ?>
                </div>
            </div>
        </div>
    </div>
</div>

