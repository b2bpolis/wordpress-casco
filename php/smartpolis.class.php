<?php
  class smartPolis {
    public function __construct() {
      add_shortcode( 'smartpolis', array( &$this, 'show_form' ) );
      add_action('wp_print_styles', array(&$this, 'add_css'));
      add_action( 'init', array( &$this, 'add_js' ) );
    }

    function add_css () {
      wp_enqueue_style('smartpolis_client', SMARTPOLIS_PLUGIN_URL . 'css/style.css');
    }

    public function add_js() {
      wp_enqueue_script('jquery');
      wp_enqueue_script('smartpolis', SMARTPOLIS_PLUGIN_URL . 'js/smartpolis.js', array('jquery'));
    }
    
    public function show_form() {
      $name = 'smartpolis_form.php';

      $path = locate_template( $name );

      if ( ! $path ) {
        $path = SMARTPOLIS_PLUGIN_DIR . "default-templates/$name";
      }

      load_template($path);
    }
  }
?>
