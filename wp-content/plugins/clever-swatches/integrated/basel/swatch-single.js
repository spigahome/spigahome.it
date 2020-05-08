/*Add bottom file theme.min.js*/
jQuery(document).bind('cleverswatch_update_gallery', function (event, response) {
    setTimeout(function () {
        baselThemeModule.init();
    }, 500);
});