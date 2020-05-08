<?php
/**
 * Css class wrap main content
 * @uses: call function zoo_main_content_css
 * @return $main_class css class wrap main content
 * */
function zoo_main_content_css()
{
    $main_class = 'main-content';
    if (!is_single()) {
        $sidebar = get_theme_mod('zoo_blog_sidebar_config', 'right');
        if (is_active_sidebar(get_theme_mod('zoo_blog_sidebar', 'sidebar')) && $sidebar != '') {
            $main_class .= ' has-' . $sidebar . '-sidebar has-sidebar';
        } else {
            $main_class .= ' without-sidebar';
        }
    } else {
        $sidebar = zoo_single_post_sidebar();
        if ($sidebar != '' && is_active_sidebar(get_theme_mod('zoo_blog_single_sidebar', 'sidebar'))) {
            $main_class .= ' has-' . $sidebar . '-sidebar has-sidebar';
        } else {
            $main_class .= ' without-sidebar';
        }
    }
    return apply_filters('zoo_main_content_css', $main_class);
}

/**
 * Css class wrap loop content
 * @uses: call function zoo_loop_content_css
 * @return $loop_class css class wrap main content
 * */
function zoo_loop_content_css()
{
    $zoo_block_layout = get_theme_mod('zoo_blog_layout', 'list');
    $loop_class = 'wrap-loop-content col-12';
    $zoo_sidebar = get_theme_mod('zoo_blog_sidebar_config', 'right');
    if(is_author() && get_the_author_meta('description')!=''){
        $loop_class .= ' col-md-9';
    }else if(is_active_sidebar(get_theme_mod('zoo_blog_sidebar', 'sidebar')) && $zoo_sidebar != '') {
        $loop_class .= ' col-md-9';
    }
    $loop_class .= ' ' . $zoo_block_layout . '-layout';
    return apply_filters('zoo_loop_content_css', $loop_class);
}

/**
 * Css class wrap loop content
 * @uses: call function zoo_single_content_css
 * @return $wrap_class css class wrap main single content
 * */
function zoo_single_content_css()
{
    $sidebar = zoo_single_post_sidebar();
    $wrap_class = 'col-12 col-md-9 wrap-post-content-without-sidebar';
    if ($sidebar != '' && is_active_sidebar(get_theme_mod('zoo_blog_single_sidebar', 'sidebar'))) {
        $wrap_class = 'col-12 col-md-9 wrap-post-content-has-sidebar';
    }
    return apply_filters('zoo_single_content_css', $wrap_class);
}

/**
 * Css class wrap post loop item
 * @uses: call function zoo_post_loop_css
 * @return $loop_class css class wrap main content
 * */
function zoo_post_loop_item_css()
{
    if (get_theme_mod('zoo_blog_layout', 'list') == 'list') {
        $class = 'post-loop-item list-layout-item col-12';
    } else {
        $class = 'post-loop-item grid-layout-item ';
        switch (get_theme_mod('zoo_blog_cols', '3')) {
            case '2':
                $class .= "col-12 col-sm-6";
                break;
            case '3':
                $class .= "col-12 col-sm-4";
                break;
            case '4':
                $class .= "col-12 col-sm-6 col-md-3";
                break;
            case '5':
                $class .= "col-12 col-md-1-5";
                break;
            case '6':
                $class .= "col-12 col-sm-4 col-md-2";
                break;
            default:
                $class .= "col-12";
                break;
        }
    }
    return apply_filters('zoo_post_loop_item_css', $class);
}

/**
 * Password form template for detail post
 * @uses: hook to the_password_form
 * @return html of password form
 * */
if (!function_exists('zoo_password_form')) {
    function zoo_password_form()
    {
        global $post;
        $zoo_id = 'pwbox-' . (empty($post->ID) ? rand() : $post->ID);
        $zoo_form = '<div class="zoo-form-login"><form action="' . esc_url(site_url('wp-login.php?action=postpass', 'login_post')) . '" method="post"><h4>
    ' . esc_html__('To view this protected post, enter the password below:', 'anon') . '</h4>
    <input name="post_password" id="' . $zoo_id . '" type="password" size="20" maxlength="20" placeholder="' . esc_attr__('Enter Password', 'anon') . ' " /><br><input type="submit" name="Submit" value="' . esc_attr__('Submit', 'anon') . '" />
    </form></div>
    ';
        return $zoo_form;
    }
}
add_filter('the_password_form', 'zoo_password_form');

/**
 * Site layout
 * @uses: call function zoo_site_layout()
 * @return site layout
 * */
if (!function_exists('zoo_site_layout')) {
    function zoo_site_layout()
    {
        $zoo_site_layout = get_theme_mod('zoo_site_layout', 'normal');
        if (is_page() && get_post_meta(get_the_ID(), 'zoo_site_layout', true) != '' && get_post_meta(get_the_ID(), 'zoo_site_layout', true) != 'inherit') {
            $zoo_site_layout = get_post_meta(get_the_ID(), 'zoo_site_layout', true);
        }
        if (isset($_GET['zoo_site_layout'])) {
            $zoo_site_layout = $_GET['zoo_site_layout'];
        }
        return $zoo_site_layout;
    }
}
/**
 * Site full width
 * @uses: call function zoo_site_width()
 * @return css for site full width
 * */
if (!function_exists('zoo_site_width')) {
    function zoo_site_width()
    {
        $zoo_site_width = '';
        if (zoo_site_layout() != 'full-width') {
            $zoo_site_width = get_theme_mod('zoo_site_max_width', '1530');
            if (is_page() && get_post_meta(get_the_ID(), 'zoo_site_max_width', true) != '') {
                $zoo_site_width = get_post_meta(get_the_ID(), 'zoo_site_max_width', true);
            }
            if (isset($_GET['zoo_site_max_width'])) {
                $zoo_site_width = $_GET['zoo_site_max_width'];
            }
        }
        if ($zoo_site_width == 0 || $zoo_site_width == '') {
            $zoo_site_width = '100%;padding-left:40px;padding-right:40px';
        } else {
            $zoo_site_width = $zoo_site_width . 'px';
        }
        return $zoo_site_width;
    }
}

/**
 * Check single post sidebar
 * @uses: call function zoo_single_post_sidebar()
 * @return sidebar option
 * */
if (!function_exists('zoo_single_post_sidebar')) {
    function zoo_single_post_sidebar()
    {
        $sidebar = get_post_meta(get_the_id(), 'zoo_blog_single_sidebar_config', true);
        if ($sidebar != 'inherit' && $sidebar) {
            return $sidebar;
        } else {
            return get_theme_mod('zoo_blog_single_sidebar_config', '');
        }
    }
}
/**
 * Check GDPG plugin is installed
 * @uses: call function zoo_gdpr_consent()
 * @return boolean
 * */
if (!function_exists('zoo_gdpr_consent')) {
    function zoo_gdpr_consent()
    {
        if (class_exists('GDPR')) {
            return GDPR::get_consent_checkboxes();
        } else {
            return false;
        }
    }
}
/**
 * Get Preset Color
 * @uses: call function zoo_theme_preset()
 * @return color
 * */
if (!function_exists('zoo_theme_preset')) {
    function zoo_theme_preset()
    {
        $preset = get_theme_mod('zoo_color_preset', 'default');
        if (isset($_GET['preset'])) {
            $preset = $_GET['preset'];
        }
        switch ($preset) {
            case 'custom':
                $preset = get_theme_mod('zoo_primary_color', '');
                break;
            case 'black':
                $preset = '#000';
                break;
            case 'blue':
                $preset = '#0366d6';
                break;
            case 'red':
                $preset = '#fc2929';
                break;
            case 'yellow':
                $preset = '#FFFF00';
                break;
            case 'green':
                $preset = '#269f42';
                break;
            case 'grey':
                $preset = '#778899';
                break;
            default:
                $preset = '';
                break;
        }
        return $preset;
    }
}
/**
 * Get list image sizes
 * @return array image size
 */
if (!function_exists('zoo_get_image_sizes')) {
    function zoo_get_image_sizes()
    {
        global $_wp_additional_image_sizes;
        $output = array();
        $imgs_size = get_intermediate_image_sizes();
        foreach ($imgs_size as $size) :
            if (in_array($size, array('thumbnail', 'medium', 'medium_large', 'large'))) :
                $output[$size] = ucwords(str_replace(array('_', ' - '), array(
                        ' ',
                        ' '
                    ), $size)) . ' (' . get_option("{$size}_size_w") . 'x' . get_option("{$size}_size_h") . ')';
            elseif (isset($_wp_additional_image_sizes[$size])) :
                $output[$size] = ucwords(str_replace(array('_', '-'), array(
                        ' ',
                        ' '
                    ), $size)) . ' (' . $_wp_additional_image_sizes[$size]['width'] . 'x' . $_wp_additional_image_sizes[$size]['height'] . ')';
            endif;
        endforeach;
        $output['full'] = esc_html__('Full', 'anon');

        return $output;
    }
}
/**
 * Get all pages
 * @return array pages
 * */
function zoo_get_pages()
{
    $pages = array();
    $all_pages = get_posts(array(
            'posts_per_page' => -1,
            'post_type' => 'page',
        )
    );
    $pages[''] = esc_html__('None', 'anon');
    if (!empty($all_pages) && !is_wp_error($all_pages)) {
        foreach ($all_pages as $page) {
            $pages[$page->ID] = $page->post_title;
        }
    }
    return $pages;
}