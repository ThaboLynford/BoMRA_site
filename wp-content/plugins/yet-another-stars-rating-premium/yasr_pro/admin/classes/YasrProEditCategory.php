<?php
/*

Copyright 2014 Dario Curvino (email : d.curvino@tiscali.it)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>
*/

if (!defined('ABSPATH')) {
    exit('You\'re not allowed to see this page');
} // Exit if accessed directly


class YasrProEditCategory {
    public static function init() {
        //filter the edit category form
        add_action('category_edit_form_fields', array('YasrProEditCategory', 'categoryEditFormFields'));

        //hook function on save
        add_action('edited_category', array('YasrProEditCategory', 'updatePostAndTermMeta'), 10, 2);
    }

    /**
     * Add new rows in the category edit page
     *
     * @param $term
     */
    public static function categoryEditFormFields($term) {
        ?>
        <tr class="form-field term-name-wrap">
            <th scope="row">
                <label for="yasr-default-itemtype-category">
                    <?php _e( 'Select default itemType', 'yasr-pro' ) ?>
                </label>
                <span class="dashicons dashicons-unlock"></span>
            </th>
            <td>
                <?php yasr_select_itemtype('yasr-pro-select-itemtype-category', (int)$term->term_id ); ?>
                <p></p>
                <label for="yasr-pro-checkbox-itemtype-category" class="yasr-indented-answer">
                    <input type="checkbox"
                           name="yasr-pro-checkbox-itemtype-category"
                           id="yasr-pro-checkbox-itemtype-category">
                    <span class="description">
                    <?php _e('Check to update YASR itemType', 'yasr-pro') ?>
                </span>
                </label>
                <p class="description">
                    <?php _e(
                        'This will overwrite YASR itemType in all existing posts or pages for this category ',
                        'yasr-pro')
                    ?>
                </p>
            </td>
        </tr >
        <?php
    }

    /**
     * Hook when a category is updated
     *
     * https://developer.wordpress.org/reference/hooks/edited_taxonomy/
     *
     * @param int $term_id
     * @return void;
     *
     * In this case, $term_id is category_id
     */
    public static function updatePostAndTermMeta($term_id) {

        //If checkbox is not selected, return
        if(!isset($_POST['yasr-pro-checkbox-itemtype-category'])) {
            return;
        }

        $supported_itemTypes = json_decode(YASR_SUPPORTED_SCHEMA_TYPES, true);
        $selected_itemType = $_POST['yasr-review-type'];

        //if the value of yasr-review-type is not in $supported_itemTypes, return
        if (!in_array( $selected_itemType, $supported_itemTypes, true ) ) {
            return;
        }

        //Select argument to retrive post
        $args = array(
            'numberposts' => -1, // -1 returns all posts
            'category'    => $term_id, //$term_id is the category id
            'fields'      => 'ids', // only get post IDs.
        );

        $array_posts_id = get_posts($args);

        foreach ($array_posts_id as $post_id) {
            update_post_meta($post_id, 'yasr_review_type', $selected_itemType);
        }

        update_term_meta($term_id, 'yasr_review_type', $selected_itemType);

    }

}
