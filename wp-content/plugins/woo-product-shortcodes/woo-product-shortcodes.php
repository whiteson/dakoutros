<?php

/*
Plugin Name: Woo Product Shortcodes (Free)
Plugin URI: https://www.thewpmakers.com/woo-product-shortcodes-free/
Description: Let us look after your Wordpress Updates.
Version: 2.2
Author: The WP Makers
Author URI: https://www.thewpmakers.com
Text Domain: woo-product-shortcodes-free
*/

if ( !function_exists( 'zfunction___woo_product_shortcode___menu' ) ) {
    function zfunction___woo_product_shortcode___menu() {
       add_management_page(
          'Woo Product Shortcodes',
          'Woo Product Shortcodes',
          'manage_options',
          'product_shortcodes_main___dashboard',
          'zfunction___woo_product_shortcodes___dashboard'
       );
       add_submenu_page(
          NULL,
          'Product Shortcodes: Settings',
          NULL,
          'manage_options',
          'product_shortcodes_main___settings',
          'zfunction___woo_product_shortcodes___settings'
       );
       add_submenu_page(
          NULL,
          'Product Shortcodes: Errors',
          NULL,
          'manage_options',
          'product_shortcodes_main___errors',
          'zfunction___woo_product_shortcodes___error_logs'
       );
    }
    add_action('admin_menu', 'zfunction___woo_product_shortcode___menu');
}



if ( !function_exists( 'zfunction___woo_product_shortcode___register_setting___group' ) ) {
    function zfunction___woo_product_shortcode___register_setting___group() {
        register_setting('woo_product_shortcodes_group', 'woo_product_shortcodes___api_key' );
    }
    add_action( 'admin_init', 'zfunction___woo_product_shortcode___register_setting___group' );
}


if ( !function_exists( 'zfunction___woo_product_shortcodes___settings' ) ) {
    function zfunction___woo_product_shortcodes___settings() {
        $database_api_key = esc_attr(get_option('woo_product_shortcodes___api_key'));
        $default_tab = 'dashboard';
        $tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;
        include('admin/page_header.php');
        include('admin/page_settings.php');
    }
}



if ( !function_exists( 'zfunction___woo_product_shortcodes___dashboard' ) ) {
    function zfunction___woo_product_shortcodes___dashboard() {
        // logic
        //Get the active tab from the $_GET param
        $default_tab = 'dashboard';
        $tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;
        include('admin/page_header.php');

        include('admin/page_dashboard.php');
    }
}



if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

    if ( !function_exists( 'zfunction___woo_product_shortcodes___shortcode_logic' ) ) {

      global $product;

      function zfunction___woo_product_shortcodes___shortcode_logic( $woo_product_shortcode___atts ) {
    		$woo_product_shortcode___atts = shortcode_atts( array(
        	'id'  =>  '',
        	'data_attribute'  =>  '',
      	), $woo_product_shortcode___atts );

        $woo_product_shortcode___atts___data_attribute = $woo_product_shortcode___atts['data_attribute'];
        if ( !empty( $woo_product_shortcode___atts['id'] ) ) {

          // get product info
          $var___get_post_type = get_post_type($woo_product_shortcode___atts['id']);
          if($var___get_post_type == 'product'){
            $var___wc_get_product = wc_get_product($woo_product_shortcode___atts['id']);
            $var___wc_get_product___price = $var___wc_get_product->get_price();
            $var___wc_get_product___sku = $var___wc_get_product->get_sku();
            $var___wc_get_product___name = $var___wc_get_product->get_name();
    		  	$var___wc_get_product___description = $var___wc_get_product->get_short_description();
    		  	$var___wc_get_product___reg_price = $var___wc_get_product->get_regular_price();
    		  	$var___wc_get_product___sale_price = $var___wc_get_product->get_sale_price();
            // PREMIUM_START
            // PREMIUM_END

            // data to be returned
            // FREE
            if ( $woo_product_shortcode___atts___data_attribute == 'price' ) {
              $var___output = $var___wc_get_product___price;
            }elseif ( $woo_product_shortcode___atts___data_attribute == 'name' ) {
              $var___output = $var___wc_get_product___name;
            }elseif ( $woo_product_shortcode___atts___data_attribute == 'short_description' ) {
              $var___output = $var___wc_get_product___description;
            } elseif ( $woo_product_shortcode___atts___data_attribute == 'sku' ) {
            $var___output = $var___wc_get_product___sku;
            }elseif ( $woo_product_shortcode___atts___data_attribute == 'regular_price' ) {
              $var___output = $var___wc_get_product___reg_price;
            }elseif ( $woo_product_shortcode___atts___data_attribute == 'sale_price' ) {
              $var___output = $var___wc_get_product___sale_price;
            // PREMIUM_START
            // PREMIUM_END

            } else {
              $var___output = 'ERROR101: Invalid Data Attribute sent';
            }
          } elseif(empty($var___get_post_type)) {
            $var___output = 'ERROR103: Invalid ID sent.';
          }else {
            $var___output = 'ERROR104: Invalid ID sent. Expecting a product but received ' . $var___get_post_type;
          }
        } else {
          $var___output = 'ERROR102: No Product ID sent';
        }
        return $var___output;
      }

    	add_shortcode( 'woo-product-shortcodes', 'zfunction___woo_product_shortcodes___shortcode_logic' );

    }
}

?>