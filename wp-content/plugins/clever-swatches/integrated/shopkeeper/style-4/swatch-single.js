jQuery(document).bind('cleverswatch_update_gallery', function (event, response) {
    setTimeout(function () {
        jQuery(window).trigger('resize');
        if (jQuery(".easyzoom").length) if (jQuery(window).width() > 1024) {
            var t = jQuery(".easyzoom").easyZoom({
                loadingNotice: "",
                errorNotice: "",
                preventClicks: !1,
                linkAttribute: "href"
            }).data("easyZoom");
        } else jQuery(".easyzoom a").click(function (e) {
            jQuery.preventDefault();
        });
    }, 100);
});