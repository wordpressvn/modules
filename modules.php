<?php
/**
 * Plugin name:         Module
 * Description:         ⚡️ This is a lightweight and simple plugin that gives you the ability to easily add your features from WordPress admin.
 * Version:             1.0.0
 * Author:              TienCOP
 * Author URI:          https://wpvnteam.com
 * Text Domain:         modules
 * Domain Path:         /languages
 * License:             GPLv2
 */
if (!defined('ABSPATH')) { exit; }

if ( ! defined( 'MODULES_VERSION' ) ) {
    define( 'MODULES_VERSION', '1.0.0' );
}
if ( ! defined( 'MODULES_FILE' ) ) {
    define( 'MODULES_FILE', __FILE__ );
}
if ( ! defined( 'MODULES_DIR' ) ) {
    define( 'MODULES_DIR', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'MODULES_URL' ) ) {
    define( 'MODULES_URL', plugin_dir_url( __FILE__ ) );
}
if ( ! defined( 'MODULES_CLASSES' ) ) {
    define( 'MODULES_CLASSES', plugin_dir_path(__FILE__) . 'module/' );
}
if ( !class_exists('ModuleUpdater') ) {
    include_once __DIR__ . '/inc/updates.php';
}

require_once __DIR__ . '/inc/core.php';
require_once __DIR__ . '/inc/init.php';