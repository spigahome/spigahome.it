jQuery(document).bind('cleverswatch_update_gallery', function (event, response) {
    setTimeout(function() {
        jQuery('.product-content .images').replaceWith(response.content);
        jQuery('.images-popups-gallery').each(function() { // the containers for all your galleries
            jQuery(this).magnificPopup({
                delegate: "a[data-rel^='gallery']", // the selector for gallery item
                type: 'image',
                gallery: {
                    enabled:true
                }
            });
        });
    },300);
});