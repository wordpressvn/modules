<?php
/**
 * Plugin Name:         WP Meta Box
 * Plugin URI:          https://github.com/wordpressvn/wp-meta-box
 * Description:         This package aims to make it easier to create meta boxes for WordPress plugins.
 * Version:             1.0.0
 * Author:              WordPress Vietnam Team
 * Author URI:          https://wpvnteam.com
 * Text Domain:         wp-meta-box
 * Domain Path:         /languages
 * License:             GPLv3
 */
if (!defined('ABSPATH')) { exit; }

if (!class_exists('WPMetaBox')) {
    class WPMetaBox {
        public function __construct() {
            $this->load_autoloader();
        }
        private function load_autoloader() {
            $autoload_path = MODULES_FILE . '/vendor/autoload.php';
            if (file_exists($autoload_path)) {
                require_once $autoload_path;
            }
        }
    }
    new WPMetaBox();
}