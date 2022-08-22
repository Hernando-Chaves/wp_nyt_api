<?php 

    if( !defined( 'ABSPATH' ) ) : die;
    endif;

    // If uninstall not called from WordPress, then exit.
    if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) :
        exit;
    endif;

    global $wpdb;
    $main_table_name = $wpdb->prefix . 'nyt_main_table';
    $query = $wpdb->prepare( "DROP TABLE IF EXISTS " . $main_table_name );
    $wpdb->query( $query );
    delete_option( 'nyt_table_version' );