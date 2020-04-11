<?php
/**
 * Comments
 *
 * @package     Zoo Theme
 * @version     1.0.0
 * @author      Zootemplate
 * @link        https://www.zootemplate.com/
 * @copyright   Copyright (c) 2020 ZooTemplate
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if (post_password_required()) {
    return;
}
if (have_comments()) : ?>
    <section id="comments-list" class="comments post-comments header-comment-block">
        <h3 class="title-block">
            <?php comments_number(esc_html__('0 Comments', 'anon'), esc_html__('1 Comment', 'anon'), esc_html__('% Comments', 'anon')); ?>
        </h3>
        <ol class="wrap-comments">
            <?php
            wp_list_comments(array(
                'type' => 'pings',
                'short_ping' => true,
                'callback' => 'zoo_custom_pings',
            ));
            wp_list_comments(array(
                'type' => 'comment',
                'callback' => 'zoo_custom_comments',
            )); ?>
        </ol>
        <?php $total_pages = get_comment_pages_count();
        if ($total_pages > 1) : ?>
            <div id="comments-nav-below" class="comments-navigation">
                <div class="wrap-pagination clearfix">
                    <?php paginate_comments_links(array('type' => 'list', 'prev_text' => '<i class="zoo-icon-arrow-left"></i>',
                        'next_text' => '<i class="zoo-icon-arrow-right"></i>')); ?></div>
            </div>
        <?php endif; ?>
        <?php
        if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')) :
            ?>
            <p class="no-comments"><?php esc_html_e('Comments are closed.', 'anon'); ?></p>
        <?php
        endif;
        ?>
    </section>
<?php endif ?>
<section class="wrap-comment-form">
    <div class="row">
        <?php
        $req = get_option('require_name_email');
        $aria_req = ($req ? " aria-required='true'" : '');
        $zoo_comment_args = array(
            'fields' => apply_filters('comment_form_default_fields', array(
                'author' => '<div class="wrap-text-field col-12 col-sm-4"><input id="author" placeholder="' . esc_attr__('Your name *', 'anon') . '" class="ipt text-field" name="author" aria-required="true" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30"' . $aria_req . ' /></div>',
                'email' => '<div class="wrap-text-field col-12 col-sm-4"><input id="email" placeholder="' . esc_attr__('Email *', 'anon') . '" class="ipt text-field" name="email" aria-required="true" type="text" value="' . esc_attr($commenter['comment_author_email']) . '" ' . $aria_req . ' /></div>',
                'url' => '<div class="wrap-text-field  col-12 col-sm-4"><input id="url" placeholder="' . esc_attr__('Website', 'anon') . '" class="ipt text-field" name="url" type="text" value="' . esc_attr($commenter['comment_author_url']) . '"/></div>',
            )),
            'comment_field' => '<div class="wrap-text-field col-12"><textarea id="comment" placeholder="' . esc_attr__('Comment', 'anon') . '" class="textarea text-field"  name="comment" cols="45" rows="8"  aria-required="true"></textarea></div>',
            'class_submit' => 'button',
            'label_submit' => esc_attr__('Post Comment', 'anon'),
            'comment_notes_after' => !!zoo_gdpr_consent() ? '<div class="wrap-text-field gdpr-consent col-12">' . zoo_gdpr_consent() . '</div>' : ''
        );
        comment_form($zoo_comment_args);
        ?>
    </div>
</section>