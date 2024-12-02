<textarea <?php echo $option->get_input_attributes_string(); ?> rows="<?php echo $option->get_arg('rows', 5); ?>"
            cols="<?php echo $option->get_arg('cols', 50); ?>"><?php echo $option->get_value_attribute(); ?></textarea>
