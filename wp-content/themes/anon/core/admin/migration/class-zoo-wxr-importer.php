<?php
/**
 * Zoo_WXR_Importer
 *
 * @package  Zoo_Theme\Core\Admin\Migration
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */

final class Zoo_WXR_Importer
{
    /**
     * @var object
     */
    protected $parser;

    // information to import from WXR file
    protected $version;
    protected $authors = [];
    protected $posts = [];
    protected $terms = [];
    protected $categories = [];
    protected $tags = [];
    protected $base_url = '';

    // mappings from old information to new
    protected $processed_authors = [];
    protected $author_mapping = [];
    protected $processed_terms = [];
    protected $processed_posts = [];
    protected $post_orphans = [];
    protected $processed_menu_items = [];
    protected $menu_item_orphans = [];
    protected $missing_menu_items = [];

    protected $fetch_attachments = false;
    protected $url_remap = [];
    protected $featured_images = [];
    protected $uploaded_placeholder = false;
    protected $placeholder_metadata = false;
    protected $isBaseContent = false;

    /**
     * Constructor
     *
     * @param array $options {
     *     @var bool $prefill_existing_posts Should we prefill `post_exists` calls? (True prefills and uses more memory, false checks once per imported post and takes longer. Default is true.)
     *     @var bool $prefill_existing_comments Should we prefill `comment_exists` calls? (True prefills and uses more memory, false checks once per imported comment and takes longer. Default is true.)
     *     @var bool $prefill_existing_terms Should we prefill `term_exists` calls? (True prefills and uses more memory, false checks once per imported term and takes longer. Default is true.)
     *     @var bool $update_attachment_guids Should attachment GUIDs be updated to the new URL? (True updates the GUID, which keeps compatibility with v1, false doesn't update, and allows deduplication and reimporting. Default is false.)
     *     @var bool $fetch_attachments Fetch attachments from the remote server. (True fetches and creates attachment posts, false skips attachments. Default is false.)
     *     @var bool $aggressive_url_search Should we search/replace for URLs aggressively? (True searches all posts' content for old URLs and replaces, false checks for `<img class="wp-image-*">` only. Default is false.)
     *     @var int $default_author User ID to use if author is missing or invalid. (Default is null, which leaves posts unassigned.)
     * }
     */
    public function __construct()
    {
        $this->parser = new Zoo_WXR_Parser();
    }

    /**
     * The main controller for the actual import stage.
     *
     * @param string $file Path to the WXR file for importing
     */
    public function import($file)
    {
        if (!is_readable($file)) {
            throw new Exception(esc_html__('Importing file does not exist or is not readable.', 'anon'));
        }

        set_time_limit(0);

        $this->fetch_attachments = apply_filters('import_allow_fetch_attachments', false);

        add_filter('import_post_meta_key', array($this, 'is_valid_meta_key'));
        add_filter('http_request_timeout', array($this, 'bump_request_timeout'));

        $data = $this->parser->parse($file);

        if (is_wp_error($data)) {
            $error_msg = $data->get_error_message();
            if ('SimpleXML_parse_error' === $data->get_error_code()) {
                $error_msg = '';
                foreach ($data->get_error_data() as $error) {
                    $error_msg .= $error->line . ':' . $error->column . ' ' . $error->message . "\n";
                }
            } elseif ('XML_parse_error' === $data->get_error_code()) {
                $error = $data->get_error_data();
                $error_msg = $error[0] . ':' . $error[1] . ' ' . $error[2];
            }
            throw new Exception(sprintf(esc_html__('Failed to parse import file. %s', 'anon'), $error_msg));
        }

        $this->get_authors_from_import($data);

        $this->version    = $data['version'];
        $this->posts      = $data['posts'];
        $this->terms      = $data['terms'];
        $this->categories = $data['categories'];
        $this->tags       = $data['tags'];
        $this->base_url   = esc_url($data['base_url']);

        wp_suspend_cache_invalidation(true);

        $this->process_categories();
        $this->process_tags();
        $this->process_terms();
        $this->process_posts();

        wp_suspend_cache_invalidation(false);

        // update incorrect/missing information in the DB
        $this->backfill_parents();
        $this->backfill_attachment_urls();
        $this->remap_featured_images();

        $this->import_end();

        return true;
    }

    /**
     * Parses the WXR file and prepares us for the task of processing parsed data
     *
     * @param string $file Path to the WXR file for importing
     */
    protected function import_start()
    {
        do_action('import_start');

        wp_defer_term_counting(true);
        wp_defer_comment_counting(true);
    }

    /**
     * Retrieve authors from parsed WXR data
     *
     * Uses the provided author information from WXR 1.1 files
     * or extracts info from each post for WXR 1.0 files
     *
     * @param array $data Data returned by a WXR parser
     */
    protected function get_authors_from_import($data)
    {
        if (!empty($data['authors'])) {
            $this->authors = $data['authors'];
        } else {
            foreach ($data['posts'] as $post) {
                $login = sanitize_user($post['post_author'], true);
                if (empty($login)) {
                    continue;
                }

                if (!isset($this->authors[$login])) {
                    $this->authors[$login] = array(
                        'author_login' => $login,
                        'author_display_name' => $post['post_author']
                    );
                }
            }
        }
    }

    /**
     * Create new categories based on import information
     *
     * Doesn't create a new category if its slug already exists
     */
    protected function process_categories()
    {
        $this->categories = apply_filters('wp_import_categories', $this->categories);

        if (empty($this->categories)) {
            return;
        }

        foreach ($this->categories as $cat) {
            // if the category already exists leave it alone
            $term_id = term_exists($cat['category_nicename'], 'category');
            if ($term_id) {
                if (is_array($term_id)) {
                    $term_id = $term_id['term_id'];
                }
                if (isset($cat['term_id'])) {
                    $this->processed_terms[intval($cat['term_id'])] = (int)$term_id;
                }
                continue;
            }

            $category_parent = empty($cat['category_parent']) ? 0 : category_exists($cat['category_parent']);
            $category_description = isset($cat['category_description']) ? $cat['category_description'] : '';
            $catarr = array(
                'category_nicename' => $cat['category_nicename'],
                'category_parent' => $category_parent,
                'cat_name' => $cat['cat_name'],
                'category_description' => $category_description
            );
            $catarr = wp_slash($catarr);

            $id = wp_insert_category($catarr);
            if (!is_wp_error($id)) {
                if (isset($cat['term_id'])) {
                    $this->processed_terms[intval($cat['term_id'])] = $id;
                }
            } else {
                continue;
            }

            $this->process_termmeta($cat, $id['term_id']);
        }

        unset($this->categories);
    }

    /**
     * Create new post tags based on import information
     *
     * Doesn't create a tag if its slug already exists
     */
    protected function process_tags()
    {
        $this->tags = apply_filters('wp_import_tags', $this->tags);

        if (empty($this->tags)) {
            return;
        }

        foreach ($this->tags as $tag) {
            // if the tag already exists leave it alone
            $term_id = term_exists($tag['tag_slug'], 'post_tag');
            if ($term_id) {
                if (is_array($term_id)) {
                    $term_id = $term_id['term_id'];
                }
                if (isset($tag['term_id'])) {
                    $this->processed_terms[intval($tag['term_id'])] = (int) $term_id;
                }
                continue;
            }

            $tag = wp_slash($tag);
            $tag_desc = isset($tag['tag_description']) ? $tag['tag_description'] : '';
            $tagarr = array( 'slug' => $tag['tag_slug'], 'description' => $tag_desc );

            $id = wp_insert_term($tag['tag_name'], 'post_tag', $tagarr);
            if (!is_wp_error($id)) {
                if (isset($tag['term_id'])) {
                    $this->processed_terms[intval($tag['term_id'])] = $id['term_id'];
                }
            } else {
                continue;
            }

            $this->process_termmeta($tag, $id['term_id']);
        }

        unset($this->tags);
    }

    /**
     * Create new terms based on import information
     *
     * Doesn't create a term its slug already exists
     */
    protected function process_terms()
    {
        global $wpdb;

        $this->terms = apply_filters('wp_import_terms', $this->terms);

        if (empty($this->terms)) {
            return;
        }

        foreach ($this->terms as $term) {
            // if the term already exists in the correct taxonomy leave it alone
            $term_id = term_exists($term['slug'], $term['term_taxonomy']);
            if ($term_id) {
                if (is_array($term_id)) {
                    $term_id = $term_id['term_id'];
                }
                if (isset($term['term_id'])) {
                    $this->processed_terms[intval($term['term_id'])] = (int) $term_id;
                }
                continue;
            }

            if (class_exists('WooCommerce', false) && false !== strpos($term['term_taxonomy'], 'pa_')) {
                if (!taxonomy_exists($term['term_taxonomy'])) {
                    $att_name = wc_sanitize_taxonomy_name(str_replace('pa_', '', $term['term_taxonomy']));
                    if (!in_array($att_name, wc_get_attribute_taxonomies())) {
                        $wpdb->insert($wpdb->prefix.'woocommerce_attribute_taxonomies', [
                            'attribute_label'   => $att_name,
                            'attribute_name'    => $att_name,
                            'attribute_type'    => 'select',
                            'attribute_orderby' => 'menu_order',
                            'attribute_public'  => 0
                        ]);
                        delete_transient('wc_attribute_taxonomies');
                    }
                    $this->register_custom_taxonomy(
                        $term['term_taxonomy'],
                        apply_filters('woocommerce_taxonomy_objects_'.$term['term_taxonomy'], ['product']),
                        apply_filters('woocommerce_taxonomy_args_'.$term['term_taxonomy'], [
                            'hierarchical' => true,
                            'show_ui'      => false,
                            'query_var'    => true,
                            'rewrite'      => false,
                        ])
                    );
                }
            }

            if (empty($term['term_parent'])) {
                $parent = 0;
            } else {
                $parent = term_exists($term['term_parent'], $term['term_taxonomy']);
                if (is_array($parent)) {
                    $parent = $parent['term_id'];
                }
            }
            $term = wp_slash($term);
            $description = isset($term['term_description']) ? $term['term_description'] : '';
            $termarr = array( 'slug' => $term['slug'], 'description' => $description, 'parent' => intval($parent) );

            $id = wp_insert_term($term['term_name'], $term['term_taxonomy'], $termarr);
            if (!is_wp_error($id)) {
                if (isset($term['term_id'])) {
                    $this->processed_terms[intval($term['term_id'])] = $id['term_id'];
                }
            } else {
                continue;
            }

            $this->process_termmeta($term, $id['term_id']);
        }

        unset($this->terms);
    }

    /**
     * Add metadata to imported term.
     *
     * @since 0.6.2
     *
     * @param array $term    Term data from WXR import.
     * @param int   $term_id ID of the newly created term.
     */
    protected function process_termmeta($term, $term_id)
    {
        if (!isset($term['termmeta'])) {
            $term['termmeta'] = [];
        }

        /**
         * Filters the metadata attached to an imported term.
         *
         * @since 0.6.2
         *
         * @param array $termmeta Array of term meta.
         * @param int   $term_id  ID of the newly created term.
         * @param array $term     Term data from the WXR import.
         */
        $term['termmeta'] = apply_filters('wp_import_term_meta', $term['termmeta'], $term_id, $term);

        if (empty($term['termmeta'])) {
            return;
        }

        foreach ($term['termmeta'] as $meta) {
            /**
             * Filters the meta key for an imported piece of term meta.
             *
             * @since 0.6.2
             *
             * @param string $meta_key Meta key.
             * @param int    $term_id  ID of the newly created term.
             * @param array  $term     Term data from the WXR import.
             */
            $key = apply_filters('import_term_meta_key', $meta['key'], $term_id, $term);
            if (!$key) {
                continue;
            }

            // Export gets meta straight from the DB so could have a serialized string
            $value = maybe_unserialize($meta['value']);

            add_term_meta($term_id, $key, $value);

            /**
             * Fires after term meta is imported.
             *
             * @since 0.6.2
             *
             * @param int    $term_id ID of the newly created term.
             * @param string $key     Meta key.
             * @param mixed  $value   Meta value.
             */
            do_action('import_term_meta', $term_id, $key, $value);
        }
    }

    /**
     * Create new posts based on import information
     *
     * Posts marked as having a parent which doesn't exist will become top level items.
     * Doesn't create a new post if: the post type doesn't exist, the given post ID
     * is already noted as imported or a post with the same title and date already exists.
     * Note that new/updated terms, comments and meta are imported for the last of the above.
     */
    protected function process_posts()
    {
        $this->posts = apply_filters('wp_import_posts', $this->posts);

        foreach ($this->posts as $post) {
            $post = apply_filters('wp_import_post_data_raw', $post);

            if (isset($this->processed_posts[$post['post_id']]) && !empty($post['post_id'])) {
                continue;
            }

            if ($post['status'] == 'auto-draft') {
                continue;
            }

            if ('nav_menu_item' == $post['post_type']) {
                $this->process_menu_item($post);
                continue;
            }

            $post_type_object = get_post_type_object($post['post_type']);

            $post_exists = post_exists($post['post_title'], '', $post['post_date']);

            /**
             * Filter ID of the existing post corresponding to post currently importing.
             *
             * Return 0 to force the post to be imported. Filter the ID to be something else
             * to override which existing post is mapped to the imported post.
             *
             * @see post_exists()
             * @since 0.6.2
             *
             * @param int   $post_exists  Post ID, or 0 if post did not exist.
             * @param array $post         The post array to be inserted.
             */
            $post_exists = apply_filters('wp_import_existing_post', $post_exists, $post);

            if ($post_exists && get_post_type($post_exists) == $post['post_type']) {
                $comment_post_ID = $post_id = $post_exists;
                $this->processed_posts[ intval($post['post_id']) ] = intval($post_exists);
            } else {
                $post_parent = (int) $post['post_parent'];
                if ($post_parent) {
                    // if we already know the parent, map it to the new local ID
                    if (isset($this->processed_posts[$post_parent])) {
                        $post_parent = $this->processed_posts[$post_parent];
                    // otherwise record the parent for later
                    } else {
                        $this->post_orphans[intval($post['post_id'])] = $post_parent;
                        $post_parent = 0;
                    }
                }

                // map the post author
                $author = sanitize_user($post['post_author'], true);
                if (isset($this->author_mapping[$author])) {
                    $author = $this->author_mapping[$author];
                } else {
                    $author = (int) get_current_user_id();
                }

                $postdata = array(
                    'import_id' => $post['post_id'], 'post_author' => $author, 'post_date' => $post['post_date'],
                    'post_date_gmt' => $post['post_date_gmt'], 'post_content' => $post['post_content'],
                    'post_excerpt' => $post['post_excerpt'], 'post_title' => $post['post_title'],
                    'post_status' => $post['status'], 'post_name' => $post['post_name'],
                    'comment_status' => $post['comment_status'], 'ping_status' => $post['ping_status'],
                    'guid' => $post['guid'], 'post_parent' => $post_parent, 'menu_order' => $post['menu_order'],
                    'post_type' => $post['post_type'], 'post_password' => $post['post_password']
                );

                if (isset($post['is_logo'])) {
                    $postdata['is_logo'] = true;
                }

                $original_post_ID = $post['post_id'];
                $postdata = apply_filters('wp_import_post_data_processed', $postdata, $post);

                $postdata = wp_slash($postdata);

                if ('attachment' == $postdata['post_type']) {
                    $remote_url = !empty($post['attachment_url']) ? $post['attachment_url'] : $post['guid'];

                    // try to use _wp_attached file for upload folder placement to ensure the same location as the export site
                    // e.g. location is 2003/05/image.jpg but the attachment post_date is 2010/09, see media_handle_upload()
                    $postdata['upload_date'] = $post['post_date'];
                    if (isset($post['postmeta'])) {
                        foreach ($post['postmeta'] as $meta) {
                            if ($meta['key'] == '_wp_attached_file') {
                                if (preg_match('%^[0-9]{4}/[0-9]{2}%', $meta['value'], $matches)) {
                                    $postdata['upload_date'] = $matches[0];
                                }
                                break;
                            }
                        }
                    }

                    $comment_post_ID = $post_id = $this->process_attachment($postdata, $remote_url);
                } else {
                    $comment_post_ID = $post_id = wp_insert_post($postdata, true);
                    do_action('wp_import_insert_post', $post_id, $original_post_ID, $postdata, $post);
                }

                if (is_wp_error($post_id)) {
                    continue;
                }

                if ($post['is_sticky'] == 1) {
                    stick_post($post_id);
                }
            }

            // map pre-import ID to local ID
            $this->processed_posts[intval($post['post_id'])] = (int) $post_id;

            if (!isset($post['terms'])) {
                $post['terms'] = [];
            }

            $post['terms'] = apply_filters('wp_import_post_terms', $post['terms'], $post_id, $post);

            // add categories, tags and other terms
            if (!empty($post['terms'])) {
                $terms_to_set = [];
                foreach ($post['terms'] as $term) {
                    // back compat with WXR 1.0 map 'tag' to 'post_tag'
                    $taxonomy = ('tag' == $term['domain']) ? 'post_tag' : $term['domain'];
                    $term_exists = term_exists($term['slug'], $taxonomy);
                    $term_id = is_array($term_exists) ? $term_exists['term_id'] : $term_exists;
                    if (!$term_id) {
                        $t = wp_insert_term($term['name'], $taxonomy, array( 'slug' => $term['slug'] ));
                        if (!is_wp_error($t)) {
                            $term_id = $t['term_id'];
                            do_action('wp_import_insert_term', $t, $term, $post_id, $post);
                        } else {
                            continue;
                        }
                    }
                    $terms_to_set[$taxonomy][] = intval($term_id);
                }

                foreach ($terms_to_set as $tax => $ids) {
                    $tt_ids = wp_set_post_terms($post_id, $ids, $tax);
                    do_action('wp_import_set_post_terms', $tt_ids, $ids, $tax, $post_id, $post);
                }
                unset($post['terms'], $terms_to_set);
            }

            if (!isset($post['comments'])) {
                $post['comments'] = [];
            }

            $post['comments'] = apply_filters('wp_import_post_comments', $post['comments'], $post_id, $post);

            // add/update comments
            if (!empty($post['comments'])) {
                $num_comments = 0;
                $inserted_comments = [];
                foreach ($post['comments'] as $comment) {
                    $comment_id	= $comment['comment_id'];
                    $newcomments[$comment_id]['comment_post_ID']      = $comment_post_ID;
                    $newcomments[$comment_id]['comment_author']       = $comment['comment_author'];
                    $newcomments[$comment_id]['comment_author_email'] = $comment['comment_author_email'];
                    $newcomments[$comment_id]['comment_author_IP']    = $comment['comment_author_IP'];
                    $newcomments[$comment_id]['comment_author_url']   = $comment['comment_author_url'];
                    $newcomments[$comment_id]['comment_date']         = $comment['comment_date'];
                    $newcomments[$comment_id]['comment_date_gmt']     = $comment['comment_date_gmt'];
                    $newcomments[$comment_id]['comment_content']      = $comment['comment_content'];
                    $newcomments[$comment_id]['comment_approved']     = $comment['comment_approved'];
                    $newcomments[$comment_id]['comment_type']         = $comment['comment_type'];
                    $newcomments[$comment_id]['comment_parent'] 	  = $comment['comment_parent'];
                    $newcomments[$comment_id]['commentmeta']          = isset($comment['commentmeta']) ? $comment['commentmeta'] : [];
                    if (isset($this->processed_authors[$comment['comment_user_id']])) {
                        $newcomments[$comment_id]['user_id'] = $this->processed_authors[$comment['comment_user_id']];
                    }
                }
                ksort($newcomments);

                foreach ($newcomments as $key => $comment) {
                    // if this is a new post we can skip the comment_exists() check
                    if (!$post_exists || !comment_exists($comment['comment_author'], $comment['comment_date'])) {
                        if (isset($inserted_comments[$comment['comment_parent']])) {
                            $comment['comment_parent'] = $inserted_comments[$comment['comment_parent']];
                        }
                        $comment = wp_slash($comment);
                        $comment = wp_filter_comment($comment);
                        $inserted_comments[$key] = wp_insert_comment($comment);
                        do_action('wp_import_insert_comment', $inserted_comments[$key], $comment, $comment_post_ID, $post);

                        foreach ($comment['commentmeta'] as $meta) {
                            $value = maybe_unserialize($meta['value']);
                            add_comment_meta($inserted_comments[$key], $meta['key'], $value);
                        }

                        $num_comments++;
                    }
                }
                unset($newcomments, $inserted_comments, $post['comments']);
            }

            if (!isset($post['postmeta'])) {
                $post['postmeta'] = [];
            }

            $post['postmeta'] = apply_filters('wp_import_post_meta', $post['postmeta'], $post_id, $post);

            // add/update post meta
            if (!empty($post['postmeta'])) {
                foreach ($post['postmeta'] as $meta) {
                    $key = apply_filters('import_post_meta_key', $meta['key'], $post_id, $post);
                    $value = false;

                    if ('_edit_last' == $key) {
                        if (isset($this->processed_authors[intval($meta['value'])])) {
                            $value = $this->processed_authors[intval($meta['value'])];
                        } else {
                            $key = false;
                        }
                    }

                    if ('cmm4e_menu_id' === $key) {
                        if (isset($this->processed_terms[intval($meta['value'])])) {
                            $value = $this->processed_terms[intval($meta['value'])];
                        } else {
                            $key = false;
                        }
                    }

                    // Replace old nav menu ids from Elementor data
                    if ('_elementor_data' === $key) {
                        preg_match_all('/"nav_menu\\\":\\\"\d+\\\"/m', $meta['value'], $matches);
                        if (!empty($matches[0]) && is_array($matches[0])) {
                            $replaces = [];
                            $searches = array_unique($matches[0]);
                            unset($matches);
                            foreach ($searches as $search) {
                                $old_nav_id = filter_var($search, FILTER_SANITIZE_NUMBER_INT);
                                if (isset($this->processed_terms[$old_nav_id])) {
                                    $replaces[] = '"nav_menu\":\"' . $this->processed_terms[$old_nav_id] . '\"';
                                }
                            }
                            $value = str_replace($searches, $replaces, $meta['value']);
                        }
                    }

                    if ($key) {
                        // export gets meta straight from the DB so could have a serialized string
                        if (!$value) {
                            $value = maybe_unserialize($meta['value']);
                        }

                        add_post_meta($post_id, $key, $value);
                        do_action('import_post_meta', $post_id, $key, $value);

                        // if the post has a featured image, take note of this in case of remap
                        if ('_thumbnail_id' == $key) {
                            $this->featured_images[$post_id] = (int) $value;
                        }
                    }
                }
            }
        }
    }

    /**
     * Attempt to create a new menu item from import data
     *
     * Fails for draft, orphaned menu items and those without an associated nav_menu
     * or an invalid nav_menu term. If the post type or term object which the menu item
     * represents doesn't exist then the menu item will not be imported (waits until the
     * end of the import to retry again before discarding).
     *
     * @param array $item Menu item details from WXR file
     */
    protected function process_menu_item($item)
    {
        // skip draft, orphaned menu items
        if ('draft' == $item['status']) {
            return;
        }

        $menu_slug = false;
        $cmm4e_post_id = false;

        if (isset($item['terms'])) {
            // loop through terms, assume first nav_menu term is correct menu
            foreach ($item['terms'] as $term) {
                if ('nav_menu' == $term['domain']) {
                    $menu_slug = $term['slug'];
                    break;
                }
            }
        }

        // no nav_menu term associated with this menu item
        if (!$menu_slug) {
            return;
        }

        $menu_id = term_exists($menu_slug, 'nav_menu');

        if (!$menu_id) {
            return;
        } else {
            $menu_id = is_array($menu_id) ? $menu_id['term_id'] : $menu_id;
        }

        foreach ($item['postmeta'] as $meta) {
            if ('cmm4e_menu_post_id' === $meta['key']) {
                if (isset($this->processed_posts[intval($meta['value'])])) {
                    $cmm4e_post_id = $this->processed_posts[intval($meta['value'])];
                }
            } else {
                ${$meta['key']} = $meta['value'];
            }
        }

        if ('taxonomy' == $_menu_item_type && isset($this->processed_terms[intval($_menu_item_object_id)])) {
            $_menu_item_object_id = $this->processed_terms[intval($_menu_item_object_id)];
        } elseif ('post_type' == $_menu_item_type && isset($this->processed_posts[intval($_menu_item_object_id)])) {
            $_menu_item_object_id = $this->processed_posts[intval($_menu_item_object_id)];
        } elseif ('custom' != $_menu_item_type) {
            // associated object is missing or not imported yet, we'll retry later
            $this->missing_menu_items[] = $item;
            return;
        }

        if (isset($this->processed_menu_items[intval($_menu_item_menu_item_parent)])) {
            $_menu_item_menu_item_parent = $this->processed_menu_items[intval($_menu_item_menu_item_parent)];
        } elseif ($_menu_item_menu_item_parent) {
            $this->menu_item_orphans[intval($item['post_id'])] = (int) $_menu_item_menu_item_parent;
            $_menu_item_menu_item_parent = 0;
        }

        // wp_update_nav_menu_item expects CSS classes as a space separated string
        $_menu_item_classes = maybe_unserialize($_menu_item_classes);
        if (is_array($_menu_item_classes)) {
            $_menu_item_classes = implode(' ', $_menu_item_classes);
        }

        $args = array(
            'menu-item-object-id' => $_menu_item_object_id,
            'menu-item-object' => $_menu_item_object,
            'menu-item-parent-id' => $_menu_item_menu_item_parent,
            'menu-item-position' => intval($item['menu_order']),
            'menu-item-type' => $_menu_item_type,
            'menu-item-title' => $item['post_title'],
            'menu-item-url' => $_menu_item_url,
            'menu-item-description' => $item['post_content'],
            'menu-item-attr-title' => $item['post_excerpt'],
            'menu-item-target' => $_menu_item_target,
            'menu-item-classes' => $_menu_item_classes,
            'menu-item-xfn' => $_menu_item_xfn,
            'menu-item-status' => $item['status']
        );

        $id = wp_update_nav_menu_item($menu_id, 0, $args);

        if ($id && !is_wp_error($id)) {
            $this->processed_menu_items[intval($item['post_id'])] = (int) $id;
            if ($cmm4e_post_id) {
                add_post_meta($id, 'cmm4e_menu_post_id', $cmm4e_post_id, true);
            }
        }
    }

	/**
	 * If fetching attachments is enabled then attempt to create a new attachment
	 *
	 * @param array $post Attachment post details from WXR
	 * @param string $url URL to fetch attachment from
	 * @return int|WP_Error Post ID on success, WP_Error otherwise
	 */
	protected function process_attachment($attachment, $remote_url )
    {
		$attachment_metadata = [];

		// if the URL is absolute, but does not contain address, then upload it assuming base_site_url
		if ( preg_match('|^/[\w\W]+$|', $remote_url )) {
			$remote_url = rtrim($this->base_url, '/') . $remote_url;
		}

		$upload = $this->fetch_remote_file($remote_url, $attachment);

		if (is_wp_error($upload)) {
			return $upload;
		}

        $info = wp_check_filetype($upload['file']);

		if ( ! $info ) {
			return new \WP_Error('attachment_processing_error', esc_html__('Invalid attachment file type', 'anon'));
		}

		$attachment['post_mime_type'] = $info['type'];

        $is_image = (false !== strpos($info['type'], 'image'));
        $is_logo  = !empty($attachment['is_logo']);

		// as per wp-admin/includes/upload.php
		$post_id = wp_insert_attachment($attachment, $upload['file']);
		if (is_wp_error($post_id)) {
			return $post_id;
		}

        if ($is_image && !$is_logo) {
            if ($this->placeholder_metadata) {
                $attachment_metadata = $this->placeholder_metadata;
            }
        }

        if (!$attachment_metadata) {
            $attachment_metadata = wp_generate_attachment_metadata($post_id, $upload['file']);
            if ($is_image && !$is_logo) {
                $this->placeholder_metadata = $attachment_metadata;
            }
        }

		wp_update_attachment_metadata($post_id, $attachment_metadata);

		// Map this image URL later if we need to
		$this->url_remap[$remote_url] = $upload['url'];

		// If we have a HTTPS URL, ensure the HTTP URL gets replaced too
		if (substr($remote_url, 0, 8) === 'https://') {
			$insecure_url = 'http' . substr($remote_url, 5 );
			$this->url_remap[$insecure_url] = $upload['url'];
		}

		return $post_id;
	}

	/**
	 * Attempt to download a remote file attachment
	 *
	 * @param string $url URL of item to fetch
	 * @param array $post Attachment details
	 * @return array|WP_Error Local file location details on success, WP_Error otherwise
	 */
	protected function fetch_remote_file($url, $post)
    {
        static $file_content = null;

        $is_image  = $is_logo = $upload = false;
        $file_name = basename($url);
        $file_ext  = pathinfo($file_name, PATHINFO_EXTENSION);

        if (in_array($file_ext, ['jpg', 'jpeg', 'jpe', 'gif', 'tif', 'tiff', 'png', 'bmp', 'ico'])) {
            $is_image = true;
            if (!empty($post['is_logo'])) {
                $is_logo = true;
            }
            if ($this->uploaded_placeholder && !$is_logo) {
                add_filter('intermediate_image_sizes_advanced', '__return_false', PHP_INT_MAX);
                return $this->uploaded_placeholder;
            }
        }

        if ($is_image) {
            if (!$is_logo) {
                if (!$file_content) {
                    global $wp_filesystem;
                    $file_content = $wp_filesystem->get_contents(ZOO_THEME_DIR.'core/assets/images/placeholder.png');
                }
                $upload = wp_upload_bits('placeholder.png', null, $file_content, $post['upload_date']);
            } else {
                $upload = wp_upload_bits($file_name, null, '', $post['upload_date']);
        		if ($upload['error']) {
        			return new WP_Error('upload_dir_error', $upload['error']);
        		}
        		$response = wp_remote_get($url, array(
        			'stream' => true,
        			'filename' => $upload['file'],
        		));
        		if (is_wp_error($response)) {
        			unlink($upload['file']);
        			return $response;
        		}
        		$code = (int)wp_remote_retrieve_response_code($response);
        		if (200 !== $code) {
        			unlink($upload['file']);
        			return new WP_Error('import_file_error', sprintf(esc_html__('Remote server returned %1$d %2$s for %3$s', 'anon'), $code, get_status_header_desc($code), $url));
        		}
        		$filesize = filesize($upload['file']);
        		$headers = wp_remote_retrieve_headers($response);
        		if (isset($headers['content-length']) && $filesize !== (int) $headers['content-length']) {
        			unlink($upload['file']);
        			return new WP_Error('import_file_error', esc_html__('Remote file is incorrect size', 'anon'));
        		}
        		if (0 === $filesize) {
        			unlink($upload['file']);
        			return new WP_Error('import_file_error', esc_html__('Zero size file downloaded.', 'anon'));
        		}
        		$max_size = apply_filters('_wxr_import_attachment_size_limit', 8*MB_IN_BYTES);
        		if (!empty($max_size) && $filesize > $max_size) {
        			unlink($upload['file']);
        			return new WP_Error('import_file_error', sprintf(esc_html__('Remote file is too large, limit is %s.', 'anon'), size_format($max_size)));
        		}
            }
        } else {
            // Handle other types of attachments such as video, audio...
        }

        if (!$upload || $upload['error']) {
            return new WP_Error('upload_dir_error', $upload['error']);
        }

        if ($is_image && !$is_logo) {
            $this->uploaded_placeholder = $upload;
        }

        return $upload;
	}

    /**
     * Attempt to associate posts and menu items with previously missing parents
     *
     * An imported post's parent may not have been imported when it was first created
     * so try again. Similarly for child menu items and menu items which were missing
     * the object (e.g. post) they represent in the menu
     */
    protected function backfill_parents()
    {
        global $wpdb;

        // find parents for post orphans
        foreach ($this->post_orphans as $child_id => $parent_id) {
            $local_child_id = $local_parent_id = false;
            if (isset($this->processed_posts[$child_id])) {
                $local_child_id = $this->processed_posts[$child_id];
            }
            if (isset($this->processed_posts[$parent_id])) {
                $local_parent_id = $this->processed_posts[$parent_id];
            }

            if ($local_child_id && $local_parent_id) {
                $wpdb->update($wpdb->posts, array( 'post_parent' => $local_parent_id ), array( 'ID' => $local_child_id ), '%d', '%d');
                clean_post_cache($local_child_id);
            }
        }

        // all other posts/terms are imported, retry menu items with missing associated object
        $missing_menu_items = $this->missing_menu_items;
        foreach ($missing_menu_items as $item) {
            $this->process_menu_item($item);
        }

        // find parents for menu item orphans
        foreach ($this->menu_item_orphans as $child_id => $parent_id) {
            $local_child_id = $local_parent_id = 0;
            if (isset($this->processed_menu_items[$child_id])) {
                $local_child_id = $this->processed_menu_items[$child_id];
            }
            if (isset($this->processed_menu_items[$parent_id])) {
                $local_parent_id = $this->processed_menu_items[$parent_id];
            }

            if ($local_child_id && $local_parent_id) {
                update_post_meta($local_child_id, '_menu_item_menu_item_parent', (int) $local_parent_id);
            }
        }
    }

    /**
     * Use stored mapping information to update old attachment URLs
     */
    protected function backfill_attachment_urls()
    {
        global $wpdb;
        // make sure we do the longest urls first, in case one is a substring of another
        uksort($this->url_remap, array(&$this, 'cmpr_strlen'));

        foreach ($this->url_remap as $from_url => $to_url) {
            // remap urls in post_content
            $wpdb->query($wpdb->prepare("UPDATE {$wpdb->posts} SET post_content = REPLACE(post_content, %s, %s)", $from_url, $to_url));
            // remap enclosure urls
            $result = $wpdb->query($wpdb->prepare("UPDATE {$wpdb->postmeta} SET meta_value = REPLACE(meta_value, %s, %s) WHERE meta_key='enclosure'", $from_url, $to_url));
        }
    }

    /**
     * Update _thumbnail_id meta to new, imported attachment IDs
     */
    protected function remap_featured_images()
    {
        // cycle through posts that have a featured image
        foreach ($this->featured_images as $post_id => $value) {
            if (isset($this->processed_posts[$value])) {
                $new_id = $this->processed_posts[$value];
                // only update if there's a difference
                if ($new_id != $value) {
                    update_post_meta($post_id, '_thumbnail_id', $new_id);
                }
            }
        }
    }

    /**
     * Performs post-import cleanup of files and the cache
     */
    protected function import_end()
    {
            set_transient('_wxr_imported_content', [
                'authors' => $this->processed_authors,
                'terms' => $this->processed_terms,
                'posts' => $this->processed_posts,
                'menu_items' => $this->processed_menu_items
            ]);

        wp_cache_flush();

        $taxonomies = get_taxonomies();

        foreach ($taxonomies as $tax) {
            delete_option("{$tax}_children");
            _get_term_hierarchy($tax);
        }

        wp_defer_term_counting(false);
        wp_defer_comment_counting(false);

        do_action('import_end');
    }

    /**
     * Decide if the given meta key maps to information we will want to import
     *
     * @param string $key The meta key to check
     * @return string|bool The key if we do want to import, false if not
     */
    public function is_valid_meta_key($key)
    {
        // skip attachment metadata since we'll regenerate it from scratch
        // skip _edit_lock as not relevant for import
        if (in_array($key, [
            '_wp_attached_file',
            '_wp_attachment_metadata',
            '_edit_lock',
            'wp-smpro-smush-data'
        ])) {
            return false;
        }

        return $key;
    }

    /**
     * Decide whether or not the importer is allowed to create users.
     * Default is true, can be filtered via import_allow_create_users
     *
     * @return bool True if creating users is allowed
     */
    protected function allow_create_users()
    {
        return apply_filters('import_allow_create_users', true);
    }

    /**
     * Decide what the maximum file size for downloaded attachments is.
     * Default is 0 (unlimited), can be filtered via import_attachment_size_limit
     *
     * @return int Maximum attachment file size to import
     */
    public function max_attachment_size()
    {
        return apply_filters('import_attachment_size_limit', 0);
    }

    /**
     * Added to http_request_timeout filter to force timeout at 60 seconds during import
     * @return int 60
     */
    public function bump_request_timeout($val)
    {
        return apply_filters('import_request_timeout', 60);
    }

    // return the difference in length between two strings
    public function cmpr_strlen($a, $b)
    {
        return strlen($b) - strlen($a);
    }

    /**
     * Clone `register_taxonomy`
     *
     * @see  https://developer.wordpress.org/reference/functions/register_taxonomy/
     */
    protected function register_custom_taxonomy($taxonomy, $object_type, $args = [])
    {
        global $wp_taxonomies;
        if ( ! is_array( $wp_taxonomies ) )
            $wp_taxonomies = array();
        $args = wp_parse_args( $args );
        if ( empty( $taxonomy ) || strlen( $taxonomy ) > 32 ) {
            _doing_it_wrong( __FUNCTION__, esc_html__( 'Taxonomy names must be between 1 and 32 characters in length.', 'anon' ), '4.2.0' );
            return new WP_Error( 'taxonomy_length_invalid', esc_html__( 'Taxonomy names must be between 1 and 32 characters in length.', 'anon' ) );
        }
        $taxonomy_object = new WP_Taxonomy( $taxonomy, $object_type, $args );
        $taxonomy_object->add_rewrite_rules();
        $wp_taxonomies[ $taxonomy ] = $taxonomy_object;
        $taxonomy_object->add_hooks();
    }
}
