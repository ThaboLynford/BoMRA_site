<?php

if (!defined('ABSPATH')) {
    exit('You\'re not allowed to see this page');
} // Exit if accessed directly

class YasrProURShortcodes extends YasrShortcode {
    /**
     * Callback for yasr_pro_average_comments_ratings
     * Print an average from all the reviews for the post
     *
     * @author Dario Curvino <@dudo>
     * @since  2.6.8
     * @return string|void
     */
    public function reviewsAverage () {
        $size = $this->starSize();

        $comments_average_obj = new YasrCommentsRatingData();
        $comment_review_enabled = $comments_average_obj->commentReviewEnabled($this->post_id);

        if ($comment_review_enabled === 0) {
            return __('Comment Reviews for this post are disabled. Please enable it first', 'yasr-pro');
        }

        $comments_stats = $comments_average_obj->getCommentStats($this->post_id);

        if($comments_stats === false) {
            $comments_stats['average']    = 0;
            $comments_stats['n_of_votes'] = 0;
        }

        $unique_id = str_shuffle(uniqid());

        $average_html_id = 'yasr-pro-average-comments-ratings-' . $unique_id;

        $this->shortcode_html .= "<div class='yasr-rater-stars' 
                                       id='$average_html_id' 
                                       data-rater-starsize='$size' 
                                       data-rating='".$comments_stats['average']."'>
                                  </div>";

        $this->customTextAfter($comments_stats['n_of_votes'], $comments_stats['average']);
        return  $this->shortcode_html;

    }

    /**
     * Doesn't accept any parameter, declared static
     *
     * @author Dario Curvino <@dudo>
     * @since  2.6.8
     * @return string|void
     */
    public static function averageCommentsRatingsProgressBars() {

        $post_id = get_the_id();

        $comments_data_obj = new YasrCommentsRatingData();
        $comment_review_enabled = $comments_data_obj->commentReviewEnabled($post_id);

        if ($comment_review_enabled === 1) {
            $comments_array = $comments_data_obj->getCommentsWithRatings($post_id);

            //Count the total votes
            $total_votes_in_array = count($comments_array);

            $shortcode_html = "<div id=\"yasr-pro-comment-reviews-stats-$post_id\" class=\"yasr-pro-comment-reviews-stats\">";
            $stars_text = __("stars", 'yasr-pro');

            //If array is not empty means that at least exists a review for that post or page
            if (!empty($comments_array)) {

                //Creating a multidimensional array with all comments ids that has a rating
                foreach ($comments_array as $comment) {
                    $comments_ids[] = $comment->comment_ID;
                }

                //create an array with the single ratings
                foreach ($comments_ids as $comment_id) {
                    $comment_meta     = get_comment_meta($comment_id, 'yasr_pro_visitor_review_rating', true);
                    $existing_votes[] = $comment_meta;
                }

                //Sorting from high to low
                arsort($existing_votes);

                //Counting the rating with same vote, the resulting array will be
                //[vote] => vote_occurence
                //both as int
                $rating_same_vote = array_count_values($existing_votes);
                $single_rating_array = null; //avoid undefined

                $i = 1;
                //Create an array with the structure vote and n_of_votes
                foreach ($rating_same_vote as $key => $occurence) {
                    $single_rating_array[$i]               = array();
                    $single_rating_array[$i]['vote']       = $key; //The key is the vote "name"
                    $single_rating_array[$i]['n_of_votes'] = $occurence; //How many time that vote has been given
                    $i ++;
                }

                //find if there is some missing votes
                for ($i = 1; $i <= 5; $i ++) {
                    if (!in_array($i, $existing_votes)) {
                        $missing_vote[$i]               = array();
                        $missing_vote[$i]['vote']       = $i;
                        $missing_vote[$i]['n_of_votes'] = 0; //If is missing 0 times have been given
                    }
                }

                //If array $missing vote is not empty, merge with $single rating array
                if (!empty($missing_vote)) {
                    $rating_array = array_merge($single_rating_array, $missing_vote);
                } else {
                    $rating_array = $single_rating_array;
                }

                //order array by $rating['votes'] from higher to lower
                arsort($rating_array);

                //Increasing value for a vote
                $single_vote_increasing_value = 100 / $total_votes_in_array;

                $i = 5;

                foreach ($rating_array as $single_rate) {
                    //Find the bar value
                    $bar_value = $single_vote_increasing_value * $single_rate['n_of_votes'];
                    $bar_value = (float)round($bar_value, 2) . '%';

                    if ($i == 1) {
                        $stars_text = __("star", 'yasr-pro');
                    }

                    $shortcode_html .= self::returnProgressBarsContainer($i, $stars_text, $bar_value, $single_rate['n_of_votes']);

                    $i = $i - 1; //decrease i
                }

                $shortcode_html .= "</div>";

            } else {
                for ($i = 5; $i > 0; $i --) {
                    if ($i == 1) {
                        $stars_text = __("star", 'yasr-pro');
                    }
                    $shortcode_html .= self::returnProgressBarsContainer($i, $stars_text, 0, 0);

                }
                $shortcode_html .= "</div>";
            }

        } else {
            $shortcode_html = __("Comment Reviews for this post are disabled. Please enable it first", 'yasr-pro');
        }

        return $shortcode_html;

    }

    protected function customTextAfter($n_of_votes, $average_rating) {
        $string_to_search = false;

        //if is single page
        if (is_singular() && is_main_query()) {
            if (defined('YASR_PRO_UR_TEXT_AFTER_COMMENTS_RATINGS')
                && YASR_PRO_UR_TEXT_AFTER_COMMENTS_RATINGS !== ''
            ) {
                $string_to_search = YASR_PRO_UR_TEXT_AFTER_COMMENTS_RATINGS;
            }
        }
        //if is archive page
        else {
            if (defined('YASR_PRO_UR_TEXT_AFTER_COMMENTS_RATINGS_ARCHIVE')
                && YASR_PRO_UR_TEXT_AFTER_COMMENTS_RATINGS_ARCHIVE !== ''
            ) {
                $string_to_search = YASR_PRO_UR_TEXT_AFTER_COMMENTS_RATINGS_ARCHIVE;
            }
        }

        if ($string_to_search !== false) {
            $text_after_star = str_replace(
                array('%total_count%', '%average%'),
                array($n_of_votes, $average_rating),
                $string_to_search
            );
            $this->shortcode_html  .= "<div class='yasr-container-custom-text-comments-rating'>
                                         <span id='yasr-custom-text-after-comments-rating'>" . $text_after_star . "</span>
                                     </div>";
        }
    }

     /**
     * Since 2.1.4, return the row with the bar
     *
     * @param int $i
     * @param string $stars_text
     * @param float $bar_value
     * @param int $number_of_votes
     *
     * @return string
     */
    protected static function returnProgressBarsContainer ($i, $stars_text, $bar_value, $number_of_votes) {
        return "<div class='yasr-progress-bar-row-container yasr-w3-container'>
                                <div class='yasr-progress-bar-name'> $i $stars_text </div>
                                <div class='yasr-single-progress-bar-container'>
                                    <div class='yasr-w3-border '>
                                        <div class='yasr-w3-amber' style='height:17px;width:$bar_value'></div>
                                    </div>
                                </div>
                                <div class='yasr-progress-bar-votes-count'>" . $number_of_votes . "</div>
                                <br />
                          </div>";
}


}