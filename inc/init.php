<?php

function get_module_data($plugin_file) {
    $default_headers = array(
        'Name'      => 'Plugin Name',
        'PluginURI' => 'Plugin URI',
        'Version'   => 'Version',
        'Description'   => 'Description',
        'Author'    => 'Author',
        'AuthorURI' => 'Author URI',
        'Tags'      => 'Tags',
    );
    $module_data = get_file_data($plugin_file, $default_headers, 'plugin');
    return $module_data;
}


function modules_add_admin_menu() {
    add_menu_page('Module', 'Module', 'manage_options', 'modules', 'modules_page', 'dashicons-plugins-checked', 65);
}
add_action('admin_menu', 'modules_add_admin_menu');

function modules_load_classes() {
    $active_classes = get_option('modules', []);
    foreach ($active_classes as $class_name) {
        $file_path = MODULES_CLASSES . $class_name . '.php';
        if (file_exists($file_path)) {
            require_once $file_path;
        }
    }
}
add_action('plugins_loaded', 'modules_load_classes');

function ajax_modules_classes() {
    if ( ! isset( $_POST['modules_nonce'] ) || ! check_admin_referer( 'modules_manage_classes', 'modules_nonce' ) ) {
        wp_send_json_error( esc_html__('Security check failed.') );
        exit;
    }
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( esc_html__('Sorry, you are not allowed to update themes for this site.') );
        exit;
    }
    $file_to_delete = isset( $_POST['file_name'] ) ? sanitize_text_field( wp_unslash( $_POST['file_name'] ) ) : '';
    if ( empty( $file_to_delete ) ) {
        wp_send_json_error( esc_html__('File does not exist?') );
        exit;
    }
    $file_path = MODULES_CLASSES . $file_to_delete;
    if ( file_exists( $file_path ) ) {
        if ( wp_delete_file( $file_path ) ) {
            wp_send_json_success( esc_html__('Success!') );
        } else {
            wp_send_json_error( esc_html__('Failed!') );
        }
    } else {
        wp_send_json_error( esc_html__('File does not exist?') );
    }
    exit;
}
add_action( 'wp_ajax_modules_classes', 'ajax_modules_classes' );

function modules_enqueue_admin_scripts( $hook ) {
    if ( $hook !== 'toplevel_page_modules' ) {
        return;
    }
    wp_enqueue_script( 'modules_classes', MODULES_URL . 'assets/js/module.js', ['jquery'], null, true );
    wp_localize_script( 'modules_classes', 'modules', array(
        'deletes' => __('Are you sure you want to delete these files?'),
        'errors' => __('An error occurred in the upload. Please try again later.'),
        'modules_nonce' => wp_create_nonce( 'modules_manage_classes' ),
        'ajax_url' => admin_url( 'admin-ajax.php' )
    ) );
}
add_action( 'admin_enqueue_scripts', 'modules_enqueue_admin_scripts' );

function modules_page() {
    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('Sorry, you are not allowed to update themes for this site.'));
    }

    $classes_folder = MODULES_CLASSES;
    $all_classes = [];
    $active_classes = get_option('modules', []);
    
    if (is_dir($classes_folder)) {
        foreach (glob($classes_folder . '*.php') as $file) {
            $class_name = pathinfo($file, PATHINFO_FILENAME);
            require_once $file;
            $get_module = get_module_data($file);
            if (!$get_module) {
                $get_module = ['Name' => $class_name];
            }
            $all_classes[$class_name] = $get_module;
        }
    }

    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST' && check_admin_referer('modules_manage_classes')) {
        $selected_classes = isset($_POST['classes']) ? array_map('sanitize_text_field', wp_unslash($_POST['classes'])) : [];
        update_option('modules', $selected_classes);
        $active_classes = $selected_classes;
        echo '<div class="updated"><p>' . esc_html__('All updates have been completed.') . '</p></div>';
    }

    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Features'); ?></h1>
        <form method="post">
            <?php wp_nonce_field('modules_manage_classes'); ?>
            <table class="wp-list-table widefat plugins">
                <thead>
                    <tr>
                        <td class="check-column"><input type="checkbox" id="select-all-classes"></td>
                        <th><?php esc_html_e('Features'); ?></th>
                        <th><?php esc_html_e('Description'); ?></th>
                        <th class="manage-column column-auto-updates"><?php esc_html_e('Tags'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($all_classes as $class_name => $module_data): 
                        $is_active = in_array($class_name, $active_classes, true);
                    ?>
                        <tr class="<?php echo $is_active ? 'active' : 'inactive'; ?>">
                            <th class="check-column">
                                <input type="checkbox" name="classes[]" value="<?php echo esc_attr($class_name); ?>" <?php checked($is_active); ?>>
                            </th>
                            <td class="plugin-title column-primary"><?php echo $is_active ? '<strong>' . $module_data['Name'] . '</strong>' : $module_data['Name']; ?>
                            <?php if ($class_name !== "module-pro") { ?>
                                <div class="row-actions visible">
                                    <span><a href="<?php echo esc_url(admin_url('plugin-editor.php?file=' . urlencode('modules/classes/' . $class_name . '.php') . '&plugin=' . urlencode('modules/modules.php'))); ?>">
                                        <?php esc_html_e('Edit'); ?>
                                    </a> | </span>
                                    <span class="delete">
                                    <a href="#" class="delete delete-class-btn" data-file-name="<?php echo esc_attr($class_name . '.php'); ?>">
                                        <?php esc_html_e('Delete'); ?>
                                    </a></span>
                                </div>
                            <?php } ?>
                            </td>
                            <td>
                            <div class="plugin-description"><p><?php echo $module_data['Description']; ?></p></div>
                            <div class="second plugin-version-author-uri">
                            <?php $module_meta = array();

                            if ( ! empty( $module_data['Version'] ) ) {
                                /* translators: %s: Module version number. */
                                $module_meta[] = sprintf( __( 'Version %s' ), $module_data['Version'] );
                            }

                            if ( ! empty( $module_data['Author'] ) ) {
                                $author = $module_data['Author'];

                                if ( ! empty( $module_data['AuthorURI'] ) ) {
                                    $author = '<a href="' . $module_data['AuthorURI'] . '">' . $module_data['Author'] . '</a>';
                                }

                                /* translators: %s: Module author name. */
                                $module_meta[] = sprintf( __( 'By %s' ), $author );
                            }

                            if ( ! empty( $module_data['PluginURI'] ) ) {
                                /* translators: %s: Module name. */
                                $aria_label = sprintf( __( 'Visit plugin site for %s' ), $module_data['Name'] );

                                $module_meta[] = sprintf(
                                    '<a href="%s" aria-label="%s">%s</a>',
                                    esc_url( $module_data['PluginURI'] ),
                                    esc_attr( $aria_label ),
                                    __( 'Documentation' )
                                );
                            }
                            echo implode( ' | ', $module_meta );
                            ?>
                            </div>
                            </td>
                            <td><?php echo $module_data['Tags']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php submit_button(esc_html__('Save Changes')); ?>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectAllCheckbox = document.getElementById('select-all-classes');
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function () {
                    const checkboxes = document.querySelectorAll('input[name="classes[]"]');
                    checkboxes.forEach(checkbox => checkbox.checked = this.checked);
                });
            }
        });
    </script>
    <?php
}