<?php
/**
 * Zoo_Mega_Menu_Walker
 *
 * @package  Zoo_Theme\Core\MegaMenu
 */
class Zoo_Mega_Menu_Walker extends Walker_Nav_Menu
{
    /**
     * Is mega menu enabled
     *
     * @var  boolean
     */
    protected $isEnabled = false;

    /**
     * Background Item
     *
     * @var string
     */
    protected $style = '';

    /**
     * Mega menu column
     *
     * @var int
     */
    protected $column = 3;

    /**
     * Starts the list before the elements are added.
     *
     * @see   Walker::start_lvl()
     */
    public function start_lvl(&$output, $depth = 0, $args = [])
    {
        $indent = str_repeat("\t", $depth);

        if (!$this->isEnabled) {
            $output .= "\n$indent<ul class=\"dropdown-submenu\">\n";
        } else {
            if ($depth == 0) {
                $output .= "\n<div\n$this->style class=\"mega-menu-content\">\n$indent<div class=\"row\">\n";
            } elseif ($depth == 1) {
                $output .= "\n$indent<div class=\"mega-menu-submenu\"><ul class=\"sub-menu check\">\n";
            } else {
                $output .= "\n$indent<ul class=\"sub-menu check\">\n";
            }
        }
    }

    /**
     * Ends the list of after the elements are added.
     *
     * @see   Walker::end_lvl()
     */
    public function end_lvl(&$output, $depth = 0, $args = [])
    {
        $indent = str_repeat("\t", $depth);

        if (!$this->isEnabled) {
            $output .= "\n$indent</ul>\n";
        } else {
            if ($depth == 0) {
                $output .= "\n$indent</div>\n$indent</div>\n";
            } elseif ($depth == 1) {
                $output .= "\n$indent</ul>\n$indent</div>";
            } else {
                $output .= "\n$indent</ul>\n";
            }
        }
    }

    /**
     * Start the element output.
     * Display item description text and classes
     *
     * @see   Walker::start_el()
     */
    public function start_el(&$output, $item, $depth = 0, $args = [], $id = 0)
    {
        $indent = $depth ? str_repeat("\t", $depth) : '';

        $item_content         = get_post_meta($item->ID, 'zoo_menu_item_content', true);
        $item_content_image   = get_post_meta($item->ID, 'zoo_menu_item_contentImage', true);
        $item_is_mega         = get_post_meta($item->ID, 'zoo_menu_item_mega', true);
        $item_mega_width      = get_post_meta($item->ID, 'zoo_menu_item_mega_width', true);
        $item_width           = get_post_meta($item->ID, 'zoo_menu_item_width', true);
        $item_hide_text       = get_post_meta($item->ID, 'zoo_menu_item_hide_text', true);
        $item_hot             = get_post_meta($item->ID, 'zoo_menu_item_hot', true);
        $item_new             = get_post_meta($item->ID, 'zoo_menu_item_new', true);
        $item_trending        = get_post_meta($item->ID, 'zoo_menu_item_trending', true);
        $item_mega_background = get_post_meta($item->ID, 'zoo_menu_item_background', true);

        $classes   = empty($item->classes) ? [] : (array)$item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        $this->style = '';
        $inline = '';

        if ($item_mega_background) {
            if (!empty($item_mega_background['image'])) {
                $inline = 'background-image:url(' . esc_attr($item_mega_background['image']) . ')';
            }

            if (!empty($item_mega_background['position'])) {
                $positionX = $item_mega_background['position']['x'];
                $positionY = $item_mega_background['position']['y'];
                if (!empty($item_mega_background['position']['custom'])) {
                    if ($item_mega_background['position']['custom']['x']) {
                        $positionX = $item_mega_background['position']['custom']['x'];
                    }
                    if ($item_mega_background['position']['custom']['y']) {
                        $positionY = $item_mega_background['position']['custom']['y'];
                    }
                }
                $inline .= ';background-position:' . esc_attr($positionX) . ' ' . esc_attr($positionY);
            }

            if (!empty($item_mega_background['repeat'])) {
                $inline .= ';background-repeat:' . esc_attr($item_mega_background['repeat']);
            }

            if (!empty($item_mega_background['size'])) {
                $inline .= ';background-size:' . esc_attr($item_mega_background['size']);
            }
            if (!empty($item_mega_background['attachment'])) {
                $inline .= ';background-attachment:' . esc_attr($item_mega_background['attachment']);
            }

            if (!empty($item_mega_background['color'])) {
                $inline .= ';background-color:' . esc_attr($item_mega_background['color']);
            }
        }

        if ($item_mega_width) {
            $inline .= ';width:' . esc_attr($item_mega_width);
        }

        if ($inline) {
            $this->style = 'style="' . $inline . '"';
        }

        /**
         * Filter the arguments for a single nav menu item.
         *
         * @since 4.4.0
         *
         * @param array  $args  An array of arguments.
         * @param object $item  Menu item data object.
         * @param int    $depth Depth of menu item. Used for padding.
         */
        $args = apply_filters('nav_menu_item_args', $args, $item, $depth);

        /**
         * Check if this is top level and is mega menu
         * Add Bootstrap class for menu that has children
         */
        if (!$depth) {
            $this->isEnabled = $item_is_mega;
        }

        /**
         * Store mege menu panel's column
         */
        if (1 == $depth && intval($this->isEnabled)) {
            $columns      = array(
                '25.00%'  => 3,
                '33.33%'  => 4,
                '50.00%'  => 6,
                '66.66%'  => 8,
                '75.00%'  => 9,
                '100.00%' => 12,
            );
            $width        = $item_width ? $item_width : '25.00%';
            $this->column = $columns[$width];
        }

        /**
         * Add active class for current menu item
         */
        $active_classes = array(
            'current-menu-item',
            'current-menu-parent',
            'current-menu-ancestor',
        );
        $is_active      = array_intersect($classes, $active_classes);
        if (!empty($is_active)) {
            $classes[] = 'active';
        }

        if (in_array('menu-item-has-children', $classes)) {
            if (!$depth || ($depth && !intval($this->isEnabled))) {
                $classes[] = 'dropdown';
            }
            if (!$depth && intval($this->isEnabled)) {
                $classes[] = 'is-mega-menu';
            }
            if (!intval($this->isEnabled)) {
                $classes[] = 'hasmenu';
            }
        }

        /**
         * Filter the CSS class(es) applied to a menu item's list item element.
         *
         * @since 3.0.0
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param array  $classes The CSS classes that are applied to the menu item's `<li>` element.
         * @param object $item    The current menu item.
         * @param array  $args    An array of {@see wp_nav_menu()} arguments.
         * @param int    $depth   Depth of menu item. Used for padding.
         */
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        /**
         * Filter the ID applied to a menu item's list item element.
         *
         * @since 3.0.1
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param string $menu_id The ID that is applied to the menu item's `<li>` element.
         * @param object $item    The current menu item.
         * @param array  $args    An array of {@see wp_nav_menu()} arguments.
         * @param int    $depth   Depth of menu item. Used for padding.
         */
        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        if ($depth == 1 && intval($this->isEnabled)) {
            $class_names = ' class="col-md-' . $this->column . '"';
            $output .= $indent . '<div' . $id . $class_names . '>' . "\n";
            $output .= $indent . '<div class="menu-item-mega">';
        } else {
            $output .= $indent . '<li' . $id . $class_names . '>';
        }

        $atts           = [];
        $atts['title']  = !empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = !empty($item->target) ? $item->target : '';
        $atts['rel']    = !empty($item->xfn) ? $item->xfn : '';
        $atts['href']   = !empty($item->url) ? $item->url : '';
        $atts['class']  = '';
        /**
         * Add attributes for menu item link when this is not mega menu item
         */
        if (in_array('menu-item-has-children', $classes)) {
            $atts['class'] = 'dropdown-toggle';
        }

        if ($depth == 1 && intval($this->isEnabled)) {
            if ($item_hide_text) {
                $atts['class'] .= ' hide-text';
            }
        }

        /**
         * Filter the HTML attributes applied to a menu item's anchor element.
         *
         * @since 3.6.0
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param array  $atts   {
         *                       The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
         *
         * @type string  $title  Title attribute.
         * @type string  $target Target attribute.
         * @type string  $rel    The rel attribute.
         * @type string  $href   The href attribute.
         * }
         *
         * @param object $item   The current menu item.
         * @param array  $args   An array of {@see wp_nav_menu()} arguments.
         * @param int    $depth  Depth of menu item. Used for padding.
         */
        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        /** This filter is documented in wp-includes/post-template.php */
        $o_title = apply_filters('the_title', $item->title, $item->ID);

        /**
         * Filter a menu item's title.
         *
         * @since 4.4.0
         *
         * @param string $title The menu item's title.
         * @param object $item  The current menu item.
         * @param array  $args  An array of {@see wp_nav_menu()} arguments.
         * @param int    $depth Depth of menu item. Used for padding.
         */
        $title = apply_filters('nav_menu_item_title', $o_title, $item, $args, $depth);

        $badge = [];
        if ($item_hot || $item_new || $item_trending) {
            if ($item_hot) {
                $badge[] = '<span class="hot-badge items-badge">' . esc_html__('Hot', 'anon') . '</span>';
            }
            if ($item_new) {
                $badge[] = '<span class="new-badge items-badge">' . esc_html__('New', 'anon') . '</span>';
            }
            if ($item_trending) {
                $badge[] = '<span class="trending-badge items-badge">' . esc_html__('Trending', 'anon') . '</span>';
            }
        }
        if ($depth == 1 && intval($this->isEnabled)) {
            $item_output = '<a ' . $attributes . ' title="'.esc_attr($o_title).'">';
            if (!$item_hide_text) {
                $item_output .= $title;
            }
            $item_output .= implode($badge);
            if ($item_content_image) {
                $item_output .= '<img class="menu-item-content-image" src="'.esc_url($item_content_image).'" alt="'.esc_attr($title).'">';
            }
            $item_output .= '</a>';
            if (!empty($item_content)) {
                $item_output .= '<div class="mega-content">' . do_shortcode($item_content) . '</div>';
            }
        } else {
            $item_output = $args->before;


            $item_output .= '<a' . $attributes . ' title="'.esc_attr($o_title).'">';
            $item_output .= $args->link_before;
            if(!Empty($badge) && !$item_hide_text){
                $item_output .='<span class="menu-item-title">';
            }
            if (!$item_hide_text) {
                $item_output .= $title;
            }
            $item_output .= implode($badge);
            if(!Empty($badge) && !$item_hide_text){
                $item_output .='</span>';
            }
            $item_output .= $args->link_after;
            if ($item_content_image) {
                $item_output .= '<img class="menu-item-content-image" src="'.esc_url($item_content_image).'" alt="'.esc_attr($title).'">';
            }
            $item_output .= '</a>';
            $item_output .= $args->after;
        }

        /**
         * Filter a menu item's starting output.
         *
         * The menu item's starting output only includes `$args->before`, the opening `<a>`,
         * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
         * no filter for modifying the opening and closing `<li>` for a menu item.
         *
         * @since 3.0.0
         *
         * @param string $item_output The menu item's starting HTML output.
         * @param object $item        Menu item data object.
         * @param int    $depth       Depth of menu item. Used for padding.
         * @param array  $args        An array of {@see wp_nav_menu()} arguments.
         */
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    /**
     * Ends the element output, if needed.
     *
     * @see   Walker::end_el()
     */
    public function end_el(&$output, $item, $depth = 0, $args = [])
    {
        if ($depth == 1 && intval($this->isEnabled)) {
            $output .= "</div>\n";
            $output .= "</div>\n";
        } else {
            $output .= "</li>\n";
        }
    }
}
