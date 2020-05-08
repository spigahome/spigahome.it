<?php
/**
 * View template for Product Image 360 View
 *
 * @package     Zoo Theme
 * @version     1.0.0
 * @author      Zootemplate
 * @link        https://www.zootemplate.com/
 * @copyright   Copyright (c) 2020 ZooTemplate
 */

$images =get_post_meta(get_the_id(),'zoo_single_product_image_360');

if (!empty($images)) {
    wp_enqueue_script('spritespin');
    $imgs = array();
    foreach ($images as $img) {
        $img=wp_get_attachment_url($img);
        if ($img)
            $imgs[] = $img;
    }
    $img=$imgs[0];
    $imgs = implode(",", $imgs);
    ?>
    <div class="product-image-360-view" data-zoo-config='<?php echo esc_attr($imgs) ?>'>
        <a href="#" class="button product-360-view-control" title="<?php esc_attr_e('360 Product View','anon')?>"><i class="cs-font clever-icon-360-2"></i></a>
        <div class="mask-product-360-view"></div>
        <div class="zoo-wrap-img-360-view">
            <div class="zoo-wrap-content-view">
                <img src="<?php echo esc_url($img);?>">
            </div>
            <ul class="zoo-wrap-control-view">
                <li class="zoo-control-view zoo-prev-item"><i class="cs-font clever-icon-arrow-left-5"></i></li>
                <li class="zoo-control-view zoo-center"><i class="cs-font clever-icon-360-2"></i></li>
                <li class="zoo-control-view zoo-next-item"><i class="cs-font clever-icon-arrow-right-5"></i></li>
            </ul>
        </div>
    </div>
    <?php
} ?>

