<?php
/**
 * UF HWCOE Child theme functions and definitions.
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
	
	//Child Theme Styles
	wp_enqueue_style( 'hwcoe-ufl-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( $parent_style ),
		get_theme_version() 
	);

	wp_enqueue_script('hwcoe-ufl-child-scripts', 
		  get_stylesheet_directory_uri() . '/js/scripts.js', 
		  array(), 
		  get_theme_version(), 
	  true);	
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

/**
 * Load custom theme files for 
 * Custom Image Sizes 
 * Shortcodes
 */

require get_stylesheet_directory() . '/inc/media.php';

require get_stylesheet_directory() . '/inc/childshortcodes.php';


/* 
* Gravity Forms
*/

/*Customizes the other label in this specific form (13) in this specific field (5)*/
add_filter( 'gform_other_choice_value', 'set_placeholder', 10, 2 );
function set_placeholder( $placeholder, $field ) {
    if ( is_object( $field ) && 5 === $field->id && 13 === $field->formId ){ // 5 and 13 to your field id and form id number.
        $placeholder = 'Other (enter the country of your citizenship)';
    }
    return $placeholder;
}

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

//Removes parent theme function to remove p tags on acf wysiwyg
function child_remove_parent_function() {
    remove_action('acf/init', 'acf_wysiwyg_remove_wpautop');
}
add_action( 'after_setup_theme', 'child_remove_parent_function' );

/*For renaming Uploads in the Scholarship Applications Form*/
/**
 * Gravity Wiz // Gravity Forms // Rename Uploaded Files
 * http://gravitywiz.com/rename-uploaded-files-for-gravity-form/
 *
 * Rename uploaded files for Gravity Forms. You can create a static naming template or using merge tags to base names on user input.
 *
 * Features:
 *  + supports single and multi-file upload fields
 *  + flexible naming template with support for static and dynamic values via GF merge tags
 *
 * Uses:
 *  + add a prefix or suffix to file uploads
 *  + include identifying submitted data in the file name like the user's first and last name
 *
 * @version   2.5.3
 * @author    David Smith <david@gravitywiz.com>
 * @license   GPL-2.0+
 * @link      http://gravitywiz.com/rename-uploaded-files-for-gravity-form/
 */
class Rename_Uploaded_Scholarship {

	public function __construct( $args = array() ) {

		// set our default arguments, parse against the provided arguments, and store for use throughout the class
		$this->_args = wp_parse_args( $args, array(
			'form_id'          => false,
			'field_id'         => false,
			'template'         => '',
			'ignore_extension' => false,
		) );

		// do version check in the init to make sure if GF is going to be loaded, it is already loaded
		add_action( 'init', array( $this, 'init' ) );

	}

	public function init() {

		// make sure we're running the required minimum version of Gravity Forms
		if ( ! is_callable( array( 'GFFormsModel', 'get_physical_file_path' ) ) ) {
			return;
		}

		add_filter( 'gform_entry_post_save', array( $this, 'rename_uploaded_files' ), 9, 2 );
		add_filter( 'gform_entry_post_save', array( $this, 'stash_uploaded_files' ), 99, 2 );

		add_action( 'gform_after_update_entry', array( $this, 'rename_uploaded_files_after_update' ), 9, 2 );
		add_action( 'gform_after_update_entry', array( $this, 'stash_uploaded_files_after_update' ), 99, 2 );

	}

	function rename_uploaded_files( $entry, $form ) {

		if ( ! $this->is_applicable_form( $form ) ) {
			return $entry;
		}

		foreach ( $form['fields'] as &$field ) {

			if ( ! $this->is_applicable_field( $field ) ) {
				continue;
			}

			$uploaded_files = rgar( $entry, $field->id );

			if ( empty( $uploaded_files ) ) {
				continue;
			}

			$uploaded_files = $this->parse_files( $uploaded_files, $field );
			$stashed_files  = $this->parse_files( gform_get_meta( $entry['id'], 'gprf_stashed_files' ), $field );
			$renamed_files  = array();

			foreach ( $uploaded_files as $_file ) {

				// Don't rename the same files twice.
				if ( in_array( $_file, $stashed_files ) ) {
					$renamed_files[] = $_file;
					continue;
				}

				$dir  = wp_upload_dir();
				$dir  = $this->get_upload_dir( $form['id'] );
				$file = str_replace( $dir['url'], $dir['path'], $_file );

				if ( ! file_exists( $file ) ) {
					continue;
				}

				$renamed_file = $this->rename_file( $file, $entry );

				if ( ! is_dir( dirname( $renamed_file ) ) ) {
					wp_mkdir_p( dirname( $renamed_file ) );
				}

				$result = rename( $file, $renamed_file );

				$renamed_files[] = $this->get_url_by_path( $renamed_file, $form['id'] );

			}

			// In cases where 3rd party add-ons offload the image to a remote location, no images can be renamed.
			if ( empty( $renamed_files ) ) {
				continue;
			}

			if ( $field->get_input_type() == 'post_image' ) {
				$value = str_replace( $uploaded_files[0], $renamed_files[0], rgar( $entry, $field->id ) );
			} elseif ( $field->multipleFiles ) {
				$value = json_encode( $renamed_files );
			} else {
				$value = $renamed_files[0];
			}

			GFAPI::update_entry_field( $entry['id'], $field->id, $value );

			$entry[ $field->id ] = $value;

		}

		return $entry;
	}

	function get_upload_dir( $form_id ) {
		$dir         = GFFormsModel::get_file_upload_path( $form_id, 'PLACEHOLDER' );
		$dir['path'] = dirname( $dir['path'] );
		$dir['url']  = dirname( $dir['url'] );
		return $dir;
	}

	function rename_uploaded_files_after_update( $form, $entry_id ) {
		$entry = GFAPI::get_entry( $entry_id );
		$this->rename_uploaded_files( $entry, $form );
	}

	/**
	 * Stash the "final" version of the files after other add-ons have had a chance to interact with them.
	 *
	 * @param $entry
	 * @param $form
	 */
	function stash_uploaded_files( $entry, $form ) {

		foreach ( $form['fields'] as &$field ) {

			if ( ! $this->is_applicable_field( $field ) ) {
				continue;
			}

			$uploaded_files         = rgar( $entry, $field->id );
			$existing_stashed_files = gform_get_meta( $entry['id'], 'gprf_stashed_files' );

			if ( $this->is_json( $uploaded_files ) ) {
				$uploaded_files = json_decode( $uploaded_files, ARRAY_A );
			}

			if ( $this->is_json( $existing_stashed_files ) ) {
				$existing_stashed_files = json_decode( $existing_stashed_files, ARRAY_A );
			}

			/* Convert single files to array of files. */
			if ( ! is_array( $existing_stashed_files ) ) {
				$existing_stashed_files = $existing_stashed_files ? array( $existing_stashed_files ) : array();
			}

			if ( ! is_array( $uploaded_files ) ) {
				$uploaded_files = $uploaded_files ? array( $uploaded_files ) : array();
			}

			if ( ! empty( $existing_stashed_files ) ) {
				$uploaded_files = array_merge( $existing_stashed_files, $uploaded_files );
			}

			gform_update_meta( $entry['id'], 'gprf_stashed_files', json_encode( $uploaded_files ) );

		}

		return $entry;
	}

	/**
	 * Check whether a string is JSON or not.
	 *
	 * @param $string string String to test.
	 *
	 * @return bool Whether the string is JSON.
	 */
	function is_json( $string ) {
		if ( method_exists( 'GFCommon', 'is_json' ) ) {
			return GFCommon::is_json( $string );
		}

		// Duplicate contents of GFCommon::is_json() here to supports versions of GF older than GF 2.5.
		// phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
		if ( is_string( $string ) && in_array( substr( $string, 0, 1 ), array( '{', '[' ) ) && is_array( json_decode( $string, ARRAY_A ) ) ) {
			return true;
		}

		return false;
	}

	function stash_uploaded_files_after_update( $form, $entry_id ) {
		$entry = GFAPI::get_entry( $entry_id );
		$this->stash_uploaded_files( $entry, $form );
	}

	function rename_file( $file, $entry ) {

		$new_file = $this->get_template_value( $this->_args['template'], $file, $entry );
		$new_file = $this->increment_file( $new_file );

		return $new_file;
	}

	function increment_file( $file ) {

		$file_path = GFFormsModel::get_physical_file_path( $file );
		$pathinfo  = pathinfo( $file_path );
		$counter   = 1;

		if ( $this->_args['ignore_extension'] ) {
			while ( glob( str_replace( ".{$pathinfo['extension']}", '.*', $file_path ) ) ) {
				$file_path = str_replace( ".{$pathinfo['extension']}", "{$counter}.{$pathinfo['extension']}", GFFormsModel::get_physical_file_path( $file ) );
				$counter ++;
			}
		} else {
			// increment the filename if it already exists (i.e. balloons.jpg, balloons1.jpg, balloons2.jpg)
			while ( file_exists( $file_path ) ) {
				$file_path = str_replace( ".{$pathinfo['extension']}", "{$counter}.{$pathinfo['extension']}", GFFormsModel::get_physical_file_path( $file ) );
				$counter ++;
			}
		}

		$file = str_replace( basename( $file ), basename( $file_path ), $file );

		return $file;
	}

	function is_path( $filename ) {
		return strpos( $filename, '/' ) !== false;
	}

	function get_template_value( $template, $file, $entry ) {

		$info = pathinfo( $file );

		if ( strpos( $template, '/' ) === 0 ) {
			$dir      = wp_upload_dir();
			$template = $dir['basedir'] . $template;
		} else {
			$template = $info['dirname'] . '/' . $template;
		}

		// replace our custom "{filename}" psuedo-merge-tag
		$value = str_replace( '{filename}', $info['filename'], $template );

		// replace merge tags
		$form  = GFAPI::get_form( $entry['form_id'] );
		$value = GFCommon::replace_variables( $value, $form, $entry, false, true, false, 'text' );

		// make sure filename is "clean"
		$filename = $this->clean( basename( $value ) );
		$value    = str_replace( basename( $value ), $filename, $value );

		// append our file ext
		$value .= '.' . $info['extension'];

		return $value;
	}

	function is_applicable_form( $form ) {

		$form_id = isset( $form['id'] ) ? $form['id'] : $form;

		return $form_id == $this->_args['form_id'];
	}

	function is_applicable_field( $field ) {

		$is_file_upload_field   = in_array( GFFormsModel::get_input_type( $field ), array( 'fileupload', 'post_image' ) );
		$is_applicable_field_id = $this->_args['field_id'] ? $field['id'] == $this->_args['field_id'] : true;

		return $is_file_upload_field && $is_applicable_field_id;
	}

	function clean( $str ) {
		return sanitize_file_name( $str );
	}

	function get_url_by_path( $file, $form_id ) {

		$dir = $this->get_upload_dir( $form_id );
		$url = str_replace( $dir['path'], $dir['url'], $file );

		return $url;
	}

	function parse_files( $files, $field ) {

		if ( empty( $files ) ) {
			return array();
		}

		if ( $this->is_json( $files ) ) {
			$files = json_decode( $files );
		} elseif ( $field->get_input_type() === 'post_image' ) {
			$file_bits = explode( '|:|', $files );
			$files     = array( $file_bits[0] );
		} else {
			$files = array( $files );
		}

		return $files;
	}

}

# Configuration

new Rename_Uploaded_Scholarship( array(
	'form_id'          => 13,
	'field_id'         => 30,
	// most merge tags are supported, original file extension is preserved
	'template'         => 'G-{Name (First):2.3}-{Name (Last):2.6}',
	// Ignore extension when renaming files and keep them in sequence (e.g. a.jpg, a1.png, a2.pdf etc.)
	'ignore_extension' => false,
) );

new Rename_Uploaded_Scholarship( array(
	'form_id'          => 13,
	'field_id'         => 31,
	// most merge tags are supported, original file extension is preserved
	'template'         => 'UG-{Name (First):2.3}-{Name (Last):2.6}',
	// Ignore extension when renaming files and keep them in sequence (e.g. a.jpg, a1.png, a2.pdf etc.)
	'ignore_extension' => false,
) );