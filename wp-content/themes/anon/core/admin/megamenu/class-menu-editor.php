<?php
/**
 * Zoo_Mega_Menu_Edit
 *
 * @package  Core\MegaMenu
 */
final class Zoo_Mega_Menu_Edit
{
    /**
     * Constructor.
     */
    function __construct()
    {
        add_action('admin_enqueue_scripts', array( $this, 'scripts' ));
        add_action('admin_footer-nav-menus.php', array( $this, 'modal' ));
        add_action('admin_footer-nav-menus.php', array( $this, 'templates' ));
        add_action('wp_ajax_zoo_save_menu_item_data', array( $this, 'save_menu_item_data' ));
    }

    /**
     * Load scripts on Menus page only
     *
     * @param string $hook
     */
    public function scripts($hook)
    {
        if ('nav-menus.php' !== $hook) {
            return;
        }

        wp_enqueue_style('zoo-mega-menu', ZOO_THEME_URI.'core/assets/css/mega-menu.min.css', array( 'media-views', 'wp-color-picker' ), ZOO_THEME_VERSION);

        wp_enqueue_media();

        wp_enqueue_script('zoo-mega-menu', ZOO_THEME_URI.'core/assets/js/mega-menu'.ZOO_JS_SUFFIX, array( 'jquery', 'jquery-ui-resizable', 'backbone', 'underscore', 'wp-color-picker' ), ZOO_THEME_VERSION, true);

        wp_enqueue_style('cmm4e-nav-menu');
        wp_localize_script('zoo-mega-menu', 'cleverMenuItems', $this->get_item_settings($this->get_selected_menu_id()));
    }

    /**
     * Prints HTML of modal on footer
     */
    public function modal()
    {
        ?>
		<div id="zoo-settings" tabindex="0" class="zoo-settings">
			<div class="zoo-modal media-modal wp-core-ui">
				<button type="button" class="button-link media-modal-close zoo-modal-close">
					<span class="media-modal-icon"><span class="screen-reader-text"><?php esc_html_e('Close', 'anon') ?></span></span>
				</button>
				<div class="media-modal-content">
					<div class="zoo-frame-menu media-frame-menu">
						<div class="zoo-menu media-menu"></div>
					</div>
					<div class="zoo-frame-title media-frame-title"></div>
					<div class="zoo-frame-content media-frame-content">
						<div class="zoo-content">
							<!--							<span class="spinner"></span>-->
						</div>
					</div>
					<div class="zoo-frame-toolbar media-frame-toolbar">
						<div class="zoo-toolbar media-toolbar">
							<div class="zoo-toolbar-primary media-toolbar-primary search-form">
								<button type="button" class="button zoo-button zoo-button-save media-button button-primary button-large"><?php esc_html_e('Save Changes', 'anon') ?></button>
								<button type="button" class="button zoo-button zoo-button-cancel media-button button-secondary button-large"><?php esc_html_e('Cancel', 'anon') ?></button>
								<span class="spinner"></span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="media-modal-backdrop zoo-modal-backdrop"></div>
		</div>
		<?php
    }

    /**
     * Prints underscore template on footer
     */
    public function templates()
    {
        ?>
        <script type="text/template" id="zoo-tmpl-menus">
            <% if ( depth == 0 ) { %>
            <a href="#" class="media-menu-item active" data-title="<?php esc_attr_e('Mega Menu Content', 'anon' ) ?>" data-panel="mega"><?php esc_html_e('Mega Menu', 'anon' ) ?></a>
            <a href="#" class="media-menu-item" data-title="<?php esc_attr_e('Mega Menu Background', 'anon' ) ?>" data-panel="background"><?php esc_html_e('Background', 'anon' ) ?></a>
            <div class="separator"></div>
            <% } else if ( depth >= 1 ) { %>
            <a href="#" class="media-menu-item active" data-title="<?php esc_attr_e('Menu Content', 'anon' ) ?>" data-panel="content"><?php esc_html_e('Menu Content', 'anon' ) ?></a>
            <% } %>
        </script>

        <script type="text/template" id="zoo-tmpl-title">
            <h1><%= title %></h1>
        </script>

        <script type="text/template" id="zoo-tmpl-mega">
            <%
                var itemId = data['menu-item-db-id'],
                    megaData = cleverMenuItems[itemId];
            %>
            <div id="zoo-panel-mega" class="zoo-panel-mega zoo-panel">
                <div class="wrap-head-panel-mega">
                    <p class="zoo-panel-box">
                        <label>
                            <input class="zoo-enable-mega-menu" type="checkbox" name="<%= zooMegaMenu.getFieldName( 'mega', itemId ) %>" value="1" <% if ( megaData.mega ) { print( 'checked="checked"' ); } %> data-for-menu-item="menu-item-<%= itemId %>">
                            <?php esc_html_e('Enable Mega Menu', 'anon') ?>
                        </label>
                    </p>
                    <p class="zoo-panel-box-large">
                        <label>
                            <?php esc_html_e('Mega Menu Width', 'anon') ?>
                            <input type="text" name="<%= zooMegaMenu.getFieldName( 'mega_width', itemId ) %>"
                                   placeholder="100%" value="<%= megaData.megaWidth %>">
                        </label>
                    </p>
                    <p>
            			<label>
            				<input type="checkbox" name="<%= zooMegaMenu.getFieldName( 'hideText', itemId ) %>" value="1" <% if ( megaData.hideText ) { print( 'checked="checked"' ); } %> >
            				<?php esc_html_e( 'Hide Text', 'anon' ) ?>
            			</label>
            		</p>
            		<p>
            			<label>
            				<input type="checkbox" name="<%= zooMegaMenu.getFieldName( 'hot', itemId ) %>" value="1" <% if ( megaData.hot ) { print( 'checked="checked"' ); } %> >
            				<?php esc_html_e( 'Hot label', 'anon' ) ?>
            			</label>
            		</p>

            		<p>
            			<label>
            				<input type="checkbox" name="<%= zooMegaMenu.getFieldName( 'new', itemId ) %>" value="1" <% if ( megaData.new ) { print( 'checked="checked"' ); } %> >
            				<?php esc_html_e( 'New label', 'anon' ) ?>
            			</label>
            		</p>

            		<p>
            			<label>
            				<input type="checkbox" name="<%= zooMegaMenu.getFieldName( 'trending', itemId ) %>" value="1" <% if ( megaData.trending ) { print( 'checked="checked"' ); } %> >
            				<?php esc_html_e( 'Trending label', 'anon' ) ?>
            			</label>
            		</p>
                </div>
                <div id="zoo-mega-content" class="zoo-mega-content">
                    <div class="background-grid-container">
                        <div class="zoo-background-grid">
                            <div class="grid-separator"></div>
                            <div class="grid-separator"></div>
                            <div class="grid-separator"></div>
                            <div class="grid-separator"></div>
                            <div class="grid-separator"></div>
                            <div class="grid-separator"></div>
                            <div class="grid-separator"></div>
                            <div class="grid-separator"></div>
                            <div class="grid-separator"></div>
                            <div class="grid-separator"></div>
                            <div class="grid-separator"></div>
                            <div class="grid-separator"></div>
                        </div>
                    </div>
                    <%
                    var items = _.filter( children, function( item ) {
                    return item.subDepth == 0;
                    } );
                    %>
                    <% _.each( items, function( item, index ) { %>

                    <div class="zoo-submenu-column" data-width="<%= item.megaData.width %>">
                        <ul>
                            <li class="menu-item menu-item-depth-<%= item.subDepth %>">
                                <% if ( item.megaData.icon ) { %>
                                <i class="<%= item.megaData.icon %>"></i>
                                <% } %>
                                <%= item.data['menu-item-title'] %>
                                <% if ( item.subDepth == 0 ) { %>
                                <span class="zoo-column-handle zoo-resizable-e"><i class="dashicons dashicons-arrow-left-alt2"></i></span>
                                <span class="zoo-column-handle zoo-resizable-w"><i class="dashicons dashicons-arrow-right-alt2"></i></span>
                                <input type="hidden" name="<%= zooMegaMenu.getFieldName( 'width', item.data['menu-item-db-id'] ) %>"
                                       value="<%= item.megaData.width %>" class="menu-item-width">
                                <% } %>
                            </li>
                        </ul>
                    </div>

                    <% } ) %>
                </div>
            </div>
        </script>

        <script type="text/template" id="zoo-tmpl-background">
            <%
                var itemId = data['menu-item-db-id'],
                    megaData = cleverMenuItems[itemId];
            %>
            <div id="zoo-panel-background" class="zoo-panel-background zoo-panel">
                <div class="background-image">
                    <label><?php esc_html_e('Background Image', 'anon') ?></label><br>
                    <span class="background-image-preview">
            			<% if ( megaData.background.image ) { %>
            				<img src="<%= megaData.background.image %>">
            			<% } %>
            		</span>

                    <button type="button"
                            class="button remove-button <% if ( ! megaData.background.image ) { print( 'hidden' ) } %>"><?php esc_html_e('Remove', 'anon') ?></button>
                    <button type="button" class="button upload-button"
                            id="background_image-button"><?php esc_html_e('Select Image', 'anon') ?></button>

                    <input type="hidden" name="<%= zooMegaMenu.getFieldName( 'background.image', itemId ) %>"
                           value="<%= megaData.background.image %>">
                </div>
                <div class="wrap-background-extend-options">
                    <p class="background-color">
                        <label><?php esc_html_e('Background Color', 'anon') ?></label><br>
                        <input type="text" class="background-color-picker"
                               name="<%= zooMegaMenu.getFieldName( 'background.color', itemId ) %>"
                               value="<%= megaData.background.color %>">
                    </p>
                    <p class="background-repeat">
                        <label><?php esc_html_e('Background Repeat', 'anon') ?></label><br>
                        <select name="<%= zooMegaMenu.getFieldName( 'background.repeat', itemId ) %>">
                            <option value="no-repeat"
                            <% if ( 'no-repeat' == megaData.background.repeat ) { print( 'selected="selected"' ) }
                            %>><?php esc_html_e('No Repeat', 'anon') ?></option>
                            <option value="repeat"
                            <% if ( 'repeat' == megaData.background.repeat ) { print( 'selected="selected"' ) }
                            %>><?php esc_html_e('Tile', 'anon') ?></option>
                            <option value="repeat-x"
                            <% if ( 'repeat-x' == megaData.background.repeat ) { print( 'selected="selected"' ) }
                            %>><?php esc_html_e('Tile Horizontally', 'anon') ?></option>
                            <option value="repeat-y"
                            <% if ( 'repeat-y' == megaData.background.repeat ) { print( 'selected="selected"' ) }
                            %>><?php esc_html_e('Tile Vertically', 'anon') ?></option>
                        </select>
                    </p>
                    <p class="background-position background-position-x">
                        <label><?php esc_html_e('Background Position', 'anon') ?></label><br>
                        <select name="<%= zooMegaMenu.getFieldName( 'background.position.x', itemId ) %>">
                            <option value="left"
                            <% if ( 'left' == megaData.background.position.x ) { print( 'selected="selected"' ) }
                            %>><?php esc_html_e('Left', 'anon') ?></option>
                            <option value="center"
                            <% if ( 'center' == megaData.background.position.x ) { print( 'selected="selected"' ) }
                            %>><?php esc_html_e('Center', 'anon') ?></option>
                            <option value="right"
                            <% if ( 'right' == megaData.background.position.x ) { print( 'selected="selected"' ) }
                            %>><?php esc_html_e('Right', 'anon') ?></option>
                            <option value="custom"
                            <% if ( 'custom' == megaData.background.position.x ) { print( 'selected="selected"' ) }
                            %>><?php esc_html_e('Custom', 'anon') ?></option>
                        </select>
                        <input type="text" name="<%= zooMegaMenu.getFieldName( 'background.position.custom.x', itemId ) %>" value="<%= megaData.background.position.custom.x %>" class="<% if ( 'custom' != megaData.background.position.x ) { print( 'hidden' ) } %>">
                    </p>
                    <p class="background-position background-position-y">
                        <select name="<%= zooMegaMenu.getFieldName( 'background.position.y', itemId ) %>">
                            <option value="top"
                            <% if ( 'top' == megaData.background.position.y ) { print( 'selected="selected"' ) }
                            %>><?php esc_html_e('Top', 'anon') ?></option>
                            <option value="center"
                            <% if ( 'center' == megaData.background.position.y ) { print( 'selected="selected"' ) }
                            %>><?php esc_html_e('Middle', 'anon') ?></option>
                            <option value="bottom"
                            <% if ( 'bottom' == megaData.background.position.y ) { print( 'selected="selected"' ) }
                            %>><?php esc_html_e('Bottom', 'anon') ?></option>
                            <option value="custom"
                            <% if ( 'custom' == megaData.background.position.y ) { print( 'selected="selected"' ) }
                            %>><?php esc_html_e('Custom', 'anon') ?></option>
                        </select>
                        <input
                                type="text"
                                name="<%= zooMegaMenu.getFieldName( 'background.position.custom.y', itemId ) %>"
                                value="<%= megaData.background.position.custom.y %>"
                                class="<% if ( 'custom' != megaData.background.position.y ) { print( 'hidden' ) } %>">
                    </p>
                    <p class="background-attachment">
                        <label><?php esc_html_e('Background Attachment', 'anon') ?></label><br>
                        <select name="<%= zooMegaMenu.getFieldName( 'background.attachment', itemId ) %>">
                            <option value="scroll"
                            <% if ( 'scroll' == megaData.background.attachment ) { print( 'selected="selected"' ) }
                            %>><?php esc_html_e('Scroll', 'anon') ?></option>
                            <option value="fixed"
                            <% if ( 'fixed' == megaData.background.attachment ) { print( 'selected="selected"' ) }
                            %>><?php esc_html_e('Fixed', 'anon') ?></option>
                        </select>
                    </p>
                </div>
            </div>
        </script>

		<script type="text/template" id="zoo-tmpl-content">
            <%
                var itemId = data['menu-item-db-id'],
                megaData = cleverMenuItems[itemId];
            %>
            <div id="zoo-panel-content" class="zoo-panel-content zoo-panel">
                <div class="wrap-head-panel-mega">
            		<p>
            			<label>
            				<input type="checkbox" name="<%= zooMegaMenu.getFieldName( 'hideText', data['menu-item-db-id'] ) %>" value="1" <% if ( megaData.hideText ) { print( 'checked="checked"' ); } %> >
            				<?php esc_html_e( 'Hide Text', 'anon' ) ?>
            			</label>
            		</p>
            		<p>
            			<label>
            				<input type="checkbox" name="<%= zooMegaMenu.getFieldName( 'hot', data['menu-item-db-id'] ) %>" value="1" <% if ( megaData.hot ) { print( 'checked="checked"' ); } %> >
            				<?php esc_html_e( 'Hot label', 'anon' ) ?>
            			</label>
            		</p>

            		<p>
            			<label>
            				<input type="checkbox" name="<%= zooMegaMenu.getFieldName( 'new', data['menu-item-db-id'] ) %>" value="1" <% if ( megaData.new ) { print( 'checked="checked"' ); } %> >
            				<?php esc_html_e( 'New label', 'anon' ) ?>
            			</label>
            		</p>

            		<p>
            			<label>
            				<input type="checkbox" name="<%= zooMegaMenu.getFieldName( 'trending', data['menu-item-db-id'] ) %>" value="1" <% if ( megaData.trending ) { print( 'checked="checked"' ); } %> >
            				<?php esc_html_e( 'Trending label', 'anon' ) ?>
            			</label>
            		</p>
            	</div>
                <p class="content-image">
            		<label><?php esc_html_e( 'Image', 'anon' ) ?></label><br>
            		<span class="content-image-preview">
            			<% if ( megaData.contentImage ) { %>
            				<img src="<%= megaData.contentImage %>">
            			<% } %>
            		</span>
                    <br>
            		<button type="button" class="button remove-button <% if ( ! megaData.contentImage ) { print( 'hidden' ) } %>"><?php esc_html_e( 'Remove', 'anon' ) ?></button>
            		<button type="button" class="button upload-button" id="content_image-button"><?php esc_html_e( 'Select Image', 'anon' ) ?></button>
            		<input type="hidden" name="<%= zooMegaMenu.getFieldName( 'contentImage', itemId ) %>" value="<%= megaData.contentImage %>">
            	</p>
            	<p>
            		<textarea name="<%= zooMegaMenu.getFieldName( 'content', data['menu-item-db-id'] ) %>" class="widefat" rows="20" contenteditable="true"><%= megaData.content %></textarea>
            	</p>

            	<p class="description"><?php esc_html_e( 'Allow HTML and Shortcodes', 'anon' ) ?></p>
            </div>
		</script>
		<?php
    }

    /**
     * Get menu items' data
     */
    private function get_item_settings($menu_id)
    {
        $items = wp_get_nav_menu_items($menu_id);

        $menu_items = [];

        if ($items) {
            foreach ($items as $item) {
        		$item_hide_text       = get_post_meta($item->ID, 'zoo_menu_item_hide_text', true);
        		$item_hot             = get_post_meta($item->ID, 'zoo_menu_item_hot', true);
        		$item_new             = get_post_meta($item->ID, 'zoo_menu_item_new', true);
        		$item_trending        = get_post_meta($item->ID, 'zoo_menu_item_trending', true);
        		$item_disable_link    = get_post_meta($item->ID, 'zoo_menu_item_disable_link', true);
        		$item_content         = get_post_meta($item->ID, 'zoo_menu_item_content', true);
        		$item_mega            = get_post_meta($item->ID, 'zoo_menu_item_mega', true);
        		$item_mega_width      = get_post_meta($item->ID, 'zoo_menu_item_width', true);
        		$mega_width           = get_post_meta($item->ID, 'zoo_menu_item_mega_width', true);

        		$item_mega_background = wp_parse_args(
        			get_post_meta($item->ID, 'zoo_menu_item_background', true),
        			[
        				'image'     =>'',
        				'color'     =>'',
        				'attachment'=>'scroll',
        				'size'      =>'',
        				'repeat'    =>'no-repeat',
        				'position'  => [
        					'x'     =>'left',
        					'y'     =>'top',
        					'custom'=> [
        						'x'=>'',
        						'y'=>'',
        					]
        				]
        			]
        		);

                $item_content_image = get_post_meta($item->ID, 'zoo_menu_item_contentImage', true);

                $menu_items[$item->ID] = [
                    'menuItemId' => $item->ID,
                    'mega' => $item_mega,
                    'megaWidth' => $mega_width,
                    'width' => $item_mega_width,
                    'background' => $item_mega_background,
                    'contentImage' => $item_content_image,
                    'hideText' => $item_hide_text,
                    'hot' => $item_hot,
                    'new' => $item_new,
                    'trending' => $item_trending,
                    'disableLink' => $item_disable_link,
                    'content' => $item_content
                ];
            }
        }

        return $menu_items;
    }

    /**
     * Get selected nav menu ID
     */
    private function get_selected_menu_id()
    {
        $nav_menus = wp_get_nav_menus(array('orderby' => 'name'));

        $menu_count = count($nav_menus);

        $menu_id = isset($_REQUEST['menu']) ? (int)$_REQUEST['menu'] : 0;

        $add_new_screen = (isset($_GET['menu']) && 0 === $_GET['menu']) ? true : false;

        $page_count = wp_count_posts('page');

        $one_theme_location_no_menus = (1 === count(get_registered_nav_menus()) && !$add_new_screen && empty($nav_menus) && !empty($page_count->publish)) ? true : false;

        $recently_edited = absint(get_user_option('nav_menu_recently_edited'));

        if (empty($recently_edited) && is_nav_menu($menu_id)) {
            $recently_edited = $menu_id;
        }

        if (empty($menu_id) && !isset($_GET['menu']) && is_nav_menu($recently_edited)) {
            $menu_id = $recently_edited;
        }

        if (!$add_new_screen && 0 < $menu_count && isset($_GET['action']) && 'delete' === $_GET['action']) {
            $menu_id = $nav_menus[0]->term_id;
        }

        if ($one_theme_location_no_menus) {
            $menu_id = 0;
        } elseif (empty($menu_id) && !empty($nav_menus) && !$add_new_screen) {
            $menu_id = $nav_menus[0]->term_id;
        }

        return $menu_id;
    }


    /**
     * Ajax function to save menu item data
     */
    public function save_menu_item_data()
    {
        $_POST['data'] = stripslashes_deep($_POST['data']);

        parse_str($_POST['data'], $data);

        $i = 0;
        $has_mega = false;
        // Save menu item data
        foreach ($data['menu-item'] as $id => $meta) {
            $keys = array_keys($meta);
            if ($i == 0) {
                if (in_array('mega', $keys)) {
                    update_post_meta($id, 'zoo_menu_item_mega', true);
                    $has_mega = true;
                } else {
                    delete_post_meta($id, 'zoo_menu_item_mega');
                }

                if (in_array('hideText', $keys)) {
                    update_post_meta($id, 'zoo_menu_item_hide_text', true);
                } else {
                    delete_post_meta($id, 'zoo_menu_item_hide_text');
                }

                if (in_array('hot', $keys)) {
                    update_post_meta($id, 'zoo_menu_item_hot', true);
                } else {
                    delete_post_meta($id, 'zoo_menu_item_hot');
                }

                if (in_array('new', $keys)) {
                    update_post_meta($id, 'zoo_menu_item_new', true);
                } else {
                    delete_post_meta($id, 'zoo_menu_item_new');
                }

                if (in_array('trending', $keys)) {
                    update_post_meta($id, 'zoo_menu_item_trending', true);
                } else {
                    delete_post_meta($id, 'zoo_menu_item_trending');
                }
            }

            foreach ($meta as $key => $value) {
                $key = str_replace('-', '_', $key);
                update_post_meta($id, 'zoo_menu_item_' . $key, $value);
            }

            $i ++;
        }

        $menu_id = intval($data['menu']);

        if ($has_mega) {
            update_term_meta($menu_id, 'zoo_menu_is_mega', '1');
        } else {
            delete_term_meta($menu_id, 'zoo_menu_is_mega');
        }

        wp_send_json_success($data);
    }
}

return new Zoo_Mega_Menu_Edit();
