<?php

if (!defined('ABSPATH')) {
    exit('You\'re not allowed to see this page');
} // Exit if accessed directly

/**
 * This class get all the db value related to comments with ratings
 */
class YasrCommentsRatingData {

    /**
     * Check if the post has comment review enabled
     *
     * @param bool|integer $post_id
     *
     * @return int
     */
    public function commentReviewEnabled($post_id=false) {

        //if values it's not passed get the post id, most of cases and default one
        if (!is_numeric($post_id)) {
            $post_id = get_the_ID();
        }

        //get post meta returns:
        //An array if $single is false. The value of the meta field if $single is true. False for an invalid $post_id.
        //if post meta doesn't exists, returns ''
        $comment_review_enabled = get_post_meta($post_id, 'yasr_pro_reviews_in_comment_enabled', true);

        if($comment_review_enabled === false || $comment_review_enabled === '') {
            if (YASR_PRO_UR_COMMENT_AUTO_INSERT === 'yes') {
                $comment_review_enabled = 1;
            } else {
                $comment_review_enabled = 0;
            }
        }

        return (int)$comment_review_enabled;
    }

    /**
     * Return an array with average and number of votes for a post
     *
     * @param $post_id
     *
     * @return array | bool
     */

    public function getCommentStats($post_id) {
        $comments_array = $this->getCommentsWithRatings($post_id);

        $comments_stats = array(
            'n_of_votes' => 0,
            'average'    => 0
        );

        //If array is not empty means that at least exists a review for that post or page
        if (!empty($comments_array)) {
            $comments_ids = array();

            //Creating an array with all comments_id
            foreach ($comments_array as $comment) {
                $comments_ids[] = $comment->comment_ID;
            }

            $sum_votes  = 0;
            $n_of_votes = 0;

            //foreach comments id take the yasr_pro_visitor_review_rating
            foreach ($comments_ids as $comment_id) {
                $comment_meta = get_comment_meta($comment_id, 'yasr_pro_visitor_review_rating', true);
                $sum_votes    = $sum_votes + $comment_meta;
                $n_of_votes   = $n_of_votes + 1;
            }

            $comments_stats['n_of_votes'] = $n_of_votes;
            $comments_stats['average']    = $sum_votes / $n_of_votes;
            $comments_stats['average']    = round($comments_stats['average'], 1);

            return $comments_stats;

        }

        return false;
    }

    /**
     * Return an array with all approved comments that has the meta value yasr_pro_visitor_review_rating
     *
     * @param $post_id
     *
     * @return array|int
     */
    public function getCommentsWithRatings($post_id) {
        //Setting arguments to get comments
        $args = array(
            'post_id'    => $post_id,
            'orderby'    => 'comment_post_ID',
            'status'     => 'approve',
            'meta_query' => array(
                array(
                    'key' => 'yasr_pro_visitor_review_rating',
                )
            )
        );

        //Define new class that allows querying WordPress database tables 'wp_comments' and 'wp_commentmeta'.
        $comments_query = new WP_Comment_Query;
        return $comments_query->query($args);

    }

}