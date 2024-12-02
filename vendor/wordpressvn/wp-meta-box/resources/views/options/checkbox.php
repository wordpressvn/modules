<?php if($option->is_multiple()) : ?>
    <ul>
        <?php foreach ($option->get_options() as $key => $label) { ?>
            <li>
            <input name="<?php echo esc_attr($option->get_name_attribute()); ?>" id="<?php echo $option->get_id_attribute(); ?>_<?php echo $key; ?>" type="checkbox" value="<?php echo $key; ?>" <?php echo in_array($key, $option->get_value_attribute()) ? 'checked' : null; ?>><label for="<?php echo $option->get_id_attribute(); ?>_<?php echo $key; ?>"><?php echo $label; ?></label>
            </li>
        <?php } ?>
    </ul>
    <?php if($option->is_select()) { ?>
        <p><a href="javascript:void(0);" class="select-all"><?php _e('Select all'); ?></a> | <a href="javascript:void(0);" class="deselect"><?php _e('Deselect'); ?></a></p>
    <?php } ?>
<?php else: ?>
    <input type="checkbox" <?php echo $option->get_input_attributes_string(['value' => $option->get_value_attribute()]); ?>
    <?php echo $option->is_checked() ? 'checked' : null; ?>>
<?php endif; ?>



        