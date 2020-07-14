jQuery(document).ready(function ($) {

    ok_update_views();
    function ok_update_views() {
        if (!$('[data-listing-wrapper-id]').length) {
            return;
        }

        var post_IDS = [];
        var is_single = false;

        $('[data-listing-wrapper-id]').each(function () {
            post_IDS.push($(this).data('listing-wrapper-id'));
            if ($(this).data('listing-is-single') == '1') {
                is_single = 1;
            }
        });

        if (!jQuery.isEmptyObject(post_IDS)) {
            var data = {
                action: 'listing_view',
                nonce: oklistingdata.nonce,
                post_IDS: post_IDS,
                is_single: is_single
            };

            jQuery.post(ajaxurl, data, function (response) {
                // console.log('views count updated');
            });
        }


    }


    var favButton = $('.favorite-listing-toggle');
    favButton.on('click', function (e) {
        e.preventDefault();

        var listing_ID = jQuery(this).data('listing-id');

        if (typeof listing_ID == 'undefined' ) {
            //no listing id, so user wasnt logged in
            console.log('no listing id');
            return false;
        } 

        var currentButton = $(this);
        var buttonText = (currentButton.hasClass('favorited') ? 'Add to favorites' : 'Favorited');

        currentButton.text(buttonText).toggleClass('favorited');
        jQuery.post(ajaxurl, {
            action: 'oklisting_toggle_fav',
            listing_ID: jQuery(this).data('listing-id')

        }, function (response) {
            // console.log(response);
        });

    })


    $(document).on('facetwp-loaded', function () {
        $.each(FWP.settings.num_choices, function (key, val) {
            var $parent = $('.facetwp-facet-' + key).closest('.jv-facet');
            (0 === val) ? $parent.hide() : $parent.show();
        });
    });



});