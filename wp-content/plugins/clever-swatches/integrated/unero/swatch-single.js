//Put this js to bottom file script.js of theme
//After this comment below (line 1899)
/**
 * Document ready
 */
jQuery(document).bind('cleverswatch_update_gallery', function (event, response) {
    setTimeout(function() {
        jQuery('#product-images-content').replaceWith(response.content);
    },100);

    setTimeout(function() {
        unero.productThumbnailSlick();
        unero.productGallery();
    },300);
});