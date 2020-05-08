<?php
/**
 * My Compare page template
 *
 * @package  Zoo_Theme\WooCommerce\Wishlist
 */
get_header();?>
    <main id="site-main-content" class="main-content products-compare-content">
        <div class="container">
            <?php
                if (empty($_COOKIE['zooProductsCompareItems']) || !$wishlist_items = json_decode($_COOKIE['zooProductsCompareItems'])) :
                    ?><p><?php esc_html_e('No products to compare.', 'anon') ?></p><?php
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

                    $query = new WP_Query(apply_filters('woocommerce_compare_products_query', $args));

                    ?>
                    <div class="products-compare-panel">
                        <div class="products-compare-panel-inner">
                            <h2 class="products-compare-panel-title">
                                <?php echo esc_html__('My Compare', 'anon') ?>
                            </h2>
                            <table class="products-compare-table">
                                <tbody>
                                <tr>
                                    <th></th>
                                    <?php while ($query->have_posts()) : $query->the_post(); ?>
                                        <td class="product-remove products-compare-row-<?php esc_attr($post->ID); ?>">
                                            <a href="#" class="remove-from-products-compare" title="<?php echo esc_attr__('Remove', 'anon'); ?>"
                                               data-id="<?php echo esc_attr($post->ID); ?>"><i class="zoo-icon-close"></i></a>
                                        </td>
                                    <?php endwhile; ?>
                                </tr>
                                <tr>
                                    <th></th>
                                    <?php while ($query->have_posts()) : $query->the_post(); ?>
                                        <td class="product-title products-compare-row-<?php esc_attr($post->ID); ?>">
                                            <a href="<?php the_permalink(); ?>"
                                               title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
                                        </td>
                                    <?php endwhile; ?>
                                </tr>
                                <tr>
                                    <th></th>
                                    <?php while ($query->have_posts()) : $query->the_post(); ?>
                                        <td class="product-thumbnail products-compare-row-<?php esc_attr($post->ID); ?>">
                                            <a href="<?php the_permalink(); ?>"
                                               title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a>
                                        </td>
                                    <?php endwhile; ?>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e('SKU','anon')?></th>
                                    <?php while ($query->have_posts()) : $query->the_post(); ?>
                                        <td class="product-meta products-compare-row-<?php esc_attr($post->ID); ?>">
                                            <?php
                                            $item = wc_get_product($post->ID);
                                            echo ent2ncr($item->get_sku());
                                            unset($item);
                                            ?>
                                        </td>
                                    <?php endwhile; ?>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e('Rating','anon')?></th>
                                    <?php while ($query->have_posts()) : $query->the_post(); ?>
                                        <td class="product-rating products-compare-row-<?php esc_attr($post->ID); ?>">
                                            <?php do_action('zoo_woo_loop_rating'); ?>
                                        </td>
                                    <?php endwhile; ?>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e('Price','anon')?></th>
                                    <?php while ($query->have_posts()) : $query->the_post(); ?>
                                        <td class="product-price products-compare-row-<?php esc_attr($post->ID); ?>">
                                            <?php woocommerce_template_loop_price(); ?>
                                        </td>
                                    <?php endwhile; ?>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e('Description','anon')?></th>
                                    <?php while ($query->have_posts()) : $query->the_post(); ?>
                                        <td class="description products-compare-row-<?php esc_attr($post->ID); ?>">
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
                                    <th><?php esc_html_e('Attributes','anon')?></th>
                                    <td colspan="<?php esc_attr(count($query->posts)) ?>">
                                        <?php echo implode(', ', array_map('wc_attribute_label', $available_atts)); ?>
                                    </td>
                                </tr>
                                <?php
                                    foreach ($available_atts as $att_name) {
                                        echo '<tr class="attribute-group-row">';
                                        echo '<th>' . ent2ncr(wc_attribute_label($att_name)) . '</th>';
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
                                                echo '<td class="products-compare-row-' . esc_attr($post->ID) .  '">';
                                                $values = [];
                                                if ($product_att->is_taxonomy()) {
                                                    $attribute_taxonomy = $product_att->get_taxonomy_object();
                                                    $attribute_values = wc_get_product_terms($woo_product->get_id(), $att_name, ['fields' => 'all']);
                                                    foreach ($attribute_values as $attribute_value ) {
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
                                                echo '<td class="products-compare-row-' . esc_attr($post->ID) .  '">&#9867;</td>';
                                            }
                                        endwhile;
                                        echo '</tr>';
                                    }
                                    if(!zoo_enable_catalog_mod()){
                                ?>
                                <tr>
                                    <th><?php esc_html_e('Add to cart','anon')?></th>
                                    <?php while ($query->have_posts()) : $query->the_post(); ?>
                                        <td class="product-cart products-compare-row-<?php esc_attr($post->ID); ?>">
                                            <?php woocommerce_template_loop_add_to_cart(); ?>
                                        </td>
                                    <?php endwhile; ?>
                                </tr>
                                        <?php }?>
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
