jQuery(document).ready(function($) {
    $('.lpmanager-call-button').on('click', function() {
        var postId = $(this).data('post-id');  // assign this in your template output
        $.post(lpmanager_vars.ajaxurl, {
            action: 'lpmanager_record_conversion',
            nonce: lpmanager_vars.nonce,
            post_id: postId
        }, function(response) {
            console.log(response.data);
        });
    });
});