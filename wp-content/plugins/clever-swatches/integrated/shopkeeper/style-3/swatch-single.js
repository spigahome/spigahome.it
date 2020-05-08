
jQuery(document).bind('cleverswatch_update_gallery', function (event, response) {
    setTimeout(function () {
        jQuery(window).trigger('resize');
        var t = jQuery(document).find(".product-images-controller"),
            i = jQuery(".product-images-style-2 .product_images .product-image:not(.mobile), .product-images-style-3 .product_images .product-image:not(.mobile)"),
            s = jQuery(".product-images-style-2 .product-images-controller li a span.dot, .product-images-style-3 .product-images-controller li a span.dot"),
            n = jQuery(".product-images-wrapper"),
            o = jQuery(".site-header.sticky").outerHeight();
        jQuery(".product_layout_2").length > 0 && jQuery(".product_layout_2 .product-images-controller").css("top", n.offset().top),
            jQuery(window).scroll(function () {
                s.addClass("current"), i.each(function () {
                    var i = jQuery(this), n = jQuery(' a[href="#' + i.attr("id") + '"]').data("number");
                    i.offset().top + i.outerHeight() <= jQuery(".product-images-controller").offset().top - o ? (s.removeClass("current"),
                        s.eq(n).addClass("current")) : s.eq(n).removeClass("current");
                });
                var n = jQuery(".product_layout_2 .fluid-width-video-wrapper, .product_layout_3 .fluid-width-video-wrapper");
                n.length > 0 && (n.offset().top <= jQuery(".product-images-controller").offset().top - o ? jQuery("li.video-icon span.dot").addClass("current") : jQuery(".product-images-controller .video-icon .dot").removeClass("current"),
                n.offset().top + n.outerHeight() <= jQuery(".product-images-controller").offset().top && jQuery(".product-images-controller .video-icon .dot").removeClass("current"));
            }),
        jQuery(".single-product").length > 0 && jQuery('a[href*="#controller-navigation-image"]:not([href="#"])').click(function () {
            if (location.pathname.replace(/^\//, "") == this.pathname.replace(/^\//, "") && location.hostname == this.hostname) {
                var t = jQuery(this.hash);
                if (t = t.length ? t : jQuery("[name=" + this.hash.slice(1) + "]"), jQuery("#wpadminbar").length > 0) i = jQuery("#wpadminbar").outerHeight(); else var i = "";
                if (t.length) return jQuery("html, body").animate({
                    scrollTop: t.offset().top - jQuery(".site-header.sticky").outerHeight() - i
                }, 500), !1;
            }
        }), jQuery(".product-image.video .fluid-width-video-wrapper iframe") && jQuery(".product_layout_2 .product-images-controller .video-icon > a, .product_layout_3 .product-images-controller .video-icon > a").on("click", function (t) {
            jQuery(".product-image.video .fluid-width-video-wrapper iframe")[0].src += "&autoplay=1",
                t.preventDefault();
        });

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