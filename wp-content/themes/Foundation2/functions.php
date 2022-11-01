<?php
/*
Author: Ole Fredrik Lie
URL: http://olefredrik.com
*/


// Various clean up functions
require_once('library/cleanup.php');

// Required for Foundation to work properly
require_once('library/foundation.php');

// Register all navigation menus
require_once('library/navigation.php');

// Add menu walker
require_once('library/menu-walker.php');

// Create widget areas in sidebar and footer
require_once('library/widget-areas.php');

// Return entry meta information for posts
require_once('library/entry-meta.php');

// Enqueue scripts
require_once('library/enqueue-scripts.php');

// Add theme support
require_once('library/theme-support.php');

//show attributes after summary in product single view
add_action('woocommerce_single_product_summary', function() {
	//template for this is in storefront-child/woocommerce/single-product/product-attributes.php
	global $product;
	echo $product->list_attributes();
	
}, 25);


    function wpa_98244_filter_short_description( $desc ){
    global $product;

    if ( is_single( $product->id ) )
        $desc .= '.';

    return $desc;
}

add_filter( 'woocommerce_short_description', 'wpa_98244_filter_short_description',10,1);



function wpa_whiteson_filter_short_description( $desc )
{
    global $product;
    if ( is_single( $product->id ) )
    {
    //$coulourvalues = get_the_terms( $product->id, 'pa_lens');
     //     foreach ( $coulourvalues as $coulourvalue ) {
    //$link = '<b>LENS:</b> <a href="http://www.welovewood.gr/wshop/?filter_lens='.$coulourvalue->name.'">'.$coulourvalue->name.'</a>';
    //       $desc .= $link.'<br />';
    //        }
    //$coulourvalues = get_the_terms( $product->id, 'pa_frame');
    //$desc .= '[related_products per_page="24"]';
    }
    //$desc = '';
    return $desc;
}
add_filter( 'woocommerce_short_description', 'wpa_whiteson_filter_short_description' );

add_filter( 'woocommerce_product_tabs', 'bbloomer_remove_product_tabs', 98 );
function bbloomer_remove_product_tabs( $tabs ) {
    unset( $tabs['additional_information'] ); 
    return $tabs;
}

function woo_related_products_limit() {
  global $product;
	
	$args['posts_per_page'] = 6;
	return $args;
}

add_filter( 'woocommerce_output_related_products_args', 'jk_related_products_args' );
function jk_related_products_args( $args ) {
	$args['posts_per_page'] = 10; // 4 related products
	$args['columns'] = 4; // arranged in 2 columns
	return $args;
}

add_action( 'woocommerce_archive_description', 'woocommerce_category_image', 2 );
function woocommerce_category_image() {
    if ( is_product_category() ){
	    global $wp_query;
	    $cat = $wp_query->get_queried_object();
	    $thumbnail_id = get_woocommerce_term_meta( $cat->term_id, 'thumbnail_id', true );
	    $image = wp_get_attachment_url( $thumbnail_id );
	    if ( $image ) {
		    echo '<img src="' . $image . '" alt="' . $cat->name . '" />';
		}
	}


}



?>
