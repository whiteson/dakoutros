<?php

// ACF functions here

 
// add_filter('acf/settings/save_json', 'my_acf_json_save_point');
function my_acf_json_save_point( $path ) {
    // update path
    $path = get_stylesheet_directory() . '/acf-json';
    // return
    return $path;
    
}
 


if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page();
	
}

