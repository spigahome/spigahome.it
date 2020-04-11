<?php
/**
 * Common Hooks
 *
 * Hooks used on both admin and public screens.
 *
 * @package  Zoo_Theme\Core\Common
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */

add_action('init', function () {

    if (is_admin()) {
        return; // Do not change text direction in admin screens
    }
    $settings = get_option(ZOO_SETTINGS_KEY);
    if (!isset($settings['enable_dev_mode'])) {
        return;
    }
    if ($settings['enable_dev_mode'] == 1) {
        session_start();
        global $wp_locale, $wp_styles;

        $text_direction = 'ltr';
        if (isset($_SESSION['text-direction'])) {
            $text_direction = $_SESSION['text-direction'];
        }
        if (!empty($_GET['text-direction'])) {
            $direction = sanitize_text_field($_GET['text-direction']);

            if (!in_array($direction, ['rtl', 'ltr'])) {
                return;
            }
            if ($text_direction != $direction) {
                $text_direction = $direction;
                $_SESSION['text-direction'] = $direction;
            }
        }

        if (!isset($wp_locale->text_direction) || $wp_locale->text_direction !== $text_direction) {
            $wp_locale->text_direction = $text_direction;
            if (!is_a($wp_styles, 'WP_Styles')) {
                $wp_styles = new WP_Styles();
            }
            $wp_styles->text_direction = $text_direction;
        }
    }
});

/**
 * AJAX get Wishlist products
 */
if (!function_exists('zoo_ajax_get_wishlist_items')) {
    function zoo_ajax_get_wishlist_items()
    {
        if (empty($_POST['wishlistItems'])) {
            wp_send_json(['html' => '<p>' . esc_html_e('Wishlist is empty.', 'anon') . '</p>']);
        }

        $items = json_decode($_POST['wishlistItems']);

        $args = [
            'post_type' => ['product', 'product_variation'],
            'suppress_filters' => true,
            'no_found_rows' => true,
            'posts_per_page' => -1,
            'ignore_sticky_posts' => true,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false
        ];

        foreach ($items as $item_id) {
            $args['post__in'][] = absint($item_id);
        }

        $query = new WP_Query(apply_filters('woocommerce_wishlist_products_query', $args));

        ob_end_clean();

        ob_start();

        if ($query->have_posts()) : ?>
            <h2 class="wishlist-panel-title zoo-popup-panel-title"><?php echo esc_html__('My Wishlist', 'anon') ?></h2>
            <div class="zoo-wrap-popup-content">
                <table class="wishlist-items-table">
                    <thead>
                    <tr>

                        <th class="product-thumbnail">
                        </th>
                        <th class="product-title" colspan="1">
                            <?php esc_html_e('Product', 'anon'); ?>
                        </th>
                        <th class="product-price">
                            <?php esc_html_e('Price', 'anon'); ?>
                        </th>
                        <th class="product-meta">
                            <?php esc_html_e('Product Meta', 'anon'); ?>
                        </th>
                        <?php if (!zoo_enable_catalog_mod()) {
                            ?>
                            <th class="product-cart">
                            </th>
                        <?php }
                        ?>
                        <th class="product-remove-wishlist"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php while ($query->have_posts()) : $query->the_post();
                        $product = wc_get_product(get_the_ID());
                        $stock_status = $product->get_stock_status();
                        ?>
                        <tr id="wishlist-item-row-<?php echo esc_attr(get_the_ID()); ?>">
                            <td class="product-thumbnail">
                                <a href="<?php the_permalink(); ?>"
                                   title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a>
                            </td>
                            <td class="product-title product-loop-title">
                                <a href="<?php the_permalink(); ?>"
                                   title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
                                <div class="content-mobile">
                                    <?php woocommerce_template_loop_price(); ?>
                                    <div class="product-meta">
                                        <span class="stock-status <?php echo esc_attr($stock_status) ?>">
                                        <?php
                                        if ($stock_status == 'instock') {
                                            esc_html_e('In stock', 'anon');
                                        } else if ($stock_status == 'onbackorder') {
                                            esc_html_e('Back Order', 'anon');
                                        } else {
                                            esc_html_e('Out of stock', 'anon');
                                        }
                                        ?>
                                        </span>
                                        <?php
                                        woocommerce_template_single_meta(); ?>
                                    </div>
                                    <div class="wrap-product-add-cart">
                                        <?php
                                        if ($product) {
                                            $args = array(
                                                'quantity' => 1,
                                                'class' => implode(' ', array_filter(array(
                                                    'button',
                                                    'product_type_' . $product->get_type(),
                                                    $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                                                    $product->supports('ajax_add_to_cart') ? 'ajax_add_to_cart' : '',
                                                ))),
                                                'attributes' => array(
                                                    'data-product_id' => get_the_ID(),
                                                    'data-product_sku' => $product->get_sku(),
                                                    'aria-label' => $product->add_to_cart_description(),
                                                    'rel' => 'nofollow',
                                                ),
                                            );
                                            $args['attributes']['aria-label'] = strip_tags($args['attributes']['aria-label']);
                                            wc_get_template('loop/add-to-cart.php', $args);
                                        }
                                        ?>
                                    </div>
                                    <a href="#" class="remove-from-wishlist"
                                       title="<?php echo esc_attr__('Remove', 'anon'); ?>"
                                       data-id="<?php echo esc_attr(get_the_ID()); ?>">
                                        <i class="zoo-icon-close"></i>
                                    </a>
                                </div>
                            </td>
                            <td class="product-price">
                                <?php woocommerce_template_loop_price(); ?>
                            </td>
                            <td class="product-meta">
                                <span class="stock-status <?php echo esc_attr($stock_status) ?>">
                                <?php
                                if ($stock_status == 'instock') {
                                    esc_html_e('In stock', 'anon');
                                } else if ($stock_status == 'onbackorder') {
                                    esc_html_e('Back Order', 'anon');
                                } else {
                                    esc_html_e('Out of stock', 'anon');
                                }
                                ?>
                                </span>
                                <?php
                                woocommerce_template_single_meta(); ?>
                            </td>
                            <?php
                            if (!zoo_enable_catalog_mod()) {
                                ?>
                                <td class="product-cart">
                                    <?php
                                    if ($product) {
                                        $args = array(
                                            'quantity' => 1,
                                            'class' => implode(' ', array_filter(array(
                                                'button',
                                                'product_type_' . $product->get_type(),
                                                $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                                                $product->supports('ajax_add_to_cart') ? 'ajax_add_to_cart' : '',
                                            ))),
                                            'attributes' => array(
                                                'data-product_id' => get_the_ID(),
                                                'data-product_sku' => $product->get_sku(),
                                                'aria-label' => $product->add_to_cart_description(),
                                                'rel' => 'nofollow',
                                            ),
                                        );
                                        $args['attributes']['aria-label'] = strip_tags($args['attributes']['aria-label']);
                                        wc_get_template('loop/add-to-cart.php', $args);
                                    }
                                    ?>
                                </td>
                            <?php } ?>
                            <td class="product-remove-wishlist">
                                <a href="#" class="remove-from-wishlist"
                                   title="<?php echo esc_attr__('Remove', 'anon'); ?>"
                                   data-id="<?php echo esc_attr(get_the_ID()); ?>">
                                    <i class="zoo-icon-close"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php
        endif;

        wp_send_json(['html' => ob_get_clean()]);
    }

    add_action('wp_ajax_zoo_get_wishlist_products', 'zoo_ajax_get_wishlist_items');
    add_action('wp_ajax_nopriv_zoo_get_wishlist_products', 'zoo_ajax_get_wishlist_items');
}

/**
 * AJAX get compare products
 */
if (!function_exists('zoo_ajax_get_compare_items')) {
    function zoo_ajax_get_compare_items()
    {
        if (empty($_POST['compareItems'])) {
            wp_send_json(['html' => '<p>' . esc_html_e('No products to compare.', 'anon') . '</p>']);
        }

        global $post;

        $items = json_decode($_POST['compareItems']);

        $args = [
            'post_type' => ['product', 'product_variation'],
            'suppress_filters' => true,
            'no_found_rows' => true,
            'posts_per_page' => -1,
            'ignore_sticky_posts' => true,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false
        ];

        foreach ($items as $item_id) {
            $args['post__in'][] = absint($item_id);
        }

        $query = new WP_Query(apply_filters('woocommerce_products_compare_query', $args));

        ob_end_clean();

        ob_start();

        if ($query->have_posts()) : ?>
            <h2 class="products-compare-panel-title zoo-popup-panel-title">
                <?php echo esc_html__('Compare Products', 'anon') ?>
            </h2>
            <div class="zoo-wrap-popup-content">
                <table class="products-compare-table">
                    <tbody>
                    <tr>
                        <th></th>
                        <?php while ($query->have_posts()) : $query->the_post(); ?>
                            <td class="product-remove products-compare-row-<?php echo esc_attr($post->ID); ?>">
                                <a href="#" class="remove-from-products-compare"
                                   title="<?php echo esc_attr__('Remove', 'anon'); ?>"
                                   data-id="<?php echo esc_attr($post->ID); ?>"><i class="zoo-icon-close"></i></a>
                            </td>
                        <?php endwhile; ?>
                    </tr>
                    <tr>
                        <th></th>
                        <?php while ($query->have_posts()) : $query->the_post(); ?>
                            <td class="product-title products-compare-row-<?php echo esc_attr($post->ID); ?>">
                                <a href="<?php the_permalink(); ?>"
                                   title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
                            </td>
                        <?php endwhile; ?>
                    </tr>
                    <tr>
                        <th></th>
                        <?php while ($query->have_posts()) : $query->the_post(); ?>
                            <td class="product-thumbnail products-compare-row-<?php echo esc_attr($post->ID); ?>">
                                <a href="<?php the_permalink(); ?>"
                                   title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a>
                            </td>
                        <?php endwhile; ?>
                    </tr>
                    <tr>
                        <th><?php esc_html_e('SKU', 'anon') ?></th>
                        <?php while ($query->have_posts()) : $query->the_post(); ?>
                            <td class="product-meta products-compare-row-<?php echo esc_attr($post->ID); ?>">
                                <?php
                                $item = wc_get_product($post->ID);
                                echo esc_html($item->get_sku());
                                unset($item);
                                ?>
                            </td>
                        <?php endwhile; ?>
                    </tr>
                    <tr>
                        <th><?php esc_html_e('Rating', 'anon') ?></th>
                        <?php while ($query->have_posts()) : $query->the_post(); ?>
                            <td class="product-rating products-compare-row-<?php echo esc_attr($post->ID); ?>">
                                <?php do_action('zoo_woo_loop_rating'); ?>
                            </td>
                        <?php endwhile; ?>
                    </tr>
                    <tr>
                        <th><?php esc_html_e('Price', 'anon') ?></th>
                        <?php while ($query->have_posts()) : $query->the_post(); ?>
                            <td class="product-price products-compare-row-<?php echo esc_attr($post->ID); ?>">
                                <?php woocommerce_template_loop_price(); ?>
                            </td>
                        <?php endwhile; ?>
                    </tr>
                    <?php if (!zoo_enable_catalog_mod()) { ?>
                        <tr>
                            <th><?php esc_html_e('Add to cart', 'anon') ?></th>
                            <?php while ($query->have_posts()) : $query->the_post(); ?>
                                <td class="product-cart products-compare-row-<?php echo esc_attr($post->ID); ?>">
                                    <?php
                                    $product = wc_get_product($post->ID);
                                    if ($product) {
                                        $args = array(
                                            'quantity' => 1,
                                            'class' => implode(' ', array_filter(array(
                                                'button',
                                                'product_type_' . $product->get_type(),
                                                $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                                                $product->supports('ajax_add_to_cart') ? 'ajax_add_to_cart' : '',
                                            ))),
                                            'attributes' => array(
                                                'data-product_id' => $product->get_id(),
                                                'data-product_sku' => $product->get_sku(),
                                                'aria-label' => $product->add_to_cart_description(),
                                                'rel' => 'nofollow',
                                            ),
                                        );
                                        $args['attributes']['aria-label'] = strip_tags($args['attributes']['aria-label']);
                                        wc_get_template('loop/add-to-cart.php', $args);
                                    }
                                    ?>
                                </td>
                            <?php endwhile; ?>
                        </tr>
                    <?php } ?>
                    <tr>
                        <th><?php esc_html_e('Description', 'anon') ?></th>
                        <?php while ($query->have_posts()) : $query->the_post(); ?>
                            <td class="description products-compare-row-<?php echo esc_attr($post->ID); ?>">
                                <?php echo apply_filters('woocommerce_short_description', $post->post_excerpt) ?>
                            </td>
                        <?php endwhile; ?>
                    </tr>
                    <tr class="attribute-head-group-row">
                        <?php
                        $available_atts = [];
                        foreach ($query->posts as $post_object) {
                            $woo_product = wc_get_product($post_object);
                            $product_atts = $woo_product->get_attributes();
                            foreach ($product_atts as $product_att) {
                                $tmp_att_name = $product_att->get_name();
                                if (in_array($tmp_att_name, $available_atts))
                                    continue;
                                $available_atts[] = $tmp_att_name;
                            }
                        }
                        ?>
                        <th><?php esc_html_e('Attributes', 'anon') ?></th>
                        <td colspan="<?php echo esc_attr(count($query->posts)) ?>">
                            <?php echo implode(', ', array_map('wc_attribute_label', $available_atts)); ?>
                        </td>
                    </tr>
                    <?php
                    foreach ($available_atts as $att_name) {
                        echo '<tr class="attribute-group-row">';
                        echo '<th>' . wc_attribute_label($att_name) . '</th>';
                        while ($query->have_posts()) : $query->the_post();
                            $product_has_att = false;
                            $product_atts_with_key = [];
                            $woo_product = wc_get_product($post);
                            $product_atts = $woo_product->get_attributes();
                            foreach ($product_atts as $product_att) {
                                $product_att_name = $product_att->get_name();
                                $product_atts_with_key[$product_att_name] = $product_att;
                                if ($product_att_name === $att_name)
                                    $product_has_att = true;
                            }
                            if ($product_has_att) {
                                $product_att = $product_atts_with_key[$att_name];
                                echo '<td class="products-compare-row-' . esc_attr($post->ID) . '">';
                                $values = [];
                                if ($product_att->is_taxonomy()) {
                                    $attribute_taxonomy = $product_att->get_taxonomy_object();
                                    $attribute_values = wc_get_product_terms($woo_product->get_id(), $att_name, ['fields' => 'all']);
                                    foreach ($attribute_values as $attribute_value) {
                                        $value_name = esc_html($attribute_value->name);
                                        if ($attribute_taxonomy->attribute_public) {
                                            $values[] = '<a href="' . esc_url(get_term_link($attribute_value->term_id, $att_name)) . '">' . $value_name . '</a>';
                                        } else {
                                            $values[] = $value_name;
                                        }
                                    }
                                } else {
                                    $values = $product_att->get_options();
                                    foreach ($values as $value) {
                                        $value = make_clickable(esc_html($value));
                                    }
                                }
                                echo implode(', ', $values);
                                echo '</td>';
                            } else {
                                echo '<td class="products-compare-row-' . esc_attr($post->ID) . '">&#9867;</td>';
                            }
                        endwhile;
                        echo '</tr>';
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        <?php
        endif;

        wp_send_json(['html' => ob_get_clean()]);
    }

    add_action('wp_ajax_zoo_get_compare_products', 'zoo_ajax_get_compare_items');
    add_action('wp_ajax_nopriv_zoo_get_compare_products', 'zoo_ajax_get_compare_items');
}
