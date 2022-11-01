<?php

if (!function_exists('FoundationPress_scripts')) :
  function FoundationPress_scripts() {

    // deregister the jquery version bundled with wordpress
  //  wp_deregister_script( 'jquery' );
    
    // register scripts
 //   wp_register_script( 'modernizr', get_template_directory_uri() . '/js/modernizr/modernizr.min.js', array(), '1.0.0', false );
 //   wp_register_script( 'jquery', get_template_directory_uri() . '/js/jquery/dist/jquery.min.js', array(), '1.0.0', false );
 //   wp_register_script( 'foundation', get_template_directory_uri() . '/js/app.js', array('jquery'), '1.0.0', true );
 //   wp_register_script( 'dakoutros', get_template_directory_uri() . '/js/dakoutros1.js', array('jquery'), '1.0.0', true );
    
  //     wp_register_script( 'jquery6', get_template_directory_uri() . '/js/vendor/jquery6.js', array(), '1.0.0', false );
      //  wp_register_script( 'jquery6', get_template_directory_uri() . '/js/dakoutros1.js', array(), '1.0.0', false );
  //  wp_register_script( 'f6', get_template_directory_uri() . '/js/vendor/foundation6.js', array('jquery'), '1.0.0', true );
  //  wp_register_script( 'f61', get_template_directory_uri() . '/js/app6.js', array('jquery'), '1.0.0', true );
    
  //   wp_register_script( 'foundation1', get_template_directory_uri() . '/js/foundation/js/foundation/foundation.dropdown.js', array('jquery'), '1.0.0', true );
  //   wp_register_script( 'jquery2', get_template_directory_uri() . '/js/jquery/dist/jquery.min.js', array(), '1.0.0', true );
  //   wp_register_script( 'slick', get_template_directory_uri() . '/js/jquery/dist/slick.js', array(), '1.0.0', true );
 //    wp_register_script( 'myslick', get_template_directory_uri() . '/js/jquery/dist/myslick.js', array(), '1.0.0', true );
     
    // wp_register_script( 'offcanvas', get_template_directory_uri() . '/js/foundation/js/foundation/foundation.offcanvas.js', array('jquery'), '1.0.0', true );
    
 

    // enqueue scripts
  //  wp_enqueue_script('modernizr');
  //  wp_enqueue_script('jquery');
    
    //wp_enqueue_script('foundation');
     //wp_enqueue_script('dakoutros');
     
     
     
  //       wp_enqueue_script('jquery6');

 //    wp_enqueue_script('f6');
 //    wp_enqueue_script('f61');
 //   wp_enqueue_script('foundation1');
//    wp_enqueue_script('jquery2');
//    wp_enqueue_script('slick');
  //  wp_enqueue_script('myslick');
//     //wp_enqueue_script('offcanvas');
   

  }

  add_action( 'wp_enqueue_scripts', 'FoundationPress_scripts' );
endif;

?>