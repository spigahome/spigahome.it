<?php
/**
 * Custom functions for pass Team reviewer
 */
// Author Link Social
if (!function_exists('zoo_frame_social_author')) {
    function zoo_frame_social_author($contactmethods)
    {
        $contactmethods['twitter'] = esc_html__('Twitter Username', 'clever-addons');
        $contactmethods['facebook'] = esc_html__('Facebook Username', 'clever-addons');
        $contactmethods['google'] = esc_html__('Google Plus Username', 'clever-addons');
        $contactmethods['tumblr'] = esc_html__('Tumblr Username', 'clever-addons');
        $contactmethods['instagram'] = esc_html__('Instagram Username', 'clever-addons');
        $contactmethods['pinterest'] = esc_html__('Pinterest Username', 'clever-addons');

        return $contactmethods;
    }
}

add_filter('user_contactmethods', 'zoo_frame_social_author', 10, 1);

/**
 * Remove Script Version
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
if (!function_exists('zoo_remove_script_version')) {
    function zoo_remove_script_version($src)
    {
        if (strpos($src, $_SERVER['SERVER_NAME']) != false) {
            $parts = explode('?', $src);
            return $parts[0];
        } else {
            return $src;
        }
    }
}
add_filter('script_loader_src', 'zoo_remove_script_version', 15, 1);
add_filter('style_loader_src', 'zoo_remove_script_version', 15, 1);

/**
 * Disable the emoji's
 */
function zoo_disable_emojis()
{
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    add_filter('tiny_mce_plugins', 'zoo_disable_emojis_tinymce');
    add_filter('wp_resource_hints', 'zoo_disable_emojis_remove_dns_prefetch', 10, 2);
}

if (get_theme_mod('zoo_disable_emojis', 1) == 1) {
    add_action('init', 'zoo_disable_emojis');
}
/**
 * Filter function used to remove the tinymce emoji plugin.
 *
 * @param array $plugins
 * @return array Difference betwen the two arrays
 */
function zoo_disable_emojis_tinymce($plugins)
{
    if (is_array($plugins)) {
        return array_diff($plugins, array('wpemoji'));
    } else {
        return array();
    }
}

/**
 * Remove emoji CDN hostname from DNS prefetching hints.
 *
 * @param array $urls URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed for.
 * @return array Difference betwen the two arrays.
 */
function zoo_disable_emojis_remove_dns_prefetch($urls, $relation_type)
{
    if ('dns-prefetch' == $relation_type) {
        /** This filter is documented in wp-includes/formatting.php */
        $emoji_svg_url = apply_filters('emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/');

        $urls = array_diff($urls, array($emoji_svg_url));
    }

    return $urls;
}