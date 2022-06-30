<?php

if (!defined('ABSPATH')) {
    exit('You\'re not allowed to see this page');
} // Exit if accessed directly

//Avoid double review and display new fields to be filled
add_action('comment_form_before', 'yasr_pro_ur_avoid_double_review');

//Save metadata and setcookie
add_action('comment_post', 'yasr_pro_ur_save_review_meta_data' );

//If I'm not on admin pages, show the new input for store comments
if (!is_admin()) {
    add_filter('comment_text', 'yasr_pro_ur_display_new_input_in_comment_form', 999); //Show the new input fields
}

function yasr_pro_ur_avoid_double_review() {

    $post_id = get_the_ID();

    $yasr_comment_rating_data_obj = new YasrCommentsRatingData();
    $comment_review_enabled = (bool)$yasr_comment_rating_data_obj->commentReviewEnabled();

    //get post meta returns as string
    if ($comment_review_enabled === true) {

        //check only if user is logged in
        if (is_user_logged_in()) {
            $current_user_id = get_current_user_id();

            //Settings argument to get comments
            $args = array(
                'post_id' => $post_id,
                'user_id' => $current_user_id,
                'meta_query' => array(
                                        array(
                                            'key' => 'yasr_pro_visitor_review_rating',
                                        )
                                    ),
                'orderby' => 'meta_value'
            );

            //Define new class that allows querying WordPress database tables 'wp_comments' and 'wp_commentmeta'.
            $comments_query = new WP_Comment_Query;
            $comments_array = $comments_query->query( $args );

            //If array is not empty means that review for that post or page exists
            if (!empty($comments_array)) {
                //Remove the new input fields, to support all themes (i.e. this is not need for twenty14 but is need for hueman)
                remove_action('comment_form_logged_in_after', 'yasr_pro_ur_add_input_to_comment_form' );
            }

            else {
                add_action( 'comment_form_logged_in_after', 'yasr_pro_ur_add_input_to_comment_form' ); //New input fields, for logged in users
            }

        }

        //If user is not logged in
        else {
            if(YASR_PRO_UR_COMMENT_ALLOW_ANONYMOUS === 'no') {
                add_action( 'comment_form_top', 'yasr_pro_ur_comment_form_login_required' ); //For non - logged in users
            }

            else {
                $cookie_name = 'yasr_rated_comment_' . $post_id;

                if (isset($_COOKIE[$cookie_name])) {

                    $cookie_value = stripslashes($_COOKIE[$cookie_name]);
                    $cookie_value = json_decode($cookie_value, TRUE);

                    if (in_array($post_id, $cookie_value)) {
                        //Remove the new input fields, to support all themes (i.e. this is not needed for twenty14 but is need for hueman)
                        remove_action('comment_form_logged_in_after', 'yasr_pro_ur_add_input_to_comment_form' );
                    }

                    else {
                        //New input fields, for non logged in users that leave a review in another post / page
                        add_action( 'comment_form_before_fields', 'yasr_pro_ur_add_input_to_comment_form' );
                    }

                }

                else {
                    add_action( 'comment_form_before_fields', 'yasr_pro_ur_add_input_to_comment_form' ); //New input fields, for non logged in users
                }

            }

        }

    }

}


/****** Add login link and register to the comment form is user is not logged in ******/
function yasr_pro_ur_comment_form_login_required() {

    _e("If you want to leave a review, please", 'yasr-pro') ?>
        <a href="<?php echo wp_login_url( get_permalink() ); ?>" title="Login"><?php _e ("Login", 'yasr-pro') ?></a>

    <?php _e("or", 'yasr-pro') ?>
        <a href="<?php echo wp_registration_url(); ?>"><?php _e ("Register", 'yasr-pro') ?></a>

    <?php _e("first.", 'yasr-pro') ?>

    <?php

}


/******* Adding star and title in comment form, the add_action is in the funcion above ******/
function yasr_pro_ur_add_input_to_comment_form() {

    echo "<span id='yasr-pro-title-comment-form-review'>";
        _e('Add your own review', 'yasr-pro');
    echo "</span>";


    if(YASR_PRO_UR_COMMENT_STARS_SIZE === 'small') {
        $stars_width = "16";
    }

    else if(YASR_PRO_UR_COMMENT_STARS_SIZE === 'big') {
        $stars_width = "32";
    }

    else {
        $stars_width = "24";
    }


    ?>

    <div class="yasr-pro-new-input-comment-form">
        <br>

        <span id="yasr-pro-rating-name-comment-form">
            <?php _e("Rating", 'yasr-pro'); ?><br />
        </span>

        <?php $html_id = 'yasr-pro-visitor-review-rater';?>

        <div class="yasr-rater-stars-in-comments" id="<?php echo $html_id ?>" data-rater-starsize="<?php echo $stars_width?>"></div>

        <input type="hidden" id="yasr-pro-visitor-review-rating" name="yasr_pro_visitor_review_rating" value="-1">

        <p class="comment-form-title">
            <label for="yasr-pro-visitor-review-title"></label>
            <input id="yasr-pro-visitor-review-title"
                name="yasr_pro_visitor_review_title"
                type="text"
                size="30"
                tabindex="5"
                placeholder="<?php  _e( 'Title of your review', 'yasr-pro' ); ?>"
            />

        </p>

    </div>

    <?php

    do_action('yasr_ur_add_custom_form_fields');


}

/*** Insert into db, into the comment meta table, add_action is on top ***/

function yasr_pro_ur_save_review_meta_data($comment_id) {

    $comment = get_comment($comment_id);
    $post_id = (int)$comment->comment_post_ID;

    //don't save metadata and don't do controls if this is an answer to a comment
    if ($comment->comment_parent != '0') {
        return;
    }

    if (YASR_PRO_UR_COMMENT_ALLOW_ANONYMOUS === 'no' && !is_user_logged_in()) {
        //DO NOT use wp_die here, or the setting will be required to be set even if user in reviews are not
        //set for a single post or page
        return;
    }

    //check if user already rated, if so, just return;
    if ( is_user_logged_in() ) {
        $current_user_id = get_current_user_id();

        //Settings argument to get comments
        //I've set 2 commentmeta field, and specified an OR relation. If BOTH exists, array will be returned
        //further doc https://codex.wordpress.org/Class_Reference/WP_Comment_Query
        $args = array(
            'post_id' => $post_id,
            'user_id' => $current_user_id,
            'meta_query' => array(
                                array(
                                    'key' => 'yasr_pro_visitor_review_rating',
                                )
                            ),
            'orderby' => 'meta_value'
        );

        //Define new class that allows querying WordPress database tables 'wp_comments' and 'wp_commentmeta'.
        $comments_query = new WP_Comment_Query;
        $comments_array = $comments_query->query( $args );

        //If array is not empty means that review for that post or page exists
        if (!empty($comments_array)) {
            return;
        }

    }

    do_action('yasr_ur_save_custom_form_fields', $comment_id); //here we can hook new form field

    $yasr_comment_rating_data_obj = new YasrCommentsRatingData();
    $comment_review_enabled = (bool)$yasr_comment_rating_data_obj->commentReviewEnabled($post_id);

    if ($comment_review_enabled === false) {
        return;
    }

    if ( ( isset( $_POST['yasr_pro_visitor_review_title'] ) ) && ( $_POST['yasr_pro_visitor_review_title'] != '') ) {
        $title = wp_filter_nohtml_kses($_POST['yasr_pro_visitor_review_title']);
        add_comment_meta( $comment_id, 'yasr_pro_visitor_review_title', $title );
    }

    else {
        if (YASR_PRO_UR_RATING_MANDATORY === 'yes') {
            wp_delete_comment( $comment_id, TRUE );

            $error_message= __("Please insert a title for the review", "yasr-pro");
            wp_die($error_message);
        }
    }

    //If isset and is not empty and is not the default value (-1) insert the rating into db
    if ( ( isset( $_POST['yasr_pro_visitor_review_rating'] ) ) && ( $_POST['yasr_pro_visitor_review_rating'] != '')
        && ( $_POST['yasr_pro_visitor_review_rating'] != '-1') ) {

        $rating = ($_POST['yasr_pro_visitor_review_rating']);

        if ($rating > 5) {
            $rating = 5;
        }

        add_comment_meta( $comment_id, 'yasr_pro_visitor_review_rating', $rating );

        $cookie_name = 'yasr_rated_comment_' . $post_id;
        yasr_setcookie($cookie_name, $post_id);

        do_action('yasr_ur_do_content_after_save_commentmeta', $comment_id);
    }

    else {
        if (YASR_PRO_UR_RATING_MANDATORY === 'yes') {
            wp_delete_comment( $comment_id, TRUE );

            $error_message= __("Please insert the rating for this review", "yasr-pro");
            wp_die($error_message);
        }
    }

}

/****** Show the new input fields in comments. add_action is on top ******/
function yasr_pro_ur_display_new_input_in_comment_form($html) {
    if (have_comments()) {
        $comment_id = get_comment_ID();

        if (!$comment_id) {
            return $html;
        }

        $comment      = get_comment($comment_id);
        $review_title = esc_attr(get_comment_meta($comment_id, 'yasr_pro_visitor_review_title', true));
        $rating       = get_comment_meta($comment_id, 'yasr_pro_visitor_review_rating', true);

        //if this is a reply be sure to just return the comment text
        if ($comment) {
            if ($comment->comment_parent !== '0') {
                return $html;
            }
        }

        if ($rating) {
            $review_body = "<span class='yasr-pro-rating-comment-body'>" . $comment->comment_content . "</span>";

            if (YASR_PRO_UR_COMMENT_STARS_SIZE === 'small') {
                $px_size = '16';
            }
            elseif (YASR_PRO_UR_COMMENT_STARS_SIZE === 'large') {
                $px_size = '32';
            }
            else {
                $px_size = '24';
            }

            $ajax_nonce_update_comment_rating = null;
            $ajax_nonce_update_comment_title  = null;

            $author          = (int)get_comment($comment_id)->user_id; //get the user id
            $current_user_id = get_current_user_id();

            //if current user id is not 0 is needed to block anonymous user to edit reviews from other anonymous
            //If the user logged is the author of the review
            if ($current_user_id !== 0 && $author === $current_user_id) {

                $ajax_nonce_update_comment_rating = wp_create_nonce("yasr_pro_nonce_update_comment_rating");
                $ajax_nonce_update_comment_title  = wp_create_nonce("yasr_pro_nonce_update_comment_title");

                $rating = "<div id='yasr-pro-visitor-review-rateit-update-$comment_id' 
                              class='yasr-rater-stars-in-comment-rated' 
                              data-rater-commentid='$comment_id' 
                              data-rating='$rating' 
                              data-rater-starsize='$px_size' 
                              data-rater-readonly='false' 
                              data-rater-nonce='$ajax_nonce_update_comment_rating'>
                            </div>";

                $loader = __("Loading, please wait", 'yasr-pro') . "<img src=\"" . YASR_IMG_DIR . "/loader.gif\">";

                //Begin Comment
                $loader_and_vote_updated = "<div id='yasr-pro-loader-update-vote-comment-$comment_id' 
                                                 class='yasr-pro-loader-update-vote-comment'>&nbsp;</div>";

                //New row
                $review_title_span = "<span id=\"yasr-pro-visitor-title-editable-span-$comment_id\" class=\"yasr-pro-visitor-title-editable-span\">
                                    <span class=\"yasr-pro-visitor-title-editable\" id=\"yasr-pro-visitor-title-editable-$comment_id\">$review_title</span>
                                    <a href=\"#\" id=\"yasr-pro-edit-visitor-title-$comment_id\">". __('Edit Title', 'yasr-pro') ."</a>

                                    <span id=\"yasr-pro-hidden-form-visitor-title-span-$comment_id\" class=\"yasr-pro-hidden-form-visitor-title-span\">
                                        <input type=\"text\" value=\"$review_title\" maxlength=\"100\" id=\"yasr-pro-hidden-form-visitor-title-$comment_id\" class=\"yasr-pro-hidden-form-visitor-title\">
                                        <span id=\"yasr-pro-hidden-form-visitor-title-links-$comment_id\">
                                            <a href=\"#\" id=\"yasr-pro-update-visitor-title-$comment_id\">". __('Update', 'yasr-pro') ."</a>
                                            &nbsp;&nbsp;&nbsp;
                                            <a href=\"#\" id=\"yasr-pro-undo-title-rating-comment-$comment_id\">". __('Undo', 'yasr-pro') ."</a>
                                        </span>
                                    </span>

                                  </span>";


                wp_localize_script(
                    'yasrprofront', 'yasrReviewInCommentData', array(
                        'commentId'       => $comment_id,
                        'nonceRating'     => $ajax_nonce_update_comment_rating,
                        'nonceTitle'      => $ajax_nonce_update_comment_title,
                        'loader'          => $loader,
                        'reviewTitleSpan' => $review_title_span
                    )
                );

            }

            else {
                $rating
                                         = "<div id=\"yasr-pro-visitor-review-rateit-update-$comment_id\" class=\"yasr-rater-stars-in-comment-rated\" data-rater-commentid=\"$comment_id\" data-rating=\"$rating\" data-rater-starsize=\"$px_size\" data-rater-readonly=\"true\"></div>";
                $loader_and_vote_updated = '';
                $review_title_span
                                         = "<span class=\"yasr-pro-rating-comment-title\"><strong>$review_title</strong></span>";
            }

            //if there is no hook for yasr_ur_display_custom_fields, $comment_id is returned
            $html = apply_filters('yasr_ur_display_custom_fields', $comment_id);

            if ($html === $comment_id) {
                $html = '';
            }

            $html .= "<div class=\"yasr-pro-rating-and-loader-comment\">" . $rating . $loader_and_vote_updated
                . "</div>" . $review_title_span;

            $html .= $review_body;

        }
        return $html;
    }
    return $html;
}
?>