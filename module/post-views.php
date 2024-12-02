<?php
/**
 * Plugin Name: Views
 * Description: Enables you to display how many times a post/page has been viewed.
 * Version:     1.0.0
 * Author:      TienCOP
 * Author URI:  https://wpvnteam.com
 * Tags:        post, product, page
 */
if (!defined('ABSPATH')) exit;

if (!class_exists('PostViews')) {
    class PostViews {
        public function __construct() {
            add_action('init', [ $this, 'register_meta_box' ]);
            add_action('wp_head', [$this, 'increment_view_count']);
            add_shortcode('views', [$this, 'display_view_count']);
            add_action('manage_posts_custom_column', [$this, 'show_view_count_in_column'], 10, 2);
            add_filter('manage_posts_columns', [$this, 'add_view_column']);
            add_action('manage_pages_custom_column', [$this, 'show_view_count_in_column'], 10, 2);
            add_filter('manage_pages_columns', [$this, 'add_view_column']);
            add_action('manage_product_posts_custom_column', [$this, 'show_view_count_in_column'], 10, 2);
            add_filter('manage_product_posts_columns', [$this, 'add_view_column']);
        }

        public function increment_view_count() {
            if (is_singular(['post', 'page', 'product'])) {
                $post_id = get_the_ID();
                if ($post_id) {
                    $views = (int) get_post_meta($post_id, 'views', true) + 1;
                    update_post_meta($post_id, 'views', $views);
                }
            }
        }

        public function display_view_count() {
            return __('Views', 'modules').': ' . (int) get_post_meta(get_the_ID(), 'views', true);
        }

        public function add_view_column($columns) {
            $columns['views'] = __('Views', 'modules');
            return $columns;
        }

        public function show_view_count_in_column($column, $post_id) {
            if ($column === 'views') {
                $views = (int) get_post_meta($post_id, 'views', true);
                echo esc_attr($views ? $views : '0');
            }
        }
        
        public function register_meta_box() {
            $this->add_options();
        }
        
        public function add_options() {
            $post_types = ['post', 'page', 'product'];
            foreach ($post_types as $post_type) {
                $meta_box = \WPVNTeam\WPMetaBox\WPMetaBox::post('Views')
                    ->set_post_type($post_type);
                $meta_box->set_context('side');
                $meta_box->set_priority('default');
                $meta_box->set_prefix('');
                $meta_box->add_option('text', [
                    'name' => 'views',
                    'label' => __( 'Views', 'modules' )
                ]);
                $meta_box->make();
            }
        }
    }
    new PostViews();
}