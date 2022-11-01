<?php

// Add custom menu bootstrap class for specific menu 
function main_menu_nav_class( $classes, $item, $args ) {

  //var_dump($args);
	if ( 'menu-main' === $args->theme_location || 'mega-menu' === $args->theme_location ) {
		$classes[] = "nav-item";
    }
    // else{
    //   $classes[] = "nav-item";
    // }

	return $classes;
}
add_filter( 'nav_menu_css_class' , 'main_menu_nav_class' , 10, 4 );


//Add menu link class
function add_specific_menu_location_atts( $atts, $item, $args ) {
  // check if the item is in the primary menu
  if( 'menu-main' === $args->theme_location || 'mega-menu' === $args->theme_location ) {
    // add the desired attributes:
    $atts['class'] = 'nav-link';
  }
  return $atts;
}
add_filter( 'nav_menu_link_attributes', 'add_specific_menu_location_atts', 10, 3 );