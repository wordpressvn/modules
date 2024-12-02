<?php

namespace WPVNTeam\WPMetaBox\Options;

use WPVNTeam\WPMetaBox\Enqueuer;

use function WPVNTeam\WPMetaBox\resource_content as resource_content;

class Color extends OptionAbstract
{
    public $view = 'text';
    private static $scripts_loaded = false;

    public function __construct($section, $args = [])
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueue']);

        $this->default_args = array_merge($this->default_args, [
            'css' => ['input_class' => 'wmb-color-picker'],
            'type' => 'text',
        ]);

        parent::__construct($section, $args);
    }

    public function enqueue()
    {
        Enqueuer::add('wp-color-picker', function () {
            if (!self::$scripts_loaded) {
                self::$scripts_loaded = true;
                wp_enqueue_script('wp-color-picker');
                wp_enqueue_style('wp-color-picker');
                wp_add_inline_script('wp-color-picker', 'jQuery(function($){$(".wmb-color-picker").wpColorPicker();})');
            }
        });
    }
}
