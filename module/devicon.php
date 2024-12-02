<?php
/**
 * Plugin Name: Devicon
 * Plugin URI:  https://devicon.dev
 * Description: Devicon is a set of icons representing programming languages, designing, and development tools. You can use it as a font or directly copy/paste the SVG code into your project.
 * Version:     2.16.0
 * Author:      TienCOP
 * Author URI:  https://wpvnteam.com
 * Tags:        icons
 */
if (!defined('ABSPATH')) exit;

if (!class_exists('Devicon')) {
    class Devicon {
        public function __construct() {
            add_action('wp_enqueue_scripts', [$this, 'enqueue_style']);
        }
        public function enqueue_style() {
            wp_enqueue_style('devicon', 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/devicon.min.css', [], '2.16.0');
        }
    }
    new Devicon();
}