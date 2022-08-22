<?php 

if ( !defined( 'ABSPATH' ) ) : die;
endif;

if( !class_exists( 'Activated_class' ) ):

    class Activated_class
    {
      /**
       * Set the global table name
       *
       * @var [type]
       */ 
       protected $nyt_main_table;

       /**
        * Set the $wpdb global variable 
        *
        * @var [type]
        */
       protected $wpdb;

       /**
        * Set the default character set and collaation
        *
        * @var [type]
        */
       protected $charset_collate;

       /**
        * Set the table version
        *
        * @var [type]
        */
       protected $table_version;
      
       public function __construct()
        {
            global $wpdb;
            $this->wpdb            = $wpdb;
            $this->table_version   = NYT_TABLE_VERSION;
            $this->charset_collate = $this->wpdb->get_charset_collate();
            $this->nyt_main_table  = $this->wpdb->prefix . NYT_TABLE_NAME;
            $this->nyt_admin_tables();
        }
        /**
         * Create the tables
         *
         * @return void
         */
        public function nyt_admin_tables()
        {
            if( $this->wpdb->get_var( "SHOW TABLES LIKE '" . $this->nyt_main_table .  "'" ) != $this->nyt_main_table  ):
                $this->nyt_create_tables();      
                $this->nty_save_tables();
            else:
                $this->nyt_update_table();
            endif;
        }

        public function nyt_create_tables()
        {
            $sql = "CREATE TABLE $this->nyt_main_table (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                title text(34) NOT NULL,
                book_description text(73) NOT NULL,
                contributor text(20),
                author text(20),
                price text(20),
                publisher text(20),
                PRIMARY KEY  (id)
            ) $this->charset_collate;";
            require_once  ABSPATH . 'wp-admin/includes/upgrade.php';
            dbDelta( $sql );

            add_option( 'nyt_table_version', $this->table_version  );
        }
        /**
         * Save the API info in the database
         *
         * @return void
         */
        public function nty_save_tables()
        {
            $results = json_decode( get_option( 'nyt_books_option' ) )->results;

            foreach( $results as $result ):
 
                $this->wpdb->insert(
                    $this->nyt_main_table,
                    [
                        'title'            => $result->title,
                        'book_description' => $result->description,
                        'contributor'      => $result->contributor,
                        'author'           => $result->author,
                        'price'            => $result->price,
                        'publisher'        => $result->publisher,
                    ]
                );
            endforeach;
        }

        /**
         * Verify the table version and update if is a new version
         *
         * @return void
         */
        public function nyt_update_table()
        {
            global $nyt_table_version;
            $installed_version = get_option( 'nyt_table_version' );

            if( $installed_version != $nyt_table_version ):

                $sql = "CREATE TABLE $this->nyt_main_table (
                    id mediumint(9) NOT NULL AUTO_INCREMENT,
                    time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                    title text(34) NOT NULL,
                    book_description text(73) NOT NULL,
                    contributor text(20),
                    author text(20),
                    price text(20),
                    publisher text(20),
                    PRIMARY KEY  (id)
                ) $this->charset_collate;";
                require_once  ABSPATH . 'wp-admin/includes/upgrade.php';
                dbDelta( $sql );

                update_option( 'nyt_table_version', $this->table_version  );
                
            endif;
        }

    }

endif;