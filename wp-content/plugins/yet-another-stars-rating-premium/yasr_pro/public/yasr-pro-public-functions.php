<?php
/*

Copyright 2020 Dario Curvino (email : d.curvino@tiscali.it)

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

//Add front js
function yasr_pro_script() {
    wp_enqueue_script('yasrprofront',
        YASR_PRO_JS_DIR . 'yasr-pro-front.js',
        array('jquery', 'rater', 'wp-i18n'),
        YASR_VERSION_NUM,
        true
    );
}

function yasr_pro_ur_front_css() {
    wp_enqueue_style(
        'yasrprocss',
        YASR_PRO_CSS_DIR . 'yasr-pro-front.css',
        false,
        YASR_VERSION_NUM
    );
}


/**
 * @author Dario Curvino <@dudo>
 * @since  2.6.8 moved here
 *
 * @param $post_microdata
 *
 * @return mixed
 */
function yasr_pro_ur_jsonld_reviews($post_microdata) {
    if (YASR_PRO_UR_COMMENT_RICH_SNIPPET !== 'yes') {
        return $post_microdata;
    }

    $post_id = get_the_ID();

    $comments_data_obj = new YasrCommentsRatingData();
    $comment_review_enabled = $comments_data_obj->commentReviewEnabled($post_id);

    if ($comment_review_enabled === 1) {

        $comments_stats = $comments_data_obj->getCommentStats($post_id);

        if($comments_stats === false) {
            return $post_microdata;
        }

        foreach ($post_microdata as $key => $value) {
            if (isset($post_microdata[$key])) {
                if ($key === 'aggregateRating') {
                    $post_microdata['aggregateRating']['ratingValue'] = $comments_stats['average'];
                    $post_microdata['aggregateRating']['reviewCount'] = $comments_stats['n_of_votes'];
                    $post_microdata['aggregateRating']['ratingCount'] = null; //unset this
                }
            }
        }

        return $post_microdata;

    } //YASR_PRO_COMMENT_AUTO_INSERT == 'yes' || $comment_review_enabled == 1

    return $post_microdata;

}


