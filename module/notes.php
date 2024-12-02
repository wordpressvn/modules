<?php
/**
 * Plugin Name: Notes
 * Description: Create simple notes for posts
 * Version:     1.0.0
 * Author:      TienCOP
 * Author URI:  https://wpvnteam.com
 * Tags:        post, product
 */
if (!defined('ABSPATH')) exit;

if (!class_exists('Notes')) {
    class Notes {
        public function __construct() {
            add_action('init', [ $this, 'register_meta_box' ]);
            add_action('manage_posts_custom_column', [$this, 'show_view_count_in_column'], 10, 2);
            add_filter('manage_posts_columns', [$this, 'add_view_column']);
            add_action('manage_product_posts_custom_column', [$this, 'show_view_count_in_column'], 10, 2);
            add_filter('manage_product_posts_columns', [$this, 'add_view_column']);
        }

        public function display_view_count() {
            return get_post_meta(get_the_ID(), '_notes', true);
        }

        public function add_view_column($columns) {
            $columns['notes'] = __('Notes');
            return $columns;
        }

        public function show_view_count_in_column($column, $post_id) {
            if ($column === 'notes') {
                $views = get_post_meta($post_id, '_notes', true);
                echo esc_attr($views ? $views : '');
            }
        }
        
        public function register_meta_box() {
            $this->add_options();
        }
        
        public function add_options() {
            $post_types = ['post', 'product'];
            foreach ($post_types as $post_type) {
                $meta_box = \WPVNTeam\WPMetaBox\WPMetaBox::post(__('Notes'))
                    ->set_post_type($post_type);
                $meta_box->set_context('side');
                $meta_box->set_priority('default');
                //$meta_box->set_prefix('');
                $meta_box->add_option('textarea', [
                    'name' => 'notes',
                    'label' => __( 'Notes' )
                ]);
                $meta_box->make();
            }
        }
    }
    new Notes();
}