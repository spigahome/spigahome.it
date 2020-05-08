<?php namespace Cafe;

/**
 * CleverWidgetsManager
 *
 * @author CleverSoft <hello.cleversoft@gmail.com>
 * @package CAFE
 */
final class WidgetsFunctionsPro
{

    /**
     * Nope constructor
     */
    static function init()
    {
        static $self = null;
        /**
         * Ajax search function
         * */
        if (null === $self) {
            $self = new self;
            add_action('wp_ajax_cafe_live_search_results', [$self, '_ajax_get_search_results'], 10, 0);
            add_action('wp_ajax_nopriv_cafe_live_search_results', [$self, '_ajax_get_search_results'], 10, 0);
            add_action('wp_ajax_cafe_user_login', [$self, '_ajax_user_login'], 10, 0);
            add_action('wp_ajax_nopriv_cafe_user_login', [$self, '_ajax_user_login'], 10, 0);
            add_filter('woocommerce_add_to_cart_fragments', [$self, '_added_cart_fragments']);
        }

        /**
         * Add drop-down icon for menus
         *
         * @see  https://developer.wordpress.org/reference/hooks/nav_menu_item_title/
         */
        add_filter('nav_menu_item_title', function ($title, $item, $args, $depth) {
            if (in_array('menu-item-has-children', $item->classes)) {
                $title .= '<span class="cafe-menu-arrow"></span>';
            }
            return $title;
        }, 25, 4);
    }

    /**
     * Ajax search result function
     * */
    function _ajax_get_search_results()
    {
        if (isset($_POST['searchQuery'])) {
            $queries = json_decode(stripslashes($_POST['searchQuery']));
            $query_args = [
                's' => sanitize_text_field($queries->queryString),
                'post_type' => 'any'
            ];

            if (!empty($queries->productCat)) {
                $query_args['post_type'] = 'product';
                if ($queries->productCat != 'all') {
                    $query_args['tax_query'][] = [
                        'taxonomy' => 'product_cat',
                        'terms' => intval($queries->productCat)
                    ];
                }
            } elseif (!empty($queries->postType)) {
                $query_args['post_type'] = $queries->postType;
            }
            if (!empty($queries->maxResult)) {
                $query_args['posts_per_page'] = $queries->maxResult;
            } else {
                $query_args['posts_per_page'] = 6;
            }
            $search_result = apply_filters('cafe_search_ajax_results', get_posts($query_args), $queries);
            ob_start();
            if ($search_result && is_array($search_result)) {
                global $post;
                if ($queries->postType == 'product' && $queries->layout=='grid') {
                    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
                    remove_action('zoo_product_button', 'woocommerce_template_loop_add_to_cart', 0);
                    remove_action('zoo_after_product_button_hover_effect', 'woocommerce_template_loop_add_to_cart', 5);
                    remove_action('woocommerce_after_shop_loop_item', 'zoo_product_button_hover_effect', 15);
                    remove_action('woocommerce_before_shop_loop_item_title', 'zoo_product_button_hover_effect', 15);
                    foreach ($search_result as $post) : setup_postdata($post);
                        wc_get_template_part('content', 'product');
                    endforeach;
                } else {
                    foreach ($search_result as $post) : setup_postdata($post); ?>
                        <article class="search-result-item cafe-col">
                            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                                <div class="wrap-img-result">
                                    <?php the_post_thumbnail('thumbnail'); ?>
                                </div>
                                <div class="wrap-result-content">
                                    <h3 class="title-result"><?php the_title(); ?></h3>
                                    <?php
                                    if ('product' === $post->post_type && class_exists('WC_Product')) { ?>
                                        <p class="price amount">
                                            <?php
                                            $prdct = new \WC_Product($post->ID);
                                            echo wp_kses_post($prdct->get_price_html());
                                            ?>
                                        </p>
                                        <?php
                                    }?>
                                </div>
                            </a>
                        </article>
                    <?php endforeach;
                }
                wp_reset_postdata();
            } else {
                ?>
                <div class="no-result">
                    <?php
                    esc_html_e('We don\'t find any results matching with this search.', 'cafe-pro');
                    ?>
                </div>
                <?php
            }
            $output = ob_get_contents();
            ob_end_clean();
            echo wp_kses_post($output);
            exit;
        }
    }

    /**
     * Ajax check user login function
     * */
    function _ajax_user_login() {
        if (isset($_POST['loginParam'])) {
            $queries = json_decode(stripslashes($_POST['loginParam']));
            $creds = array(
                'user_login' => sanitize_text_field($queries->username),
                'user_password' => sanitize_text_field($queries->password),
                'remember' => $queries->rememberme
            );

            $user = wp_signon($creds, false);

            if (is_wp_error($user)) {
                echo $user->get_error_message();
            }
        }
        exit;
    }

    /**
     * Added Cart Fragments
     * Update cart fragments
     * Need for issue wrong count when site enable Cache HTML.
     * @return array custom cart fragments of theme after updateFragments worked.
     */
    function _added_cart_fragments($fragments)
    {
        ob_start();
        $cart = WC()->instance()->cart;
        $fragments['.cafe-cart-count'] = '<span class="cafe-cart-count">'.$cart->get_cart_contents_count().'</span>';
        $fragments['.cafe-cart-subtotal'] = '<span class="cafe-cart-subtotal">'.$cart->get_cart_subtotal().'</span>';
        ob_start();
        return $fragments;
    }
}

// Initialize.
WidgetsFunctionsPro::init();
