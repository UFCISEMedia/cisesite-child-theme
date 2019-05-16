<?php
/**
 * UF HWCOE Child theme example functions and definitions.
*
*/

function hwcoe_ufl_child_scripts() {
	
	//enqueue parent stylesheet
	$parent_style = 'hwcoe-ufl-style'; 

	wp_enqueue_style( $parent_style, 
		get_template_directory_uri() . '/style.css', 
		['bootstrap', 'prettyPhoto'],
		wp_get_theme('hwcoe-ufl')->get('Version')
	);
	
	//DataTables Styles
	wp_enqueue_style( 'datatables', get_stylesheet_directory_uri() . '/css/datatables.min.css' );
	
	//Child Theme Styles
	wp_enqueue_style( 'hwcoe-ufl-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( $parent_style ),
		get_theme_version() 
	);

    wp_enqueue_script('hwcoe-ufl-child-scripts', get_stylesheet_directory_uri() . '/scripts.js', array(), get_theme_version(), true);

	//DataTables Scripts	
	wp_enqueue_script( 'datatables', get_stylesheet_directory_uri() . '/js/datatables.min.js', null, null, true );
	
}
add_action( 'wp_enqueue_scripts', 'hwcoe_ufl_child_scripts' );

// Custom Function to Include
if ( !function_exists( 'hwcoe_ufl_child_icon_url' ) ) {

	function hwcoe_ufl_child_icon_url() {
		if ( empty($url) ){
			$url = get_stylesheet_directory_uri() . '/favicon.png';
		}
		return $url;
	}
	add_filter( 'get_site_icon_url', 'hwcoe_ufl_child_icon_url' );
}

/*
 * Theme variable definitions
 */
define( "HWCOE_UFL_CHILD_INC_DIR", get_stylesheet_directory() . "/inc/modules" );

/*
* Visual Editor Styles
*/

function my_mce_buttons_2($buttons) {
    array_unshift($buttons, 'styleselect');
    return $buttons;
}
add_filter('mce_buttons_2', 'my_mce_buttons_2');

// Callback function to filter the MCE settings
function my_mce_before_init_insert_formats( $init_array ) {  
	// Define the style_formats array
	$style_formats = array(  
		// Each array child is a format with it's own settings
		array(  
			'title' => 'Inline Div',  
			'block' => 'div',  
			'classes' => 'cise-inline-div',
			'wrapper' => true,
			
		),  
	);  
	// Insert the array, JSON ENCODED, into 'style_formats'
	$init_array['style_formats'] = json_encode( $style_formats );  
	
	return $init_array;  
  
} 
// Attach callback to 'tiny_mce_before_init' 
add_filter( 'tiny_mce_before_init', 'my_mce_before_init_insert_formats' );  
