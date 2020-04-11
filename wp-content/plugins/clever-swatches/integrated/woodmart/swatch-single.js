jQuery(document).bind('cleverswatch_update_gallery', function (event, response) {
    setTimeout(function () {
        woodmartThemeModule.productImages();
        woodmartThemeModule.productImagesGallery();
    },300);
});