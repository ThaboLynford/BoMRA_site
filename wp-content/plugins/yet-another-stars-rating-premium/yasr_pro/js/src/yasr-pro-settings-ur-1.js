/****************** YASR USER REVIEW *****************/

let activeTab;
let tabClass = document.getElementsByClassName('nav-tab-active');

if(tabClass.length > 0){
    activeTab = document.getElementsByClassName('nav-tab-active')[0].id;
}

if(activeTab === 'ur_general_options') {
    const urTextEnabled = document.getElementById('yasr-ur-custom-text-enable');

    jQuery('#yasr-pro-review-in-comment-auto-insert-explained-link').on('click', function () {
        jQuery('#yasr-pro-review-in-comment-auto-insert-explained').show();
        return false;
    });

    jQuery('#yasr-doc-custom-text-average-comments-link').on('click', function () {
        jQuery('#yasr-doc-custom-text-average-comments-div').toggle('slow');
        return false;
    });

    if (urTextEnabled.checked === false) {
        jQuery('.yasr-pro-custom-text-comments-ratings').prop('disabled', true);
    }

    urTextEnabled.addEventListener('change', function (e) {
        if (urTextEnabled.checked === true) {
            jQuery('.yasr-pro-custom-text-comments-ratings').prop('disabled', false);
            jQuery('#yasr-pro-custom-text-comments-ratings').val('%total_count% votes, average %average%');
            jQuery('#yasr-pro-custom-text-comments-ratings-archive').val('(%total_count%)');
        } else {
            jQuery('.yasr-pro-custom-text-comments-ratings').prop('disabled', true);
        }
    });

}

/**
 * Hook into yasrBuilderDrawRankingsShortcodes and add ranking used by YASR UR
 * that need to be printed with yasrDrawRankings()
 */
wp.hooks.addFilter('yasrBuilderDrawRankingsShortcodes', 'yet-another-stars-rating', yasrUrDrawRankingShortcodes, 10);

/**
 * Add to given array the shortcode ranking used by YASR UR
 *
 * @param starRankingShortcodes Array with shortcode that need to be printed with yasrDrawRankings()
 * @return {array}
 */
function yasrUrDrawRankingShortcodes(starRankingShortcodes) {
    starRankingShortcodes.push('yasr_pro_ur_ranking');
    return starRankingShortcodes;
}