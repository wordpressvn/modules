<?php

namespace WPVNTeam\WPMetaBox\Options;

use WPVNTeam\WPMetaBox\Enqueuer;

use function WPVNTeam\WPMetaBox\resource_content;

class WPEditor extends OptionAbstract
{
    public $view = 'wp-editor';

    public function __construct($args, $meta_box)
    {
        parent::__construct($args, $meta_box);
    }
    
    public function get_config()
    {
        $default = [
            'textarea_name' => $this->get_name_attribute(),
            'wpautop' => true,
            'teeny' => false,
            'media_buttons' => true,
            'default_editor' => 'tinymce',
            'drag_drop_upload' => false,
            'textarea_rows' => 10,
            'tabindex' => null,
            'tabfocus_elements' => '',
            'editor_css' => '',
            'editor_class' => '',
            'tinymce' => true,
            'quicktags' => true,
        ];

        return array_merge($default, $this->get_arg('config', []));
    }

    public function sanitize($value)
    {
        return wp_kses_post($value);
    }
}
