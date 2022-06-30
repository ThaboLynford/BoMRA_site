<?php
/**
 * Elements for clone
 *
 * @package     Wow_Plugin
 * @copyright   Copyright (c) 2018, Dmytro Lobov
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

include_once( 'options/clone.php' );
?>
<template id="clone-1">
    <div class="panel">
        <div class="panel-heading">
            <div class="level-item icon-select" style="color: #ffffff; background-color: #009688;">
                <i class="fas fa-hand-point-up"></i>
            </div>
            <div class="level-item">
                <span class="item-label-text">(<?php esc_attr_e( 'no label', 'floating-button' ); ?>)</span>
            </div>
            <div class="level-item element-type">Link</div>
            <div class="level-item toogle-element">
                <span class="dashicons dashicons-arrow-down"></span>
                <span class="dashicons dashicons-arrow-up is-hidden"></span>
            </div>
        </div>

        <div class="toogle-content is-hidden">

            <div class="panel-block">
                <div class="field">
                    <label class="label is-small">
						<?php esc_attr_e( 'Label Text', 'floating-button' ); ?>
                    </label>
					<?php self::option( $menu_1_item_tooltip ); ?>
                </div>
            </div>
            <label class="panel-block checkTooltip">
				<?php self::option( $menu_1_item_tooltip_include ); ?><?php esc_attr_e( 'Enable Tooltip', 'floating-button' ); ?>
            </label>

            <p class="panel-tabs">
                <a class="is-active" data-tab="1"><?php esc_attr_e( "Type", 'floating-button' ); ?></a>
                <a data-tab="2"><?php esc_attr_e( "Icon", 'floating-button' ); ?></a>
                <a data-tab="3"><?php esc_attr_e( "Style", 'floating-button' ); ?></a>
                <a data-tab="4"><?php esc_attr_e( "Attributes", 'floating-button' ); ?></a>
            </p>

            <div data-tab-content="1" class="tabs-content">
                <div class="panel-block">
                    <div class="field">
                        <label class="label">
							<?php esc_attr_e( 'Item type', 'floating-button' ); ?><?php self::tooltip( $item_type_menu_help ); ?>

                        </label>
						<?php self::option( $menu_1_item_type ); ?>
                    </div>
                    <div class="field item-link">
                        <label class="label item-link-text">
							<?php esc_attr_e( 'Link', 'floating-button' ); ?>
                        </label>
						<?php self::option( $menu_1_item_link ); ?>
                    </div>
                </div>
            </div>
            <div data-tab-content="2" class="tabs-content is-hidden">
                <div class="panel-block icon-default">
                    <div class="field">
                        <label class="label">
							<?php esc_attr_e( 'Icon', 'floating-button' ); ?>
                        </label>
						<?php self::option( $menu_1_item_icon ); ?>
                    </div>
                </div>
            </div>
            <div data-tab-content="3" class="tabs-content is-hidden">
                <div class="panel-block">
                    <div class="field">
                        <label class="label">
							<?php esc_attr_e( 'Button color', 'floating-button' ); ?>
                        </label>
						<?php self::option( $menu_1_button_color ); ?>
                    </div>
                    <div class="field">
                        <label class="label">
							<?php esc_attr_e( 'Button hover color', 'floating-button' ); ?>
                        </label>
						<?php self::option( $menu_1_button_hcolor ); ?>
                    </div>
                    <div class="field">
                        <label class="label">
							<?php esc_attr_e( 'Icon color', 'floating-button' ); ?>
                        </label>
						<?php self::option( $menu_1_icon_color ); ?>
                    </div>
                </div>
            </div>
            <div data-tab-content="4" class="tabs-content is-hidden">
                <div class="panel-block">
                    <div class="field">
                        <label class="label">
							<?php esc_attr_e( 'ID for element', 'floating-button' ); ?>
                        </label>
						<?php self::option( $menu_1_button_id ); ?>
                    </div>
                </div>
                <div class="panel-block">
                    <div class="field">
                        <label class="label">
							<?php esc_attr_e( 'Class for element', 'floating-button' ); ?>
                        </label>
						<?php self::option( $menu_1_button_class ); ?>
                    </div>
                </div>
                <div class="panel-block">
                    <div class="field">
                        <label class="label">
				            <?php esc_attr_e( 'Attribute: rel', 'floating-button' ); ?>
                        </label>
			            <?php self::option( $menu_1_link_rel ); ?>
                    </div>
                </div>
            </div>
            <div class="panel-block actions">
                <a class="item-delete"><?php esc_attr_e( 'Remove', 'floating-button' ); ?></a>
            </div>
        </div>
    </div>
</template>

<template id="clone-2">
    <div class="panel">
        <div class="panel-heading">
            <div class="level-item icon-select" style="color: #ffffff; background-color: #009688;">
                <i class="fas fa-hand-point-up"></i>
            </div>
            <div class="level-item">
                <span class="item-label-text">(<?php esc_attr_e( 'no label', 'floating-button' ); ?>)</span>
            </div>
            <div class="level-item element-type">Link</div>
            <div class="level-item toogle-element">
                <span class="dashicons dashicons-arrow-down"></span>
                <span class="dashicons dashicons-arrow-up is-hidden"></span>
            </div>
        </div>

        <div class="toogle-content is-hidden">

            <div class="panel-block">
                <div class="field">
                    <label class="label is-small">
						<?php esc_attr_e( 'Label Text', 'floating-button' ); ?>
                    </label>
					<?php self::option( $menu_2_item_tooltip ); ?>
                </div>
            </div>
            <label class="panel-block checkTooltip">
				<?php self::option( $menu_2_item_tooltip_include ); ?><?php esc_attr_e( 'Enable Tooltip', 'floating-button' ); ?>
            </label>

            <p class="panel-tabs">
                <a class="is-active" data-tab="1"><?php esc_attr_e( "Type", 'floating-button' ); ?></a>
                <a data-tab="2"><?php esc_attr_e( "Icon", 'floating-button' ); ?></a>
                <a data-tab="3"><?php esc_attr_e( "Style", 'floating-button' ); ?></a>
                <a data-tab="4"><?php esc_attr_e( "Attributes", 'floating-button' ); ?></a>
            </p>

            <div data-tab-content="1" class="tabs-content">
                <div class="panel-block">
                    <div class="field">
                        <label class="label">
							<?php esc_attr_e( 'Item type', 'floating-button' ); ?><?php self::tooltip( $item_type_menu_help ); ?>

                        </label>
						<?php self::option( $menu_2_item_type ); ?>
                    </div>
                    <div class="field item-link">
                        <label class="label item-link-text">
							<?php esc_attr_e( 'Link', 'floating-button' ); ?>
                        </label>
						<?php self::option( $menu_2_item_link ); ?>
                    </div>
                </div>

            </div>
            <div data-tab-content="2" class="tabs-content is-hidden">
                <div class="panel-block icon-default">
                    <div class="field">
                        <label class="label">
							<?php esc_attr_e( 'Icon', 'floating-button' ); ?>
                        </label>
						<?php self::option( $menu_2_item_icon ); ?>
                    </div>
                </div>
            </div>
            <div data-tab-content="3" class="tabs-content is-hidden">
                <div class="panel-block">
                    <div class="field">
                        <label class="label">
							<?php esc_attr_e( 'Button color', 'floating-button' ); ?>
                        </label>
						<?php self::option( $menu_2_button_color ); ?>
                    </div>
                    <div class="field">
                        <label class="label">
							<?php esc_attr_e( 'Button hover color', 'floating-button' ); ?>
                        </label>
						<?php self::option( $menu_2_button_hcolor ); ?>
                    </div>
                    <div class="field">
                        <label class="label">
							<?php esc_attr_e( 'Icon color', 'floating-button' ); ?>
                        </label>
						<?php self::option( $menu_2_icon_color ); ?>
                    </div>
                </div>
            </div>
            <div data-tab-content="4" class="tabs-content is-hidden">
                <div class="panel-block">
                    <div class="field">
                        <label class="label">
							<?php esc_attr_e( 'ID for element', 'floating-button' ); ?>
                        </label>
						<?php self::option( $menu_2_button_id ); ?>
                    </div>
                </div>
                <div class="panel-block">
                    <div class="field">
                        <label class="label">
							<?php esc_attr_e( 'Class for element', 'floating-button' ); ?>
                        </label>
						<?php self::option( $menu_2_button_class ); ?>
                    </div>
                </div>
                <div class="panel-block">
                    <div class="field">
                        <label class="label">
				            <?php esc_attr_e( 'Attribute: rel', 'floating-button' ); ?>
                        </label>
			            <?php self::option( $menu_2_link_rel ); ?>
                    </div>
                </div>
            </div>
            <div class="panel-block actions">
                <a class="item-delete"><?php esc_attr_e( 'Remove', 'floating-button' ); ?></a>
            </div>
        </div>
    </div>
</template>