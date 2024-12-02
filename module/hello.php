<?php
/**
 * Plugin Name: Sample
 * Plugin URI:  https://wpvnteam.com
 * Description: This is a sample. Delete me!
 * Version:     1.0.0
 * Author:      WordPress Vietnam Team
 * Author URI:  https://wpvnteam.com
 * Tags:        test
 */
if (!defined('ABSPATH')) exit;

if (!class_exists('HelloModule')) {
    class HelloModule {
        public function __construct() {
            //PHP Code
        }
    }
    new HelloModule();
}