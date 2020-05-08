<?php
/**
 * My Wislist page template
 *
 * @package  Zoo_Theme\WooCommerce\Wishlist
 */
get_header();?>
    <main id="site-main-content" class="main-content wishlist-content">
        <div class="container">
            <?php
                if (empty($_COOKIE['zooWishlistItems']) || !$wishlist_items = json_decode($_COOKIE['zooWishlistItems'])) :
                    ?><p><?php esc_html_e('Wishlist is Empty.', 'anon') ?></p><?php
                else :
                    global $post, $product;

                    $args = [
                        'post_type' => 'product',
                        'paged' => get_query_var('paged') ? get_query_var('paged') : 1,
                        'ignore_sticky_posts' => true
                    ];

                    foreach ($wishlist_items as $item_id) {
                        $args['post__in'][] = absint($item_id);
                    }

                    $query = new WP_Query(apply_filters('woocommerce_wishlist_products_query', $args));

                    ?>
                    <div class="zoo-wishlist-panel">
                        <div class="zoo-wishlist-panel-inner">
                            <h2 class="wishlist-panel-title zoo-popup-panel-title"><?php echo esc_html__('My Wishlist', 'anon') ?></h2>
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
                                    <?php if(!zoo_enable_catalog_mod()){?>
                                    <th class="product-cart">
                                    </th>
                                    <?php }?>
                                    <th class="product-remove-wishlist"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php while ($query->have_posts()) : $query->the_post();
                                    $product = wc_get_product(get_the_ID());
                                    $stock_status = $product->get_stock_status();
                                    ?>
                                    <tr id="wislist-item-row-<?php echo esc_attr(get_the_ID()); ?>">
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
                                        <?php if(!zoo_enable_catalog_mod() ){ ?>
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
                        <?php }?>
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
                    </div>
                    <?php
                    wp_reset_postdata();
                endif;
            ?>
        </div>
    </main>
<?php
get_footer();
