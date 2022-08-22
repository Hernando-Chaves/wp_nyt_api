<?php 

if ( !defined( 'ABSPATH' ) ) : die;
endif;

if( !class_exists( 'Deactivated_class' ) ):

    class Deactivated_class
    {
       public function __construct()
        {
            $this->nyt_remove_tables();
        }

        /**
         * Remove all the things created on plugin activation
         *
         * @return void
         */
        public function nyt_remove_tables()
        {
            global $wpdb;
            $main_table_name = $wpdb->prefix . NYT_TABLE_NAME;
            $wp_query = $wpdb->prepare( "DROP TABLE IF EXISTS " . $main_table_name );

            $wpdb->query( $wp_query );

            delete_option( 'nyt_table_version' );
        }
    }

endif;