var EnvatoWizard = (function($) {

    var t;

    // callbacks from form button clicks.
    var callbacks = {
        install_child: function(btn) {
            var installer = new ChildTheme();
            installer.init(btn);
        },
        install_plugins: function(btn) {
            var plugins = new PluginManager();
            plugins.init(btn);
        },
        install_content: function(btn) {
            var content = new ContentManager();
            content.init(btn);
        }
    };

    function window_loaded() {
        $(".field-tooltip").on("hover", function() {
            $(this).find(".tip-content").toggle(350);
        });
        // init button clicks:
        $(".button-next").on("click", function(e) {
            e.preventDefault();
            var loading_button = dtbaker_loading_button(this);
            if (!loading_button) {
                return false;
            }
            var data_callback = $(this).data("callback");
            if (data_callback && typeof callbacks[data_callback] !== "undefined") {
                // we have to process a callback before continue with form submission
                callbacks[data_callback](this);
                return false;
            } else {
                return true;
            }
        });
        $(".theme-presets a").on("click", function(e) {
            e.preventDefault();
            var $ul = $(this).parents("ul").first();
            $ul.find(".current").removeClass("current");
            var $li = $(this).parents("li").first();
            $li.addClass("current");
            var newcolor = $(this).data("style");
            $("#new_style").val(newcolor);
            return false;
        });
    }

    function ChildTheme() {
        var complete, notice = $("#envato-setup-child-theme-text");

        function ajax_callback(r) {
            notice.empty();
            if (typeof r.done !== "undefined") {
                notice.addClass("lead success");
                notice.html(r.message);
                complete();
            } else {
                notice.addClass("lead error");
                notice.html(r.error);
            }
        }

        function do_ajax() {
            childThemeName = $("#child_theme_name").val();
            jQuery.post(envato_setup_params.ajaxurl, {
                action: "envato_setup_child_theme",
                wpnonce: envato_setup_params.wpnonce,
                cThemeName: childThemeName
            }, ajax_callback).fail(ajax_callback);
        }

        return {
            init: function(btn) {
                complete = function() {
                    setTimeout(function() {
                        window.location.href = btn.href;
                    }, 1200);
                };
                do_ajax();
            }
        }
    }

    function PluginManager() {

        var complete;
        var items_completed = 0;
        var current_item = "";
        var $current_node;
        var current_item_hash = "";

        function ajax_callback(response, status, jqXHR) {
            if (typeof response === "object" && response.message) {
                $current_node.find("span").text(response.message);
                if (response.url) {
                    current_item_hash = response.hash;
                    jQuery.post(response.url, response, function(response2) {
                        process_current();
                        $current_node.find("span").text(response.message + envato_setup_params.verify_text);
                    }).fail(ajax_callback);
                } else {
                    find_next();
                }
            } else {
                // Some plugins do redirection after being activated successfully.
                if (typeof response == "string" && jqXHR.getResponseHeader('content-type').indexOf('text/html') >= 0) {
                    $current_node.find("span").text("Success");
                } else {
                    $current_node.find("span").text("Error");
                }
                find_next();
            }
        }

        function process_current() {
            if (current_item) {
                jQuery.post(envato_setup_params.ajaxurl, {
                    action: "envato_setup_plugins",
                    wpnonce: envato_setup_params.wpnonce,
                    slug: current_item
                }, ajax_callback).fail(ajax_callback);
            }
        }

        function find_next() {
            var do_next = false;
            if ($current_node) {
                if (!$current_node.data("done_item")) {
                    items_completed++;
                    $current_node.data("done_item", 1);
                }
                $current_node.find(".spinner").css("display", "none");
            }
            var $li = $(".envato-wizard-plugins li");
            $li.each(function() {
                if (current_item == "" || do_next) {
                    current_item = $(this).data("slug");
                    $current_node = $(this);
                    process_current();
                    do_next = false;
                } else if ($(this).data("slug") == current_item) {
                    do_next = true;
                }
            });
            if (items_completed >= $li.length) {
                // finished all plugins!
                complete();
            }
        }

        return {
            init: function(btn) {
                $(".envato-wizard-plugins").addClass("installing");
                complete = function() {
                    window.location.href = btn.href;
                };
                find_next();
            }
        }
    }

    function ContentManager() {

        var complete;
        var items_completed = 0;
        var current_item = "";
        var $current_node;
        var current_item_hash = "";

        function ajax_callback(response) {
            var currentSpan = $current_node.find("span");
            if (typeof response == "object" && typeof response.message !== "undefined") {
                currentSpan.text(response.message);
                if (typeof response.url !== "undefined") {
                    // we have an ajax url action to perform.
                    if (response.hash === current_item_hash) {
                        currentSpan.text("Failed");
                        find_next();
                    } else {
                        current_item_hash = response.hash;
                        jQuery.post(response.url, response, ajax_callback).fail(ajax_callback); // recuurrssionnnnn
                    }
                } else if (typeof response.done !== "undefined") {
                    // finished processing this plugin, move onto next
                    find_next();
                } else {
                    // error processing this plugin
                    find_next();
                }
            } else {
                console.log(response);
                // error - try again with next plugin
                currentSpan.text("Error");
                find_next();
            }
        }

        function process_current() {
            if (current_item) {
                var $check = $current_node.find("input:checkbox");
                if ($check.is(":checked")) {
                    jQuery.post(envato_setup_params.ajaxurl, {
                        action: "envato_setup_content",
                        wpnonce: envato_setup_params.wpnonce,
                        content: current_item
                    }, ajax_callback).fail(ajax_callback);
                } else {
                    $current_node.find("span").text("Skipped");
                    setTimeout(find_next, 300);
                }
            }
        }

        function find_next() {
            var do_next = false;
            if ($current_node) {
                if (!$current_node.data("done_item")) {
                    items_completed++;
                    $current_node.data("done_item", 1);
                }
                $current_node.find(".spinner").css("display", "none");
            }
            var $items = $("tr.envato_default_content");
            var $enabled_items = $("tr.envato_default_content input:checked");
            $items.each(function() {
                if (current_item == "" || do_next) {
                    current_item = $(this).data("content");
                    $current_node = $(this);
                    process_current();
                    do_next = false;
                } else if ($(this).data("content") == current_item) {
                    do_next = true;
                }
            });
            if (items_completed >= $items.length) {
                // finished all items!
                complete();
            }
        }

        return {
            init: function(btn) {
                $(".envato-setup-pages").addClass("installing");
                $(".envato-setup-pages").find("input").prop("disabled", true);
                complete = function() {
                    window.location.href = btn.href;
                };
                find_next();
            }
        }
    }

    function dtbaker_loading_button(btn) {

        var $button = jQuery(btn);
        if ($button.data("done-loading") == "yes") return false;
        var existing_text = $button.text();
        var existing_width = $button.outerWidth();
        var loading_text = "⡀⡀⡀⡀⡀⡀⡀⡀⡀⡀⠄⠂⠁⠁⠂⠄";
        var completed = false;

        $button.css("width", existing_width);
        $button.addClass("dtbaker_loading_button_current");
        var _modifier = $button.is("input") || $button.is("button") ? "val" : "text";
        $button[_modifier](loading_text);
        //$button.attr("disabled",true);
        $button.data("done-loading", "yes");

        var anim_index = [0, 1, 2];

        // animate the text indent
        function moo() {
            if (completed) return;
            var current_text = "";
            // increase each index up to the loading length
            for (var i = 0; i < anim_index.length; i++) {
                anim_index[i] = anim_index[i] + 1;
                if (anim_index[i] >= loading_text.length) anim_index[i] = 0;
                current_text += loading_text.charAt(anim_index[i]);
            }
            $button[_modifier](current_text);
            setTimeout(function() {
                moo();
            }, 60);
        }

        moo();

        return {
            done: function() {
                completed = true;
                $button[_modifier](existing_text);
                $button.removeClass("dtbaker_loading_button_current");
                $button.attr("disabled", false);
            }
        }

    }

    return {
        init: function() {
            t = this;
            $(window_loaded);
        }
    }

})(jQuery);

EnvatoWizard.init();
