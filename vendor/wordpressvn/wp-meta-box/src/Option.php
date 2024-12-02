<?php

namespace WPVNTeam\WPMetaBox;

use Exception;
use WPVNTeam\WPMetaBox\Options\Checkbox;
use WPVNTeam\WPMetaBox\Options\Choices;
use WPVNTeam\WPMetaBox\Options\CodeEditor;
use WPVNTeam\WPMetaBox\Options\Color;
use WPVNTeam\WPMetaBox\Options\Date;
use WPVNTeam\WPMetaBox\Options\Image;
use WPVNTeam\WPMetaBox\Options\Media;
use WPVNTeam\WPMetaBox\Options\Number;
use WPVNTeam\WPMetaBox\Options\Repeater;
use WPVNTeam\WPMetaBox\Options\Select;
use WPVNTeam\WPMetaBox\Options\Select2;
use WPVNTeam\WPMetaBox\Options\SelectMultiple;
use WPVNTeam\WPMetaBox\Options\Text;
use WPVNTeam\WPMetaBox\Options\Textarea;
use WPVNTeam\WPMetaBox\Options\WPEditor;

class Option
{
    public $meta_box;

    public $type;

    public $args;

    public $implementation;

    public function __construct($meta_box, $type, $args = [])
    {
        $this->meta_box = $meta_box;
        $this->type = $type;
        $this->args = $args;

        $type_map = apply_filters('wp_meta_box_option_type_map', [
            'text' => Text::class,
            'date' => Date::class,
            'number' => Number::class,
            'checkbox' => Checkbox::class,
            'choices' => Choices::class,
            'textarea' => Textarea::class,
            'wp-editor' => WPEditor::class,
            'code-editor' => CodeEditor::class,
            'repeater' => Repeater::class,
            'media' => Media::class,
            'image' => Image::class,
            'color' => Color::class,
            'select' => Select::class,
            'select2' => Select2::class,
        ]);

        if (empty($type_map[$this->type])) {
            throw new Exception("The {$type} option does not exist");
        }

        $this->implementation = new $type_map[$this->type]($this->args, $this->meta_box);
    }

    public function save($object_id = null)
    {
        return $this->implementation->save($object_id);
    }

    public function render()
    {
        echo $this->implementation->render();
    }

    public function add_repeater_option($name, $args)
    {
        if ($this->implementation instanceof Repeater) {
            $this->implementation->add_option($name, $args);
        }

        return $this;
    }
}
