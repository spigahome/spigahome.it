<?php
/**
 * Product custom meta
 * @package     Zoo Theme
 * @version     3.0.0
 * @author      Zootemplate
 * @link        https://www.zootemplate.com/
 * @copyright   Copyright (c) 2020 ZooTemplate
 *
 */

global $product;
?>
<div class="wrap-custom-meta">
    <?php

    ob_start();
    /**
     * This is custom hook, allow add another template to this hook
     * */

    do_action('zoo_custom_product_meta');
    $custom_meta = ob_get_contents();
    ob_end_clean();

    if (!empty($custom_meta)) { ?>
        <div class="wrap-left-custom-meta">
            <?php
            echo wp_kses_post($custom_meta);
            ?>
        </div>
        <?php
    }
    do_action('zoo_product_enable_sold_per_day');
    ?>
</div>