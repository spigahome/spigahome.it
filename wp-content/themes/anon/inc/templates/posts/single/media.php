<?php
/**
 * The default template for media of post
 *
 * Display thumbnail, video, auto, gallery at here
 *
 * @package     Zoo Theme
 * @version     1.0.0
 * @author      Zootemplate
 * @link        https://www.zootemplate.com/
 * @copyright   Copyright (c) 2020 ZooTemplate
 */

if (has_post_format('gallery')) :
    $zoo_images = get_post_meta(get_the_ID(), '_format_gallery_images', true);
    if ($zoo_images) :
        wp_enqueue_style('slick');
        wp_enqueue_style('slick-theme');
        wp_enqueue_script('slick');
        ?>
        <div class="post-media single-image">
            <ul class="post-slider">
                <?php foreach ($zoo_images as $zoo_image) :
                    $zoo_the_image = wp_get_attachment_image_src($zoo_image, 'full-thumb');
                    $zoo_the_caption = get_post_field('post_excerpt', $zoo_image); ?>
                    <li><img src="<?php echo esc_url($zoo_the_image[0]); ?>"
                             <?php if ($zoo_the_caption) : ?>title="<?php echo esc_attr($zoo_the_caption); ?>"<?php endif; ?> />
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif;
elseif (has_post_format('video')) :
    $zoo_video = get_post_meta(get_the_ID(), '_format_video_embed', true);
    if ($zoo_video != ''):
        ?>
        <div class="post-media single-video">
            <?php if (wp_oembed_get($zoo_video)) :
                echo wp_oembed_get($zoo_video);
            else :
                echo ent2ncr($zoo_video);
            endif;
            ?>
        </div>
    <?php
    endif;
elseif (has_post_format('audio')) :
    $zoo_audio = get_post_meta(get_the_ID(), '_format_audio_embed', true);
    if ($zoo_audio != ''):
        ?>
        <div class="post-media audio single-audio">
            <?php
            if (wp_oembed_get($zoo_audio)) :
                echo wp_oembed_get($zoo_audio);
            else :
                echo do_shortcode('[audio mp3="' . esc_url($zoo_audio) . '"][/audio]');
            endif; ?>
        </div>
    <?php
    endif;
else : if (has_post_thumbnail()) : ?>
    <div class="post-media single-image">
        <?php the_post_thumbnail('full-thumb'); ?>
    </div>
<?php endif;
endif;