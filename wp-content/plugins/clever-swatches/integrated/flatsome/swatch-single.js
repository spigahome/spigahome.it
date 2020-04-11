<script>
jQuery(document).bind('cleverswatch_update_gallery', function (event, response) {
    if(jQuery('.custom-product-page')[0]){
        var gallery=jQuery(response.content).find('.product-gallery-slider');
        jQuery('#product-' + response.product_id).find('.images').replaceWith(gallery);
        var thumb=jQuery(response.content).find('.product-thumbnails');
        jQuery('#product-' + response.product_id).find('.product-thumbnails').replaceWith(thumb);
    }else{
        jQuery('#product-' + response.product_id).find('.product-gallery').html(response.content);
    }
    if(jQuery('.product-lightbox')[0]){
        jQuery('#product-' + response.product_id).find('.product-gallery').html(response.content);
    }
    setTimeout(function() {
        jQuery('#product-' + response.product_id).find('.slider').each(function () {
            jQuery(this).flickity(jQuery(this).data('flickity-options'));
            jQuery(".has-image-zoom .slide").easyZoom({loadingNotice:"",preventClicks:!1});
            if(!jQuery('.product-quick-view-container')[0]) {
                jQuery(this).wc_product_gallery();
            }
        });
    },300);
});
</script>