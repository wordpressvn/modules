<?php

namespace WPVNTeam\WPMetaBox\Options;

use WPVNTeam\WPMetaBox\Enqueuer;

use function WPVNTeam\WPMetaBox\resource_content;

class Select2 extends Select
{
    public $view = 'select2';
    private static $scripts_loaded = false;

    public function __construct($section, $args = [])
    {
        parent::__construct($section, $args);

        $this->input_attributes['wmb-select2'] = $this->get_config();

        if ($this->is_using_ajax()) {
            $action = "wmb_select2_".$this->generate_hash();

            $this->input_attributes['wmb-select2:action'] = $action;

            add_action('wp_ajax_' . $action, [$this, 'handle_action']);
        }

        add_action('admin_enqueue_scripts', [$this, 'enqueue']);
    }

    public function is_using_ajax()
    {
        return !empty($this->args['ajax']);
    }

    public function is_multiple()
    {
        if(isset($this->get_arg('config')['multiple']) && $this->get_arg('config')['multiple']) {
            return true;
        }

        return $this->get_arg('multiple', false);
    }

    public function get_config()
    {
        $default = [
            'placeholder' => __('Select an option'),
            'allowClear' => true,
            'minimumInputLength' => 2,
            'width' => '100%',
            '_is_using_ajax' => $this->is_using_ajax()
        ];

        return array_merge($default, $this->get_arg('config', []));
    }

    public function get_value_label()
    {
        if (isset($this->args['ajax']['value']) && is_callable($this->args['ajax']['value'])) {
            return call_user_func($this->args['ajax']['value'], $this->get_value_attribute(), $this);
        }

        return null;
    }

    public function handle_action()
    {
        if (! current_user_can($this->meta_box->capability)) {
            return;
        }

        if (isset($this->args['ajax']['action']) && is_callable($this->args['ajax']['action'])) {
            return call_user_func($this->args['ajax']['action'], $this);
        }

        return null;
    }

    public function enqueue()
    {
        Enqueuer::add('wmb-select2', function () {
            if (!self::$scripts_loaded) {
                self::$scripts_loaded = true;
                $select2_assets = apply_filters('wmb_select2_assets', [
                    'js' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js',
                    'css' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css'
                ]);

                wp_enqueue_style('wmb-select2', $select2_assets['css']);
                wp_enqueue_script('wmb-select2', $select2_assets['js'], ['jquery']);

                wp_add_inline_script('wmb-select2', resource_content('js/wmb-select2.js'));
            }
        });
    }
}
