/****** Yasr User Reviews ******/
const yasrRaterInComments = document.getElementsByClassName('yasr-rater-stars-in-comments');

//Show rater for the empty comment form
if (yasrRaterInComments.length > 0) {
    yasrSetRaterUserRewiews(yasrRaterInComments);
}

//If found in the dom, show ratings in the comments
const yasrRaterInCommentsRated = document.getElementsByClassName('yasr-rater-stars-in-comment-rated');

if (yasrRaterInCommentsRated.length > 0) {
    yasrSetRaterInComments(yasrRaterInCommentsRated);

    const elems = document.getElementsByClassName('yasr-pro-new-input-comment-form');

    yasrShowOrHideNewFieldsIfIsAReply(elems);

    if (typeof yasrReviewInCommentData !== 'undefined') {
        yasrProEditReview();
    }

}


/*** Show the fields for the author of the review***/
function yasrProEditReview () {

    //Update the title
    jQuery('#yasr-pro-edit-visitor-title-'+yasrReviewInCommentData.commentId).on('click', function(event) {

        jQuery('#yasr-pro-visitor-title-editable-'+yasrReviewInCommentData.commentId).hide();
        jQuery('#yasr-pro-edit-visitor-title-'+yasrReviewInCommentData.commentId).hide();
        jQuery('#yasr-pro-hidden-form-visitor-title-span-'+yasrReviewInCommentData.commentId).show();

        event.preventDefault();

        //On click update
        jQuery('#yasr-pro-update-visitor-title-'+yasrReviewInCommentData.commentId).on('click', function() {
            var title = jQuery('#yasr-pro-hidden-form-visitor-title-'+yasrReviewInCommentData.commentId).val();

            var data = {
                action: 'yasr_pro_update_comment_title',
                commentId: yasrReviewInCommentData.commentId,
                nonce: yasrReviewInCommentData.nonceTitle,
                title: title
            };

            //Send to the Server
            jQuery.post(yasrCommonData.ajaxurl, data, function(response) {
                jQuery('#yasr-pro-hidden-form-visitor-title-'+yasrReviewInCommentData.commentId).hide();

                let responseText;
                response = JSON.parse(response);
                responseText = response.text;

                jQuery('#yasr-pro-hidden-form-visitor-title-links-'+yasrReviewInCommentData.commentId).html(responseText);
            });

            return false;

        }); //End update title

    });

    //Undo update title

    //Undo update the title
    jQuery('#yasr-pro-undo-title-rating-comment-'+yasrReviewInCommentData.commentId).on('click', function() {

        jQuery('#yasr-pro-hidden-form-visitor-title-span-'+yasrReviewInCommentData.commentId).hide();
        jQuery('.yasr-pro-visitor-title-editable').show();
        jQuery('#yasr-pro-edit-visitor-title-'+yasrReviewInCommentData.commentId).show();
        return false;

    });

}

function yasrShowOrHideNewFieldsIfIsAReply (elems) {

    //On click on reply hide new input
    jQuery(document).on('click', 'a.comment-reply-link', function (event) {
        document.getElementById('yasr-pro-title-comment-form-review').style.display = 'none';
        for (var i = 0; i < elems.length; i++) {
            elems[i].style.display = 'none';
        }
    });

//On click on delete reply show new input
    jQuery(document).on('click', 'a#cancel-comment-reply-link', function (event) {
        document.getElementById('yasr-pro-title-comment-form-review').style.display = '';
        for (var i = 0; i < elems.length; i++) {
            elems[i].style.display = '';
        }
    });

}


//this is to set the star in the comment form fileds
function yasrSetRaterUserRewiews (yasrRaterInComments) {

    //Check in the object
    for (var i = 0; i < yasrRaterInComments.length; i++) {

        //This should be not necessary, there is only 1 form per page
        (function (i) {

            var htmlId = yasrRaterInComments.item(i).id;
            var starSize = yasrRaterInComments.item(i).getAttribute('data-rater-starsize');

            starSize = parseInt(starSize);

            raterJs({
                starSize: starSize,
                step: 1,
                showToolTip: false,
                readOnly: false,
                element: document.getElementById(htmlId),

                rateCallback: function rateCallback(rating, done) {

                    //Just leave 1 number after the .
                    rating = rating.toFixed(1);
                    //Be sure is a number and not a string
                    rating = parseFloat(rating);

                    this.setRating(rating);

                    document.getElementById('yasr-pro-visitor-review-rating').value=rating;

                    done();

                }

            });

        })(i);

    } //End for

}

function yasrSetRaterInComments (yasrRaterInCommentsRated) {

    //Check in the object
    for (var i = 0; i < yasrRaterInCommentsRated.length; i++) {

        (function (i) {

            var htmlId = yasrRaterInCommentsRated.item(i).id;
            var starSize = yasrRaterInCommentsRated.item(i).getAttribute('data-rater-starsize');
            var readonly = yasrRaterInCommentsRated.item(i).getAttribute('data-rater-readonly');
            var commentId = yasrRaterInCommentsRated.item(i).getAttribute('data-rater-commentid');
            var nonce = yasrRaterInCommentsRated.item(i).getAttribute('data-rater-nonce');

            if (typeof readonly === 'undefined' || readonly === null || readonly === '') {
                readonly = true;
            }

            //Convert string to boolean
            if (readonly === 'true' || readonly === '1') {
                readonly = true;
            }
            if (readonly === 'false' || readonly === '0') {
                readonly = false;
            }

            starSize = parseInt(starSize);

            raterJs({
                starSize: starSize,
                step: 1,
                showToolTip: false,
                readOnly: readonly,
                element: document.getElementById(htmlId),

                rateCallback: function rateCallback(rating, done) {

                    //Just leave 1 number after the .
                    rating = rating.toFixed(1);
                    //Be sure is a number and not a string
                    rating = parseFloat(rating);

                    this.setRating(rating);

                    document.getElementById('yasr-pro-loader-update-vote-comment-' + commentId).innerHTML = yasrCommonData.loaderHtml;

                    //Creating an object with data to send
                    var data = {
                        action: 'yasr_pro_update_comment_rating',
                        commentId: commentId,
                        nonce: nonce,
                        rating: rating
                    };

                    jQuery.post(yasrCommonData.ajaxurl, data, function (response) {
                        let responseText;
                        response = JSON.parse(response);
                        responseText = response.text;
                        document.getElementById('yasr-pro-loader-update-vote-comment-' + commentId).innerHTML = responseText;
                    });


                    /** This code works, but not for IE **/
                    //Convert in a string
                   /* var dataToSend = jsObject_to_URLEncoded(data);

                    //Create a new request
                    var yasrVVAjaxCall = new Request(yasrCommonData.ajaxurl, {
                        method: 'post',
                        headers: new Headers({
                            "Content-Type": "application/x-www-form-urlencoded"
                        }),
                        body: dataToSend
                    });

                    //Do the ajax call
                    fetch(yasrVVAjaxCall)
                        .then(checkResponse)
                        .then(function (response) {
                            //return the new average rating
                            return response.text();
                        })

                        .then(function (data) {
                            document.getElementById('yasr-pro-loader-update-vote-comment-' + commentId).innerHTML = data;
                        })
                        .then(done)
                        .catch(function (err) {
                            console.log('Error with ajax call', err);
                        });*/
                }

            });

        })(i);

    } //End for

}


//On click on highest, hide most and show highest
function yasrShowHighestFromReviews () {
    document.getElementById('yasr-pro-most-rated-posts-from-reviews').style.display = 'none';
    document.getElementById('yasr-pro-highest-rated-posts-from-reviews').style.display = '';

    return false; // prevent default click action from happening!

}

//Vice versa
function yasrShowMostFromReviews () {
    document.getElementById('yasr-pro-highest-rated-posts-from-reviews').style.display = 'none';
    document.getElementById('yasr-pro-most-rated-posts-from-reviews').style.display = '';

    return false; // prevent default click action from happening!
}

/******End Yasr Pro reviews in comments ******/

