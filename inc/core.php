<?php

function modules_load_textdomain() {
    load_plugin_textdomain( 'modules', false, dirname( plugin_basename( MODULES_FILE ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'modules_load_textdomain' );

function modules_plugin_row_meta( $meta, $file ) {
    if ( $file !== plugin_basename( MODULES_FILE ) ) {
        return $meta;
    }
    $meta[] = '<a href="https://wpvnteam.com/modules/doc/" target="_blank">' . __( 'Documentation' ) . '</a>';
    $meta[] = '<a href="https://wpvnteam.com/donate/" target="_blank">' . __( 'Donate', 'modules' ) . '</a>';
    return $meta;
}
add_filter( 'plugin_row_meta', 'modules_plugin_row_meta', 10, 2 );