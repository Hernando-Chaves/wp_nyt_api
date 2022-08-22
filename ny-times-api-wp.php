<?php 


/**
*
*Plugin Name:       NEW YORK TIMES API WP
*Plugin URI:        bogotawebcompany.com
*Description:       Plugin desarrollado para practicar conexión con API de NY Times
*Version:           1.0.0
*Author:            Hernando j Chaves
*Author URI:        bogotawebcompany.com
*License:           GPL-2.0+
*License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
*Text Domain:       ny_times
*/

use Dotenv\Dotenv;

if ( !defined( 'ABSPATH' ) ) : die;
endif;

require_once 'vendor/autoload.php';
require_once 'vendor/vlucas/phpdotenv/src/Dotenv.php';
$dotenv = Dotenv::createImmutable( __DIR__ );
$dotenv->load();



if( !class_exists( 'nyt_final_class' ) ):

    final class nyt_final_class
    {
    public function __construct()
        {
            $this->nyt_load_constants();
            add_action( 'plugins_loaded', [ $this, 'nyt_plugins_loaded' ] );
            register_activation_hook( __FILE__ , [$this, 'nyt_activation'] );
            register_deactivation_hook( __FILE__ , [ $this, 'nyt_deactivation'] );  
        }

        /**
         * Define global variables
         *
         * @return void
         */
        public function nyt_load_constants()
        {
            define( 'NYT_VERSION', '1.0.0' );
            define( 'NYT_DOMAIN', 'ny_times' );
            define( 'NYT_TABLE_VERSION', '1.0.0' );
            define( 'NYT_TABLE_NAME', 'nyt_main_table' );
            define( 'NYT_PATH', untrailingslashit(plugin_dir_path( __FILE__ ) ) );
            define( 'NYT_URL', untrailingslashit(plugins_url( '/', __FILE__ ) )  );
        }

        /**
         * Call the classes when plugins loaded
         *
         * @return void
         */
        public function nyt_plugins_loaded()
        {
            new Settings();
        }

    /**
         * Set all the actions when plugin is activated
         *
         * @return void
         */
        public function nyt_activation()
        {
            new Activated_class();
        }
        
        /**
         * Set all the actions when plugin is deactivated
         *
         * @return void
         */
        public function nyt_deactivation()
        {
            // new Deactivated_class();
        }

        /**
         * Implement the singleton pattern
         *
         * @return void
         */
        public static function nyt_singleton()
        {
            static $instance = false;
            if( !$instance ):
                $instance = new self();
            endif;
            return $instance;
            
        }
}

endif;

/**
 * Function that initualize the final class
 *
 * @return void
 */
if( !function_exists( 'nyt_init_class' ) ):

    function nyt_init_class()
    {
        return nyt_final_class::nyt_singleton();
    }

    nyt_init_class();

endif;