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

class YasrProUrAdmin {
    public function init() {
        ///// While editing post or page
        // Function to draw the "Enable review in comments" in the metabox
        add_action('yasr_add_content_bottom_topright_metabox', array($this, 'urMetaboxClassicEditor'));
        //When post is saved, check if enable or disable reviews in comments
        add_action('yasr_on_save_post', array($this, 'urSavePostMeta'));
        //Adds pro tab on tinymce popup
        add_action('yasr_add_tabs_on_tinypopupform', array($this, 'urTinypopupTabs'));
        //Function to draw the content of the pro tinypopup content
        add_action('yasr_add_content_on_tinypopupform', array($this, 'urTinypopupContent'));

        /////Settings
        //Simply add the tabs on settings page
        add_action('yasr_add_settings_tab', array($this, 'urSettingsTab'));
        //Add new page
        add_action('yasr_settings_tab_content', array($this, 'urSettingsPage'));
        //Hook the "Select Ranking" in the select inside "Settings -> Rankings"
        add_filter('yasr_settings_select_ranking', array($this, 'urAddRankingOnSelect'));

        //Comments Dashboard
        add_filter('comment_text', array($this, 'displayReviewsCommentDashboard'), 999);
        add_action('deleted_comment', array($this, 'deleteReviewsCommentMeta'));

        /////Ajax action, must be here even if works only in front end
        //Used in comment form when updating the rating
        add_action('wp_ajax_yasr_pro_update_comment_rating',  array($this, 'urUpdateReviewRating'));

        //Used in comment form when updating the title
        add_action('wp_ajax_yasr_pro_update_comment_title',  array($this, 'urUpdateReviewTitle'));
    }

    /**
     * @author Dario Curvino <@dudo>
     * @since  2.6.8 refactored as method
     *
     * @param $post_id
     */
    public function urMetaboxClassicEditor($post_id) {
        wp_nonce_field( 'yasr_nonce_comment_review_enabled_action', 'yasr_nonce_comment_review_enabled');

        $yasr_comment_rating_data_obj = new YasrCommentsRatingData();
        $comment_review_enabled = (bool)$yasr_comment_rating_data_obj->commentReviewEnabled();
        ?>
        <hr />
        <p>
            <?php
            if ($comment_review_enabled === true ) {
                _e("Reviews in comments for this post / page are ENABLED", 'yasr-pro');
            } else {
                _e("Reviews in comments for this post / page are DISABLED", 'yasr-pro');
            }
            ?>
        </p>

        <div id="yasr-toprightmetabox-reviews-in-comments-switcher">
            <div class="yasr-onoffswitch-big" id="yasr-switcher-enable-reviews-in-comments">
                <input type="checkbox" name="yasr_pro_review_in_comments" class="yasr-onoffswitch-checkbox" value="yes"
                       id="yasr-pro-comments-enabled-yes" <?php if ($comment_review_enabled === true) {
                    echo " checked='checked' ";
                } ?> >
                <label class="yasr-onoffswitch-label" for="yasr-pro-comments-enabled-yes">
                    <span class="yasr-onoffswitch-inner yasr-onoffswitch-onoff-inner"></span>
                    <span class="yasr-onoffswitch-switch"></span>
                </label>
            </div>
        </div>
        <br/>
        <div id="yasr-pro-reviews-comments-enabled-message">
        </div>
        <?php

    }

    /**
     * @author Dario Curvino <@dudo>
     * @since  2.6.8 refactored as method
     *
     * @param $post_id
     */
    public function urSavePostMeta($post_id) {
        //this mean there we're not in the classic editor
        if (!isset($_POST['yasr_nonce_comment_review_enabled'])) {
            return;
        }

        $nonce = $_POST['yasr_nonce_comment_review_enabled'];

        if (!wp_verify_nonce($nonce, 'yasr_nonce_comment_review_enabled_action')) {
            return;
        }

        if (isset($_POST['yasr_pro_review_in_comments'])) {
            $post_data = 1;
        } else {
            $post_data = 0;
        }

        //If by default, user reviews in comment is no, and post data is no, do not save/delete useless data
        if(YASR_PRO_UR_COMMENT_AUTO_INSERT === 'no' && $post_data === 0) {
            delete_post_meta($post_id, 'yasr_pro_reviews_in_comment_enabled');
            return;
        }

        //same but if everything is enabled
        if(YASR_PRO_UR_COMMENT_AUTO_INSERT === 'yes' && $post_data === 1) {
            delete_post_meta($post_id, 'yasr_pro_reviews_in_comment_enabled');
            return;
        }

        //insert post meta
        update_post_meta($post_id, 'yasr_pro_reviews_in_comment_enabled', $post_data);

    }

    /**
     * @author Dario Curvino <@dudo>
     * @since  2.6.8 refactored as method
     **/
    public function urTinypopupTabs() {
        ?>
        <a href="#" id="yasr-pro-link-tab-comments"
           class="nav-tab yasr-nav-tab"><?php _e("User Reviews", 'yasr-pro'); ?></a>
        <?php
    }

    /**
     * @author Dario Curvino <@dudo>
     * @since  2.6.8 refactored as method
     *
     */
    public function urTinypopupContent() {
        ?>
        <div id="yasr-pro-content-comments" class="yasr-content-tab-tinymce" style="display:none">
            <table id="yasr-table-tiny-popup-comments" class="form-table">
                <tr>
                    <th>
                        <label for="yasr-pro-rating-stats-progressbars"><?php _e("Insert Progress Bars", "yasr-pro"); ?></label>
                    </th>
                    <td><input type="button" class="button-primary"
                               name="yasr-pro-rating-stats-progressbars"
                               id="yasr-pro-rating-stats-progressbars"
                               value="<?php _e("Insert Progress Bars stats", "yasr-pro") ?>"
                        />
                        <br/>
                        <small>
                            <?php _e("Insert progress bars statistics for review in comments", "yasr-pro"); ?>
                        </small>
                    </td>
                </tr>

                <tr>
                    <th><label for="yasr-pro-rating-stats-average"><?php _e("Insert Average Rating", "yasr-pro"); ?></label>
                    </th>
                    <td>
                        <input type="button"
                               class="button-primary"
                               name="yasr-pro-rating-stats-average"
                               id="yasr-pro-rating-stats-average"
                               value="<?php _e("Insert Stars Average", "yasr-pro") ?>"
                        /><br/>
                        <small>
                            <?php _e("Insert the average (in stars) of all ratings in comments", "yasr-pro"); ?>
                        </small>

                        <div id="yasr-pro-tinymce-choose-size-comments-stars">
                            <small>
                                <?php _e("Choose Size", 'yet-another-stars-rating'); ?>
                            </small>
                            <div class="yasr-tinymce-button-size">
                                <?php
                                echo YasrEditorHooks::yasr_tinymce_return_button('yasr_pro_average_comments_ratings');
                                ?>
                            </div>
                        </div>

                    </td>
                </tr>

                <tr>
                    <th>
                        <label for="yasr-pro-rating-stats-progressbars"><?php _e("Ranking from reviews", "yasr-pro"); ?></label>
                    </th>
                    <td><input type="button"
                               class="button-primary"
                               name="yasr-pro-rankings-from-review"
                               id="yasr-pro-rankings-from-review"
                               value="<?php _e("Insert Ranking From Reviews", "yasr-pro") ?>"/><br/>
                        <small><?php _e("Show up a ranking build from the reviews", "yasr-pro"); ?></small>
                    </td>
                </tr>

            </table>
        </div>

        <script type="text/javascript">

            jQuery(document).ready(function () {

                //Tinymce
                jQuery('#yasr-pro-link-tab-comments').on("click", function () {

                    jQuery('.yasr-nav-tab').removeClass('nav-tab-active');
                    jQuery('#yasr-pro-link-tab-comments').addClass('nav-tab-active');

                    jQuery('.yasr-content-tab-tinymce').hide();
                    jQuery('#yasr-pro-content-comments').show();

                });

                //Add shortcode for comments review statistics. This is pro only
                //This is for the progressbars
                jQuery('#yasr-pro-rating-stats-progressbars').on("click", function () {
                    var shortcode = '[yasr_pro_comments_ratings_progressbars]';

                    if (tinyMCE.activeEditor == null) {

                        //this is for tinymce used in text mode
                        jQuery("#content").append(shortcode);

                    } else {

                        // inserts the shortcode into the active editor
                        tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);

                    }

                    // closes thickbox
                    tb_remove();
                });

                //And this is for the average
                jQuery('#yasr-pro-rating-stats-average').on("click", function () {
                    jQuery('#yasr-pro-tinymce-choose-size-comments-stars').toggle('slow');
                });

                //Tab this cause is inside a div

                //Add shortcode for comments review statistics. This is pro only
                //This is for the progressbars
                jQuery('#yasr-pro-rankings-from-review').on("click", function () {
                    var shortcode = '[yasr_pro_rankings_from_comments_reviews]';

                    if (tinyMCE.activeEditor == null) {
                        //this is for tinymce used in text mode
                        jQuery("#content").append(shortcode);

                    } else {
                        // inserts the shortcode into the active editor
                        tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
                    }

                    // closes thickbox
                    tb_remove();
                });

            });

        </script>

        <?php

    }

    /**
     * Callback for yasr_add_settings_tab, add the "User Reviews tab"
     *
     * @author Dario Curvino <@dudo>
     * @since  2.6.8 refactored as method
     *
     * @param $active_tab
     */
    public function urSettingsTab($active_tab) {
        ?>
        <a href="?page=yasr_settings_page&tab=ur_general_options"
           id="ur_general_options"
           class="nav-tab <?php if ($active_tab === 'ur_general_options') {
               echo 'nav-tab-active';
           } ?>">
            <?php
                _e("User Reviews", 'yasr-pro');
                echo YASR_LOCKED_FEATURE;
            ?>
        </a>

        <?php

    }


    /**
     * Callback for yasr_settings_tab_content, add page content
     *
     * @author Dario Curvino <@dudo>
     * @since  2.6.8 refactored as method
     *
     * @param $active_tab
     */
    public function urSettingsPage($active_tab) {
        if ($active_tab === 'ur_general_options') {
            ?>
            <form action="options.php" method="post" id="yasr_settings_form">
                <?php
                settings_fields('yasr_ur_general_options_group');
                do_settings_sections('yasr_ur_general_options_tab');
                submit_button(YASR_SAVE_All_SETTINGS_TEXT);
                ?>
            </form>
            <?php
        } //End tab ur options
    }


    /**
     * Hook into yasr_settings_select_ranking and add ranking used by YASR UR
     *
     * @author Dario Curvino <@dudo>
     * @since  2.7.1
     * @param  $select_array
     * @return array
     */
    public function urAddRankingOnSelect($select_array) {
        $select_array[] = 'yasr_pro_ur_ranking';
        return $select_array;
    }


    /**
     * Shows the stars and the titkle in wp-admin/edit-comments.php
     *
     * @author Dario Curvino <@dudo>
     * @since  2.6.8 refatcored as method
     *
     * @param $html
     *
     * @return mixed|string
     */
    public function displayReviewsCommentDashboard($html) {

        $comment_id = get_comment_ID();
        $review_title = esc_attr(get_comment_meta( $comment_id, 'yasr_pro_visitor_review_title', true ));
        $rating = get_comment_meta( $comment_id, 'yasr_pro_visitor_review_rating', true );
        $review_body = get_comment_text( $comment_id );

        //generate an unique id to be sure that every element has a different ID
        $unique_id               = str_shuffle(uniqid());
        $comment_rating_html_id  = 'yasr-pro-visitor-review-rater-' . $unique_id;

        if ($rating) {

            $rating = '<div class="yasr-rater-star-comment" 
                        id="'.$comment_rating_html_id.'" 
                        data-rating="'.$rating.'">
                   </div>';

            $review_title_span = "<span class=\"yasr-pro-rating-comment-title\"><strong>$review_title</strong></span>";

            $html = $rating . $review_title_span . '<p>' . $review_body;
        }

        return $html;

    }


    /**
     * Delete YASR comment meta when comment is deleted
     *
     * @author Dario Curvino <@dudo>
     * @since  2.6.8 refatcored as method
     *
     * @param $comment_id
     */
    public function deleteReviewsCommentMeta($comment_id) {
        delete_comment_meta($comment_id, 'yasr_pro_visitor_review_title');
        delete_comment_meta($comment_id, 'yasr_pro_visitor_review_rating');
    }


    /**
     * Callback for wp_ajax_yasr_pro_update_comment_rating
     *
     * @author Dario Curvino <@dudo>
     */
    public function urUpdateReviewRating() {
        if (isset( $_POST['rating'] ) && isset( $_POST['commentId'] ) && isset( $_POST['nonce'] ) ) {
            $rating     = $_POST['rating'];
            $comment_id = $_POST['commentId'];
            $nonce      = $_POST['nonce'];
        } else {
            exit();
        }

        $error_name_nonce = __('Wrong nonce. Title can\'t be updated.', 'yasr-pro');
        $valid_nonce = YasrShortcodesAjax::validNonce($nonce, 'yasr_pro_nonce_update_comment_rating', $error_name_nonce);
        if($valid_nonce !== true) {
            die ($valid_nonce);
        }

        $array_to_return = array();

        $result = update_comment_meta($comment_id, 'yasr_pro_visitor_review_rating', $rating);

        if ($result) {
            $string = sprintf(__( 'New vote is %d', 'yasr-pro' ), $rating);
            $array_to_return['status'] = 'success';
            $array_to_return['text']   = $string;
        } else {
            $array_to_return['status'] = 'error';
            $array_to_return['text']   = __( "Something goes wrong", 'yasr-pro' );

        }

        echo json_encode($array_to_return);

        die();

    }

    /**
     * Callback for wp_ajax_yasr_pro_update_comment_title
     *
     * @author Dario Curvino <@dudo>
     */
    public function urUpdateReviewTitle() {
        if (isset($_POST['title']) && isset($_POST['commentId']) && isset($_POST['nonce'])) {
            $title      = $_POST['title'];
            $comment_id = $_POST['commentId'];
            $nonce      = $_POST['nonce'];
        } else {
            exit();
        }

        $error_name_nonce = __('Wrong nonce. Title can\'t be updated.', 'yasr-pro');

        $valid_nonce = YasrShortcodesAjax::validNonce($nonce, 'yasr_pro_nonce_update_comment_title', $error_name_nonce);
        if($valid_nonce !== true) {
            die ($valid_nonce);
        }

        $array_to_return = array ();

        //Title must be 2 chars
        if (mb_strlen($title) < 2) {
            $text_to_return = __('Title must be at least 2 chars', 'yasr-pro');
            $array_to_return['status'] = 'error';
            $array_to_return['text']  = $text_to_return;
        }

        else if (mb_strlen($title) > 100) {
            $text_to_return = __('Title must be shorter than 100 chars', 'yasr-pro');
            $array_to_return['status'] = 'error';
            $array_to_return['text']   = $text_to_return;
        }

        else {
            $result = update_comment_meta($comment_id, 'yasr_pro_visitor_review_title', $title);

            if ($result) {
                $text_to_return = "<strong>$title</strong> &nbsp;&nbsp;&nbsp;";
                $text_to_return .= __('Title Updated', 'yasr-pro');
                $array_to_return['status'] = 'success';
            }
            else {
                $text_to_return = __('Something goes wrong', 'yasr-pro');
                $array_to_return['status'] = 'error';
            }
            $array_to_return['text']  = $text_to_return;
        }

        echo json_encode($array_to_return);

        die();

    }
}