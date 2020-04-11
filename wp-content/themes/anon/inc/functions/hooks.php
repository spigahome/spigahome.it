<?php
/**
 * Hooks of Blog
 * All templates of theme will load by hook. User can change order and location at custom file.
 *
 */

/**
 * Hook follow blog layout
 * */
add_action('zoo_after_post_item_title', 'zoo_post_item_info', 10);
if (get_theme_mod('zoo_blog_layout', 'list') == 'list' && !is_single()) {
    add_action('zoo_after_post_item_title', 'zoo_post_item_thumbnail', 20);
} else {
    add_action('zoo_before_post_item_title', 'zoo_post_item_thumbnail', 10);
}


/**
 * zoo_breadcrumb
 * Get template breadcrumb
 * @uses use function and hook to zoo_before_main_content
 * */
if (!function_exists('zoo_breadcrumb')) {
    function zoo_breadcrumb()
    {
        get_template_part('inc/templates/breadcrumbs');
    }
}
add_action('zoo_before_main_content', 'zoo_breadcrumb', 10);

/**
 * zoo_blog_cover
 * Get template blog cover
 * @uses use function and hook to zoo_before_main_content
 * */
if (!function_exists('zoo_blog_cover')) {
    function zoo_blog_cover()
    {
        get_template_part('inc/templates/posts/blog', 'cover');
    }
}
add_action('zoo_before_main_content', 'zoo_blog_cover', 20);


/**
 * zoo_post_pagination
 * Get template post pagination for loop
 * @uses use function and hook to zoo_before_main_content
 * */
if (!function_exists('zoo_post_pagination')) {
    function zoo_post_pagination()
    {
        get_template_part('inc/templates/posts/post', 'pagination');
    }
}
add_action('zoo_after_loop_content', 'zoo_post_pagination', 10);

/**
 * zoo_post_item_thumbnail
 * Get template post item thumbnail for loop
 * @uses use function and hook to zoo_post_item_thumbnail
 * */
function zoo_post_item_thumbnail()
{
    get_template_part('inc/templates/posts/loop/thumbnail');
}

/**
 * zoo_post_item_info
 * Get template post item thumbnail for loop
 * @uses use function and hook to zoo_post_item_thumbnail
 * */
function zoo_post_item_info()
{
    get_template_part('inc/templates/posts/loop/post', 'info');;
}

/**
 * zoo_post_item_cat
 * Get template post item thumbnail for loop
 * @uses use function and hook to zoo_post_item_thumbnail
 * */
function zoo_post_item_cat()
{
    if (!has_post_thumbnail()) {
        get_template_part('inc/templates/posts/global/sticky');
    }
    if (get_theme_mod('zoo_enable_loop_cat_post', '1') == 1) {
        ?>
        <div class="list-cat">
            <?php if (get_theme_mod('zoo_blog_loop_post_info_style', '') == 'icon') { ?>
                <i class="cs-font clever-icon-document"></i>
                <?php
            }
            echo get_the_term_list(get_the_ID(), 'category', '', ', ', ''); ?></div>
    <?php }
}

add_action('zoo_before_post_item_title', 'zoo_post_item_cat', 20);

/**
 * zoo_post_item_thumbnail
 * Get template post item thumbnail for loop
 * @uses use function and hook to zoo_post_item_thumbnail
 * */
function zoo_post_item_readmore()
{
    if (get_theme_mod('zoo_enable_loop_readmore', '1') == 1 || get_the_title() == '') {
        ?>
        <a href="<?php echo esc_url(the_permalink()); ?>"
           class="readmore"><?php echo esc_html__('Read more', 'anon'); ?></a>
        <?php
    }
}

add_action('zoo_after_post_item', 'zoo_post_item_readmore', 10);

/**---------------------------------------------------------
 *----------------------SINGLE POST------------------------
 *----------------------------------------------------------*/

/**
 * zoo_single_post_sticky_label
 * Get sticky label template
 * @uses hook to zoo_before_single_post_title
 * */
function zoo_single_post_sticky_label()
{
    get_template_part('inc/templates/posts/global/sticky');
}

add_action('zoo_before_single_post_title', 'zoo_single_post_sticky_label', 10);

/**
 * zoo_single_post_sticky_label
 * Get sticky label template
 * @uses hook to zoo_before_single_post_title
 * */
function zoo_single_post_info()
{
    get_template_part('inc/templates/posts/single/post-info');
}

add_action('zoo_after_single_post_title', 'zoo_single_post_info', 10);

/**
 * zoo_single_post_media
 * Get media template of single post
 * @uses hook to zoo_before_single_content
 * */
function zoo_single_post_media()
{
    get_template_part('inc/templates/posts/single/media');
}

add_action('zoo_before_single_content', 'zoo_single_post_media', 10);

/**
 * !IMPORTANT This is default feature of WP, don't remove.
 * zoo_single_post_pagination
 * Get pagination template of single post
 * @uses hook to zoo_after_single_content
 * */
function zoo_single_post_pagination()
{
    get_template_part('inc/templates/pagination', 'detail');
}

add_action('zoo_after_single_content', 'zoo_single_post_pagination', 10);

/**
 * zoo_single_post_bottom_content
 * Get bottom post template of single post
 * Display share, tag, category.
 * @uses hook to zoo_after_single_content
 * */
function zoo_single_post_bottom_content()
{
    get_template_part('inc/templates/posts/single/bottom-post-content');
}

add_action('zoo_after_single_content', 'zoo_single_post_bottom_content', 20);

/**
 * zoo_single_post_about_author
 * Get temple about post author
 * Display post author.
 * @uses hook to zoo_single_post_bottom
 * */
function zoo_single_post_about_author()
{
    get_template_part('inc/templates/posts/single/about', 'author');
}

add_action('zoo_single_post_bottom', 'zoo_single_post_about_author', 10);

/**
 * zoo_single_post_navigation
 * Get template post navigation
 * Display next and previous post.
 * @uses hook to zoo_single_post_bottom
 * */
function zoo_single_post_navigation()
{
    get_template_part('inc/templates/posts/single/post', 'navigation');
}

add_action('zoo_single_post_bottom', 'zoo_single_post_navigation', 20);

/**
 * zoo_single_post_related
 * Get template related posts
 * Display related posts.
 * @uses hook to zoo_single_post_bottom
 * */
function zoo_single_post_related()
{
    get_template_part('inc/templates/posts/single/related', 'posts');
}

add_action('zoo_single_post_bottom', 'zoo_single_post_related', 30);



