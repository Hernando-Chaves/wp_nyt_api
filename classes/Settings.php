<?php 

if ( !defined( 'ABSPATH' ) ) : die;
endif;

if( !class_exists( 'Settings' ) ):

    class Settings
    {
      /**
       * Set de capability
       *
       * @var [type]
       */
      protected $capability;

      /**
       * Set the slug mto the menu page
       *
       * @var [type]
       */
      protected $slug;

      /**
       * Set the base_url API
       *
       * @var [type]
       */
      protected $api_url;

      /**
       * Set de books option in options table in database
       *
       * @var [type]
       */
      protected $books_option;

      public function __construct()
      {
        $this->slug         = 'nyt_bs';
        $this->api_url      = $_ENV['NYT_URL'];
        $this->capability   = 'manage_options';
        $this->books_option = 'nyt_books_option';
        add_action( 'admin_menu', [$this, 'nyt_add_menu_page'] );
        add_shortcode( 'nyt_api', [$this, 'nyt_add_shortcode'] );
      }

      /**
       * Add admin menu item
       *
       * @return void
       */
      public function nyt_add_menu_page(  )
      {
          add_menu_page( 
            esc_html( 'NYT Best Sellers', NYT_DOMAIN ),
            esc_html( 'NYT Best Sellers', NYT_DOMAIN ),
            $this->capability,
            $this->slug,
            [ $this, 'nyt_run_all' ],
            'dashicons-awards',
            20
          );
      }

      /**
       * Call al the functions 
       *
       * @return void
       */
      public function nyt_run_all()
      {
        /**
         * Save the API data like option in the database
         */
        if( false === get_option( $this->books_option ) ):
            $info_books = $this->nyt_view_best_sellers();
    
            add_option( $this->books_option, $info_books );    
          return;
        endif;        

      }

      /**
       * Connect with de API  and retrieve de info
       *
       * @return void
       */
      public function nyt_view_best_sellers()
      {
        $full_url = $this->api_url . "?api-key=" . $_ENV['NYT_KEY'] . "&offset=20";
        $args = [
          'headers' => [
            'Content-Type' => 'application/json',
          ],
          'body' => [],
        ];
          $response      = wp_remote_get( $full_url, $args );
          $body          = wp_remote_retrieve_body( $response );
          $response_code = wp_remote_retrieve_response_code( $response );
         
          if( $response_code === 401 ):
            return esc_html( 'Acceso no autorizado', NYT_DOMAIN );
          endif;

          if ( $response_code !== 200 ):
            return esc_html( 'Error al conectar con la API', NYT_DOMAIN );
          endif;

          if( $response_code === 200 ):
            return $body; 
          endif;
      }

      /**
       * Create settings andshortcode view
       *
       * @param array $atts
       * @param [type] $content
       * @param string $tag
       * @return void
       */
      public function nyt_add_shortcode( $atts = [], $content= null, $tag = '' )
      {
        $atts = array_change_key_case( (array) $atts, CASE_LOWER );
          extract( shortcode_atts( [
            'cantidad' => '',
          ], 
          $atts,$tag ) );

          if( !empty( $cantidad ) ):
            $cantidad = array_map( 'absint', $cantidad );
          endif;

          ob_start();
          require  NYT_PATH .  '/includes/shortcode_view.php';
          return ob_get_clean();
         
          
      }

    }

endif;