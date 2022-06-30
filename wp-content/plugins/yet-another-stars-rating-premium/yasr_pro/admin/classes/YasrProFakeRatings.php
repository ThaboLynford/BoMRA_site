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


class YasrProFakeRatings {

    public function init() {
        //hook into yasr_add_content_bottom_topright_metabox
        add_action('yasr_add_content_bottom_topright_metabox', array($this, 'metaboxAction'), 99);

        //Add js constants to gutenberg
        add_filter('yasr_gutenberg_constants', array($this, 'gutenbergConstants'));

        //Both actions uses same function
        add_action('save_post', array($this, 'saveRatings'));
        add_action('wp_ajax_yasr_adds_fake_ratings', array($this, 'saveRatings'));
    }

    /**
     * @param $post_id
     *
     * Add nonce field and select to top right metabox
     *
     * Must be public
     *
     */
    public function metaboxAction ($post_id) {
        wp_nonce_field('yasr_pro_nonce_fake_ratings_action', 'yasr_pro_nonce_fake_ratings');
        ?>
        <hr />
        <div>
            <strong><?php _e('Add fake ratings', 'yet-another-stars-rating'); ?></strong>
            <p />
            <div>
                <?php _e('Number of votes', 'yet-another-stars-rating') ?>
                <br />
                <div>
                    <label for="yasr-pro-fake-number-of-votes">
                        <select name="yasr_pro_fake_number_of_votes" id="yasr-pro-fake-number-of-votes">
                            <option value="none" selected>0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="200">200</option>
                        </select>
                    </label>
                </div>
                <?php
                    _e('Rating:', 'yet-another-stars-rating');
                ?>
                <div>
                    <label for="yasr-pro-fake-ratings">
                        <select name="yasr_pro_fake_ratings" id="yasr-pro-fake-ratings">
                            <?php
                            for ($i=5; $i>0; $i--) {
                                if($i === 5) {
                                    $selected = 'selected';
                                } else {
                                    $selected = '';
                                }
                                echo "<option value='$i' $selected>$i</option>";
                            }
                            ?>
                        </select>
                    </label>
                    <p />
                    <div>
                        <?php _e(
                            'This will add ratings for yasr_visitor_votes shortcode.',
                            'yet-another-stars-rating')
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <?php

    }

    /**
     * @param $constants_array
     *
     * Add gutenberg constants
     *
     * @return array
     *
     */
    public function gutenbergConstants($constants_array) {
        $array_with_nonces = array(
            'yasr_pro_nonce_fake_ratings' => wp_create_nonce('yasr_pro_nonce_fake_ratings_action')
        );

        return $constants_array + $array_with_nonces;
    }

    /**
     * Save Ratings
     *
     * @param bool|string|int $post_id
     *
     * @return string|void
     */
    public function saveRatings($post_id) {
        if (!current_user_can(YASR_USER_CAPABILITY_EDIT_POST)) {
            return;
        }

        if (!isset($_POST['yasr_pro_nonce_fake_ratings'])) {
            return;
        }

        $is_ajax = false;

        //if !isset $post_id, and $_POST['post_id'] it is from ajax
        if(!$post_id && isset($_POST['post_id'])) {
            $post_id = (int)$_POST['post_id'];
            $is_ajax = true;
        }

        //if is ajax is still false, this must be a gutenberg page
        if($is_ajax === false && yasr_is_gutenberg_page()) {
            return;
        }

        if(isset($_POST['yasr_pro_fake_number_of_votes'], $_POST['yasr_pro_fake_ratings'])) {
            $number_of_votes = $_POST['yasr_pro_fake_number_of_votes'];
            $rating          = (int)$_POST['yasr_pro_fake_ratings'];

            $nonce  = $_POST['yasr_pro_nonce_fake_ratings'];

            if (!wp_verify_nonce($nonce, 'yasr_pro_nonce_fake_ratings_action')) {
                if($is_ajax === true) {
                    die('KO wrong nonce');
                }
                return;
            }

            if(!is_numeric($number_of_votes)) {
                if($is_ajax === true) {
                    die('Not numeric');
                }
                return;
            }

            if (($rating > 0 && $rating < 6) && ($number_of_votes >= 1 && $number_of_votes < 201) ) {
                global $wpdb;

                $transient_name = 'yasr_visitor_votes_' . $post_id;

                delete_transient( $transient_name );

                for ($i=1; $i<=$number_of_votes; $i++) {
                    $result_insert_log = $wpdb->replace (
                        YASR_LOG_TABLE,
                        array (
                            'post_id' => $post_id,
                            'user_id' => 0,
                            'vote' => $rating,
                            'date' => date('Y-m-d H:i:s'),
                            'ip' => 'x.x.x.x'
                        ),
                        array ('%d', '%d', '%d', '%s', '%s')
                    );

                    if($result_insert_log === false && $is_ajax === true) {
                        die('KO can\'t save');
                    }
                }

                if($is_ajax === true) {
                    die('OK');
                }

            } else {
                if($is_ajax === true) {
                    die('KO Wrong rating or number of votes');
                }
            }

        }

        if($is_ajax === true) {
            die('KO no post values sets');
        }

    }

}