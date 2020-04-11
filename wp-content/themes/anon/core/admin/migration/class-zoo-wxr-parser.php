<?php
/**
 * Zoo_WXR_Parser
 */
final class Zoo_WXR_Parser
{
    private $wp_tags = [
        'wp:post_id', 'wp:post_date', 'wp:post_date_gmt', 'wp:comment_status', 'wp:ping_status', 'wp:attachment_url',
        'wp:status', 'wp:post_name', 'wp:post_parent', 'wp:menu_order', 'wp:post_type', 'wp:post_password',
        'wp:is_sticky', 'wp:term_id', 'wp:category_nicename', 'wp:category_parent', 'wp:cat_name', 'wp:category_description',
        'wp:tag_slug', 'wp:tag_name', 'wp:tag_description', 'wp:term_taxonomy', 'wp:term_parent',
        'wp:term_name', 'wp:term_description', 'wp:author_id', 'wp:author_login', 'wp:author_email', 'wp:author_display_name',
        'wp:author_first_name', 'wp:author_last_name',
    ];

    private $wp_sub_tags = [
        'wp:comment_id', 'wp:comment_author', 'wp:comment_author_email', 'wp:comment_author_url',
        'wp:comment_author_IP',	'wp:comment_date', 'wp:comment_date_gmt', 'wp:comment_content',
        'wp:comment_approved', 'wp:comment_type', 'wp:comment_parent', 'wp:comment_user_id',
    ];

    /**
     * @var string
     */
    private $base_dir = ZOO_THEME_DIR . '/inc/sample-data/base';

    /**
     * Parse
     */
    public function parse($file)
    {
        $this->base_dir = dirname($file);

        if (extension_loaded('simplexml')) {
            return $this->simpleXmlParse($file);
        } elseif (extension_loaded('xml')) {
            return $parser->xmlParse($file);
        } else {
            return new WP_Error('No_WXR_Parser_Found', esc_html__('No WXR parser found.', 'anon'));
        }
    }

    /**
     * Parse file with SimpleXml extension
     */
    protected function simpleXmlParse($file)
    {
        global $wp_filesystem;

        WP_Filesystem();

        $authors = $posts = $categories = $tags = $terms = [];

        $internal_errors = libxml_use_internal_errors(true);

        $dom = new DOMDocument();
        $old_value = null;

        if (function_exists('libxml_disable_entity_loader')) {
            $old_value = libxml_disable_entity_loader(true);
        }

        $file_content = $wp_filesystem->get_contents($file);

        if (!$file_content) {
            return new WP_Error('SimpleXML_parse_error', esc_html__('There is an error while getting WXR file content.', 'anon'));
        }

        $success = $dom->loadXML($file_content);

        if (!is_null($old_value)) {
            libxml_disable_entity_loader($old_value);
        }

        if (!$success || isset($dom->doctype)) {
            return new WP_Error('SimpleXML_parse_error', esc_html__('There is an error while reading the WXR file', 'anon'), libxml_get_errors());
        }

        $xml = simplexml_import_dom($dom);

        unset($dom);

        // halt if loading produces an error
        if (!$xml) {
            return new WP_Error('SimpleXML_parse_error', esc_html__('There is an error while reading the WXR file', 'anon'), libxml_get_errors());
        }

        $wxr_version = $xml->xpath('/rss/channel/wp:wxr_version');

        if (!$wxr_version) {
            return new WP_Error('WXR_parse_error', esc_html__('This does not appear to be a WXR file, missing/invalid WXR version number', 'anon'));
        }

        $wxr_version = (string) trim($wxr_version[0]);

        // confirm that we are dealing with the correct file format
        if (!preg_match('/^\d+\.\d+$/', $wxr_version)) {
            return new WP_Error('WXR_parse_error', esc_html__('This does not appear to be a WXR file, missing/invalid WXR version number', 'anon'));
        }

        $base_url = $xml->xpath('/rss/channel/wp:base_site_url');
        $base_url = (string) trim($base_url[0]);

        $namespaces = $xml->getDocNamespaces();

        if (!isset($namespaces['wp'])) {
            $namespaces['wp'] = 'http://wordpress.org/export/1.1/';
        }

        if (!isset($namespaces['excerpt'])) {
            $namespaces['excerpt'] = 'http://wordpress.org/export/1.1/excerpt/';
        }

        // grab authors
        foreach ($xml->xpath('/rss/channel/wp:author') as $author_arr) {
            $a = $author_arr->children($namespaces['wp']);
            $login = (string)$a->author_login;
            $authors[$login] = array(
                'author_id' => (int)$a->author_id,
                'author_login' => $login,
                'author_email' => (string)$a->author_email,
                'author_display_name' => (string)$a->author_display_name,
                'author_first_name' => (string)$a->author_first_name,
                'author_last_name' => (string)$a->author_last_name
           );
        }

        // grab cats, tags and terms
        foreach ($xml->xpath('/rss/channel/wp:category') as $term_arr) {
            $t = $term_arr->children($namespaces['wp']);
            $category = array(
                'term_id' => (int)$t->term_id,
                'category_nicename' => (string)$t->category_nicename,
                'category_parent' => (string)$t->category_parent,
                'cat_name' => (string)$t->cat_name,
                'category_description' => (string)$t->category_description
           );

            foreach ($t->termmeta as $meta) {
                $category['termmeta'][] = array(
                    'key' => (string)$meta->meta_key,
                    'value' => (string)$meta->meta_value
               );
            }

            $categories[] = $category;
        }

        foreach ($xml->xpath('/rss/channel/wp:tag') as $term_arr) {
            $t = $term_arr->children($namespaces['wp']);
            $tag = array(
                'term_id' => (int)$t->term_id,
                'tag_slug' => (string)$t->tag_slug,
                'tag_name' => (string)$t->tag_name,
                'tag_description' => (string)$t->tag_description
           );

            foreach ($t->termmeta as $meta) {
                $tag['termmeta'][] = array(
                    'key' => (string)$meta->meta_key,
                    'value' => (string)$meta->meta_value
               );
            }

            $tags[] = $tag;
        }

        foreach ($xml->xpath('/rss/channel/wp:term') as $term_arr) {
            $t = $term_arr->children($namespaces['wp']);
            $term = array(
                'term_id' => (int)$t->term_id,
                'term_taxonomy' => (string)$t->term_taxonomy,
                'slug' => (string)$t->term_slug,
                'term_parent' => (string)$t->term_parent,
                'term_name' => (string)$t->term_name,
                'term_description' => (string)$t->term_description
           );

            foreach ($t->termmeta as $meta) {
                $term['termmeta'][] = array(
                    'key' => (string)$meta->meta_key,
                    'value' => (string)$meta->meta_value
               );
            }

            $terms[] = $term;
        }

        static $first_image_id = null;

        // grab posts
        foreach ($xml->channel->item as $item) {
            $post = array(
                'post_title' => (string)$item->title,
                'guid' => (string)$item->guid,
                '_link' => (string)$item->link
           );

            $dc = $item->children('http://purl.org/dc/elements/1.1/');
            $post['post_author'] = (string)$dc->creator;

            $content = $item->children('http://purl.org/rss/1.0/modules/content/');
            $excerpt = $item->children($namespaces['excerpt']);

            $post['post_content'] = (string)$content->encoded;
            $post['post_excerpt'] = (string)$excerpt->encoded;

            $wp = $item->children($namespaces['wp']);

            $post['post_id'] = (int)$wp->post_id;
            $post['post_date'] = (string)$wp->post_date;
            $post['post_date_gmt'] = (string)$wp->post_date_gmt;
            $post['comment_status'] = (string)$wp->comment_status;
            $post['ping_status'] = (string)$wp->ping_status;
            $post['post_name'] = (string)$wp->post_name;
            $post['status'] = (string)$wp->status;
            $post['post_parent'] = (int)$wp->post_parent;
            $post['menu_order'] = (int)$wp->menu_order;
            $post['post_type'] = (string)$wp->post_type;
            $post['post_password'] = (string)$wp->post_password;
            $post['is_sticky'] = (int)$wp->is_sticky;

            $is_logo = $this->isLogo($wp);

            if ($is_logo) {
                $post['is_logo'] = true;
            }

            if (isset($wp->attachment_url)) {
                $post['attachment_url'] = (string)$wp->attachment_url;
            }

            if ($this->isImage($wp) && !$is_logo) {
                if (null === $first_image_id) {
                    $first_image_id = $post['post_id'];
                }
                $post['post_id'] = $first_image_id;
                $post['post_title'] = esc_html__('Placeholder Image', 'anon');
                $post['attachment_url'] = ZOO_THEME_URI . 'core/assets/images/placeholder.png';
            }

            foreach ($item->category as $c) {
                $att = $c->attributes();
                if (isset($att['nicename'])) {
                    $post['terms'][] = array(
                        'name' => (string)$c,
                        'slug' => (string)$att['nicename'],
                        'domain' => (string)$att['domain']
                   );
                }
            }

            foreach ($wp->postmeta as $meta) {
                $key = (string)$meta->meta_key;
                $post['postmeta'][] = array(
                    'key' => $key,
                    'value' => ('_thumbnail_id' === $key && !$is_logo) ? $first_image_id : (string)$meta->meta_value
               );
            }

            foreach ($wp->comment as $comment) {
                $meta = [];
                if (isset($comment->commentmeta)) {
                    foreach ($comment->commentmeta as $m) {
                        $meta[] = array(
                            'key' => (string)$m->meta_key,
                            'value' => (string)$m->meta_value
                       );
                    }
                }
                $post['comments'][] = array(
                    'comment_id' => (int)$comment->comment_id,
                    'comment_author' => (string)$comment->comment_author,
                    'comment_author_email' => (string)$comment->comment_author_email,
                    'comment_author_IP' => (string)$comment->comment_author_IP,
                    'comment_author_url' => (string)$comment->comment_author_url,
                    'comment_date' => (string)$comment->comment_date,
                    'comment_date_gmt' => (string)$comment->comment_date_gmt,
                    'comment_content' => (string)$comment->comment_content,
                    'comment_approved' => (string)$comment->comment_approved,
                    'comment_type' => (string)$comment->comment_type,
                    'comment_parent' => (string)$comment->comment_parent,
                    'comment_user_id' => (int)$comment->comment_user_id,
                    'commentmeta' => $meta,
               );
            }

            $posts[] = $post;
        }

        return [
            'authors' => $authors,
            'posts' => $posts,
            'categories' => $categories,
            'tags' => $tags,
            'terms' => $terms,
            'base_url' => $base_url,
            'version' => $wxr_version
       ];
    }

    /**
     * Parse file with Xml extension
     */
    protected function xmlParse($file)
    {
        global $wp_filesystem;

        WP_Filesystem();

        $this->wxr_version = $this->in_post = $this->cdata = $this->data = $this->sub_data = $this->in_tag = $this->in_sub_tag = false;
        $this->authors = $this->posts = $this->term = $this->category = $this->tag = [];

        $xml = xml_parser_create('UTF-8');
        xml_parser_set_option($xml, XML_OPTION_SKIP_WHITE, 1);
        xml_parser_set_option($xml, XML_OPTION_CASE_FOLDING, 0);
        xml_set_object($xml, $this);
        xml_set_character_data_handler($xml, 'cdata');
        xml_set_element_handler($xml, 'tag_open', 'tag_close');

        if (!xml_parse($xml, $wp_filesystem->get_contents($file), true)) {
            $current_line = xml_get_current_line_number($xml);
            $current_column = xml_get_current_column_number($xml);
            $error_code = xml_get_error_code($xml);
            $error_string = xml_error_string($error_code);
            return new WP_Error('XML_parse_error', esc_html__('There was an error when reading this WXR file', 'anon'), array($current_line, $current_column, $error_string));
        }

        xml_parser_free($xml);

        if (!preg_match('/^\d+\.\d+$/', $this->wxr_version)) {
            return new WP_Error('WXR_parse_error', esc_html__('This does not appear to be a WXR file, missing/invalid WXR version number', 'anon'));
        }

        return [
            'authors' => $this->authors,
            'posts' => $this->posts,
            'categories' => $this->category,
            'tags' => $this->tag,
            'terms' => $this->term,
            'base_url' => $this->base_url,
            'version' => $this->wxr_version
        ];
    }

    public function tag_open($parse, $tag, $attr)
    {
        if (in_array($tag, $this->wp_tags)) {
            $this->in_tag = substr($tag, 3);
            return;
        }

        if (in_array($tag, $this->wp_sub_tags)) {
            $this->in_sub_tag = substr($tag, 3);
            return;
        }

        switch ($tag) {
            case 'category':
                if (isset($attr['domain'], $attr['nicename'])) {
                    $this->sub_data['domain'] = $attr['domain'];
                    $this->sub_data['slug'] = $attr['nicename'];
                }
                break;
            case 'item': $this->in_post = true;
            // no break
            case 'title': if ($this->in_post) {
                $this->in_tag = 'post_title';
            } break;
            case 'guid': $this->in_tag = 'guid'; break;
            case 'dc:creator': $this->in_tag = 'post_author'; break;
            case 'content:encoded': $this->in_tag = 'post_content'; break;
            case 'excerpt:encoded': $this->in_tag = 'post_excerpt'; break;

            case 'wp:term_slug': $this->in_tag = 'slug'; break;
            case 'wp:meta_key': $this->in_sub_tag = 'key'; break;
            case 'wp:meta_value': $this->in_sub_tag = 'value'; break;
        }
    }

    public function cdata($parser, $cdata)
    {
        if (!trim($cdata)) {
            return;
        }

        if (false !== $this->in_tag || false !== $this->in_sub_tag) {
            $this->cdata .= $cdata;
        } else {
            $this->cdata .= trim($cdata);
        }
    }

    public function tag_close($parser, $tag)
    {
        switch ($tag) {
            case 'wp:comment':
                unset($this->sub_data['key'], $this->sub_data['value']); // remove meta sub_data
                if (!empty($this->sub_data)) {
                    $this->data['comments'][] = $this->sub_data;
                }
                $this->sub_data = false;
                break;
            case 'wp:commentmeta':
                $this->sub_data['commentmeta'][] = array(
                    'key' => $this->sub_data['key'],
                    'value' => $this->sub_data['value']
                );
                break;
            case 'category':
                if (!empty($this->sub_data)) {
                    $this->sub_data['name'] = $this->cdata;
                    $this->data['terms'][] = $this->sub_data;
                }
                $this->sub_data = false;
                break;
            case 'wp:postmeta':
                if (!empty($this->sub_data)) {
                    $this->data['postmeta'][] = $this->sub_data;
                }
                $this->sub_data = false;
                break;
            case 'item':
                $this->posts[] = $this->data;
                $this->data = false;
                break;
            case 'wp:category':
            case 'wp:tag':
            case 'wp:term':
                $n = substr($tag, 3);
                array_push($this->$n, $this->data);
                $this->data = false;
                break;
            case 'wp:author':
                if (!empty($this->data['author_login'])) {
                    $this->authors[$this->data['author_login']] = $this->data;
                }
                $this->data = false;
                break;
            case 'wp:base_site_url':
                $this->base_url = $this->cdata;
                break;
            case 'wp:wxr_version':
                $this->wxr_version = $this->cdata;
                break;

            default:
                if ($this->in_sub_tag) {
                    $this->sub_data[$this->in_sub_tag] =!empty($this->cdata) ? $this->cdata : '';
                    $this->in_sub_tag = false;
                } elseif ($this->in_tag) {
                    $this->data[$this->in_tag] =!empty($this->cdata) ? $this->cdata : '';
                    $this->in_tag = false;
                }
        }

        $this->cdata = false;
    }

    /**
     * Is an image post
     *
     * @return bool
     */
    private function isImage($post)
    {
        if ('attachment' !== (string)$post->post_type)
            return false;

        if (empty($post->attachment_url))
            return false;

        $ext = pathinfo($post->attachment_url, PATHINFO_EXTENSION);

        if (!in_array($ext, ['jpg', 'jpeg', 'jpe', 'gif', 'tif', 'tiff', 'png', 'bmp', 'ico']))
            return false;

        return true;
    }

    /**
     * Extract logos' ID from customize data.
     */
    private function isLogo($post)
    {
        static $logos = null;

        if (!$this->isImage($post)) {
            return false;
        }

        if (null === $logos) {
            global $wp_filesystem;
            if (file_exists($this->base_dir . '/customizer.dat')) {
                $file_contents = $wp_filesystem->get_contents($this->base_dir . '/customizer.dat');
                $customize_data = json_decode($file_contents, true);
                if (null === $customize_data) {
                    $customize_data = maybe_unserialize($file_contents);
                }
            } else {
                $customize_data = [];
            }

            if (empty($customize_data['mods']))
                return false;

            $logos = [];

            foreach ($customize_data['mods'] as $mod_key => $mod_value) {
                if ('custom_logo' === $mod_key) {
                    $logos[] = $mod_value;
                }
                if ('header_sticky_logo' === $mod_key) {
                    $logos[] = $mod_value['id'];
                }
            }
        }

        if (in_array($post->post_id, $logos))
            return true;

        return false;
    }
}
