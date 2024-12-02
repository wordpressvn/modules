<?php

namespace WPVNTeam\WPMetaBox\Options;

use WPVNTeam\WPMetaBox\Enqueuer;

use function WPVNTeam\WPMetaBox\resource_content;

class Checkbox extends OptionAbstract
{
    public $view = 'checkbox';
    private static $scripts_loaded = false;

    public function __construct($section, $args = [])
    {
        parent::__construct($section, $args);
        
        if ($this->is_select()) {
            add_action('admin_enqueue_scripts', [$this, 'enqueue']);
        }
    }

	public function enqueue()
	{
        Enqueuer::add('wmb-checkbox', function () {
            if (!self::$scripts_loaded) {
                self::$scripts_loaded = true;
                wp_register_script('wmb-checkbox', false);
                wp_enqueue_script('wmb-checkbox');
                wp_add_inline_script('wmb-checkbox', 'jQuery(function($) { $(".select-all").on("click", function() { $(this).closest("div").find("input[type="checkbox"]").prop("checked", true); }); $(".deselect").on("click", function() { $(this).closest("div").find("input[type="checkbox"]").prop("checked", false); }); });');
            }
        });
	}

    public function is_checked()
    {
        return parent::get_value_attribute();
    }

    public function is_select()
    {
        return $this->get_arg('select', false);
    }

    public function is_multiple()
    {
        return $this->get_arg('multiple', false);
    }

    public function get_options()
    {
        $options = $this->get_arg('options', []);

        if(is_callable($options)) {
            return $options();
        }

        return $options;
    }

    public function get_name_attribute()
    {
        $name = parent::get_name_attribute();

        if ($this->is_multiple()) {
            return "{$name}[]";
        }

        return $name;
    }

    public function get_value_from_request()
    {
        return $_REQUEST[parent::get_name_attribute()] ?? [];
    }

    public function get_value_attribute()
    {
        if (! $this->is_multiple()) {
            return '1';
        }

        $value = parent::get_value_attribute();

        if (empty($value)) {
            return [];
        }

        return (array) $value;
    }
}