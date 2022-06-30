<?php

if (!defined('ABSPATH')) {
    exit('You\'re not allowed to see this page');
} // Exit if accessed directly

/****** YASR PRO GENERAL SETTINGS ******/
add_action('admin_init', 'yasr_pro_ur_general_options_init');

function yasr_pro_ur_general_options_init() {
    register_setting(
        'yasr_ur_general_options_group', // A settings group name. Must exist prior to the register_setting call. This must match the group name in settings_fields()
        'yasr_ur_general_options', //The name of an option to sanitize and save.
        'yasr_pro_ur_general_options_sanitize'
    );

    $general_options = get_option("yasr_ur_general_options");

    //general options are not found
    if (!$general_options) {
        $general_options['comment_stars_auto_insert'] = 'no';
        $general_options['comment_stars_size']        = 'medium';
        $general_options['comment_allow_anonymous']   = 'no';
        $general_options['comment_rich_snippet']      = 'yes';
        $general_options['comment_rating_mandatory']  = 'no';
    }

    add_settings_section(
            'yasr_ur_general_options_section_id',
            __('Yasr User Reviews Options', 'yasr-pro'),
            'yasr_pro_ur_general_options_callback',
            'yasr_ur_general_options_tab'
    );

    add_settings_field('yasr_ur_comments_review',
        __('Reviews In Comments Options', 'yasr-pro'),
        'yasr_pro_ur_comments_review',
        'yasr_ur_general_options_tab',
        'yasr_ur_general_options_section_id',
        $general_options
    );
    add_settings_field('yasr_ur_custom_text',
        __('Insert custom text to show after yasr_pro_average_comments_ratings shortcode', 'yasr-pro'),
        'yasr_pro_custom_text_average_comments_ratings',
        'yasr_ur_general_options_tab',
        'yasr_ur_general_options_section_id',
        $general_options)
    ;

}

function yasr_pro_ur_general_options_callback() {
    //
}

function yasr_pro_ur_comments_review($general_options) {

    ?>

    <strong><?php _e('Allow anonymous?', 'yasr-pro'); ?></strong>
    <br/>

    <div class="yasr-indented-answer">
        <div class="yasr-onoffswitch-big">
            <input type="checkbox" name="yasr_ur_general_options[comment_allow_anonymous]"
                   class="yasr-onoffswitch-checkbox" value="yes"
                   id="yasr-ur-comment-allow-anonymous-switch" <?php if ($general_options['comment_allow_anonymous'] === 'yes') {
                echo " checked='checked' ";
            } ?> >
            <label class="yasr-onoffswitch-label" for="yasr-ur-comment-allow-anonymous-switch">
                <span class="yasr-onoffswitch-inner"></span>
                <span class="yasr-onoffswitch-switch"></span>
            </label>
        </div>
    </div>

    <p>&nbsp;</p>

    <strong><?php _e('Size?', 'yasr-pro'); ?></strong>
    <br/>

    <div class="yasr-indented-answer">
        <?php
            $name  = 'yasr_ur_general_options[comment_stars_size]';
            $class = 'yasr-pro-comments-review-class';
            $id    = 'yasr-pro-comment-reviews-options-size-';
            YasrPhpFieldsHelper::radioSelectSize($name, $class, $general_options['comment_stars_size'], $id);
        ?>
    </div>

    <p>&nbsp;</p>

    <strong><?php _e('Enable on every post and page?', 'yasr-pro'); ?></strong>
    <br/>

    <div class="yasr-indented-answer">

        <div class="yasr-pro-option-review-comments">

            <div class="yasr-onoffswitch-big">
                <input type="checkbox" name="yasr_ur_general_options[comment_stars_auto_insert]"
                       class="yasr-onoffswitch-checkbox" value="yes"
                       id="yasr-ur-comment-stars-auto-insert" <?php if ($general_options['comment_stars_auto_insert'] === 'yes') {
                    echo " checked='checked' ";
                } ?> >
                <label class="yasr-onoffswitch-label" for="yasr-ur-comment-stars-auto-insert">
                    <span class="yasr-onoffswitch-inner"></span>
                    <span class="yasr-onoffswitch-switch"></span>
                </label>
            </div>

            <br/><br/>

            <a href="#" id="yasr-pro-review-in-comment-auto-insert-explained-link">What's this?</a>

            <div id="yasr-pro-review-in-comment-auto-insert-explained">
                <?php _e("By enabling this, in every comment form YASR will add the fields in order to enable your visitors to add their own reviews.", 'yasr-pro'); ?>
                <br/>
                <?php _e("If you choose \"Yes\" but want to exclude a specific post or page, just open the editor page and disable it. ", 'yasr-pro'); ?>
                <br/>
                <?php _e("Vice versa if you choose \"No\". ", 'yasr-pro'); ?>
            </div>

        </div>

    </div>

    <p>&nbsp;</p>

    <strong><?php _e('Should rating and title review be mandatory?', 'yasr-pro'); ?></strong>

    <div class="yasr-indented-answer">

        <div class="yasr-onoffswitch-big">
            <input type="checkbox" name="yasr_ur_general_options[comment_rating_mandatory]"
                   class="yasr-onoffswitch-checkbox" value="yes"
                   id="yasr-ur-comment-rating-mandatory" <?php if ($general_options['comment_rating_mandatory'] === 'yes') {
                echo " checked='checked' ";
            } ?> >
            <label class="yasr-onoffswitch-label" for="yasr-ur-comment-rating-mandatory">
                <span class="yasr-onoffswitch-inner"></span>
                <span class="yasr-onoffswitch-switch"></span>
            </label>
        </div>

    </div>

    <p>&nbsp;</p>

    <strong><?php _e('Create Rich Snippet for comments?', 'yasr-pro'); ?></strong>

    <div class="yasr-indented-answer">

        <div class="yasr-onoffswitch-big">
            <input type="checkbox" name="yasr_ur_general_options[comment_rich_snippet]"
                   class="yasr-onoffswitch-checkbox" value="yes"
                   id="yasr-ur-comment-rich-snippet" <?php if ($general_options['comment_rich_snippet'] === 'yes') {
                echo " checked='checked' ";
            } ?> >
            <label class="yasr-onoffswitch-label" for="yasr-ur-comment-rich-snippet">
                <span class="yasr-onoffswitch-inner"></span>
                <span class="yasr-onoffswitch-switch"></span>
            </label>
        </div>

    </div>

    <br /><br/>

    <hr />


    <?php

}

function yasr_pro_custom_text_average_comments_ratings ($general_options) {

    if (isset($general_options['text_after_stars_enabled']) && $general_options['text_after_stars_enabled'] === 'on') {
        $text_after_comments_ratings                = $general_options['text_after_stars'];
        $text_after_comments_ratings_archive        = $general_options['text_after_stars_archive'];
    }
    else {
        $text_after_comments_ratings = sprintf(
            __('%s votes, average: %s', 'yasr-pro'),
            '%total_count%', '%average%'
        );

        $text_after_comments_ratings_archive = sprintf(
            __('(%s)', 'yasr-pro'),
            '%total_count%'
        );
    }
    ?>

    <div class="yasr-onoffswitch-big">
        <input type="checkbox" name="yasr_ur_general_options[text_after_stars_enabled]" class="yasr-onoffswitch-checkbox"
               id="yasr-ur-custom-text-enable" <?php if (isset($general_options['text_after_stars_enabled'])) {
            echo " checked='checked' ";
        } ?> >
        <label class="yasr-onoffswitch-label" for="yasr-ur-custom-text-enable">
            <span class="yasr-onoffswitch-inner"></span>
            <span class="yasr-onoffswitch-switch"></span>
        </label>
    </div>

    <br/> <br/>

    <input type='text' name='yasr_ur_general_options[text_after_stars]'
        id="yasr-pro-custom-text-comments-ratings"
        class='yasr-pro-custom-text-comments-ratings' <?php printf('value="%s"', $text_after_comments_ratings); ?>
        maxlength="80" />
    <label for="yasr-pro-custom-text-comments-ratings">
        <?php _e('Custom text to display in single post or page', 'yet-another-stars-rating') ?>
    </label>

    <br/> <br/> <br/>

    <input type='text' name='yasr_ur_general_options[text_after_stars_archive]'
           id="yasr-pro-custom-text-comments-ratings-archive"
           class='yasr-pro-custom-text-comments-ratings' <?php printf('value="%s"', $text_after_comments_ratings_archive); ?>
           maxlength="80"/>
    <label for="yasr-pro-custom-text-comments-ratings-archive">
        <?php _e('Custom text to display in archive pages', 'yet-another-stars-rating') ?>
    </label>

    <br/> <br/>

    <a href="#" id="yasr-doc-custom-text-average-comments-link">
        <?php _e('Help', 'yet-another-stars-rating'); ?>
    </a>

    <div id="yasr-doc-custom-text-average-comments-div" class="yasr-help-box-settings">
        <?php
        $string_custom_visitor = sprintf(__('You can use %s pattern to show the 
        total count, and %s pattern to show the average', 'yet-another-stars-rating'),
            '<strong>%total_count%</strong>', '<strong>%average%</strong>');

        echo $string_custom_visitor;
        ?>
    </div>

<?php

}

function yasr_pro_ur_general_options_sanitize($general_options) {

    foreach ($general_options as $key => $value) {
        // Check to see if the current option has a value. If so, process it.
        if (isset($general_options[$key])) {
            //Tags are not allowed for any fields
            $allowed_tags = '';

            $general_options[$key] = strip_tags(stripslashes($general_options[$key]), $allowed_tags);
        }

    }

    //if in array doesn't exists [comment_allow_anonymous] key, create it and set to no
    if (!array_key_exists('comment_allow_anonymous', $general_options)) {
        $general_options['comment_allow_anonymous'] = 'no';
    } else {
        $general_options['comment_allow_anonymous'] = 'yes';
    }

    //if in array doesn't exists [comment_stars_auto_insert] key, create it and set to no
    if (!array_key_exists('comment_stars_auto_insert', $general_options)) {
        $general_options['comment_stars_auto_insert'] = 'no';
    } else {
        $general_options['comment_stars_auto_insert'] = 'yes';
    }

    //if in array doesn't exists [comment_rating_mandatory] key, create it and set to no
    if (!array_key_exists('comment_rating_mandatory', $general_options)) {
        $general_options['comment_rating_mandatory'] = 'no';
    } else {
        $general_options['comment_rating_mandatory'] = 'yes';
    }


    if (!array_key_exists('comment_rich_snippet', $general_options)) {
        $general_options['comment_rich_snippet'] = 'no';
    } else {
        $general_options['comment_rich_snippet'] = 'yes';
    }

    return $general_options;

}

?>
