<?php
/*
Plugin Name: HWCOE Syllabi Display
Description: This plugin allows admin to display a dynamic table of entries using the Syllabus Upload custom_post_type. Use this shortcode to display the table: <strong>[syllabi-table]</strong>.
Requirements: Advanced Custom Fields with the Student Registration Modules field group; hwcoe-ufl-child theme with career fair modifications; Gravity Forms with the Syllabi Uploads form and Gravity Forms + Custom Post Types plugin. 
Version: 1.4
Author: Allison Logan
Author URI: http://allisoncandreva.com/
*/

function create_post_type() {
  register_post_type( 'hwcoe-syllabi',
    array(
      'labels' => array(
        'name' => __( 'Syllabi Form Entries' ), //Top of page when in post type
        'singular_name' => __( 'Entry' ), //per post
		'menu_name' => __('Course Syllabi'), //Shows up on side menu
		'all_items' => __('All Entries'), //On side menu as name of all items
      ),
      'public' => true,
	  'menu_position' => 4,
	  'menu_icon' => 'dashicons-text-page',
      'has_archive' => true,
    )
  );
}
add_action( 'init', 'create_post_type' );

/* Enqueue assets */
add_action( 'wp_enqueue_scripts', 'hwcoe_syllabi_assets' );
function hwcoe_syllabi_assets() {
    wp_register_style( 'hwcoe-syllabi-datatables', plugins_url( '/css/datatables.min.css' , __FILE__ ) );
    wp_register_style( 'hwcoe-syllabi', plugins_url( '/css/hwcoesyllabi.css' , __FILE__ ) );

    wp_register_script( 'hwcoe-syllabi-datatables', plugins_url( '/js/datatables.min.js' , __FILE__ ), array( 'jquery' ), null, true );
    wp_register_script( 'hwcoe-syllabi', plugins_url( '/js/hwcoesyllabi.js' , __FILE__ ), array( 'jquery' ), null, true );
}

if( is_admin() ){
    include( 'admin-entries.php' );
}

/*Convert Name field to Title Case*/
$theformID = RGFormsModel::get_form_id('Syllabi Upload');
//$thefieldID = RGFormsModel::get_field($theformID, 'name_first');

add_action('gform_pre_submission', 'titlecase_fields');
function titlecase_fields($form){
	// add all the field IDs you want to titlecase, to this array
	$form  = GFAPI::get_form( $theformID );
	$fields_to_titlecase = array(
						'input_8_3',
						'input_8_6');
	foreach ($fields_to_titlecase as $each) {
			// for each field, convert the submitted value to lowercase and then title case and assign back to the POST variable
			// the rgpost function strips slashes
			$lowercase = strtolower(rgpost($each));
			$_POST[$each] = ucwords($lowercase);
		} 
	// return the form, even though we did not modify it
	return $form;
}//end field titlecaseing

add_action('gform_pre_submission', 'upperecase_fields');
function upperecase_fields($form){
	// add all the field IDs you want to uppercase, to this array
	$form  = GFAPI::get_form( $theformID );
	$fields_to_uppercase = array(
						'input_14');
	foreach ($fields_to_uppercase as $each) {
			// for each field, convert the submitted value to uppercase and assign back to the POST variable
			// the rgpost function strips slashes
			$_POST[$each] = strtoupper(rgpost($each));
		} 
	// return the form, even though we did not modify it
	return $form;
}//end field uppercasing

/**
 * Gravity Wiz // Gravity Forms // Rename Uploaded Files
 *
 * Rename uploaded files for Gravity Forms. 
 *
 * @version   2.3
 * @author    David Smith <david@gravitywiz.com>
 * @license   GPL-2.0+
 * @link      http://gravitywiz.com/rename-uploaded-files-for-gravity-form/
 */
class GW_Rename_Uploaded_Files {

	public function __construct( $args = array() ) {

		// set our default arguments, parse against the provided arguments, and store for use throughout the class
		$this->_args = wp_parse_args( $args, array(
			'form_id'  => false,
			'field_id' => false,
			'template' => ''
		) );

		// do version check in the init to make sure if GF is going to be loaded, it is already loaded
		add_action( 'init', array( $this, 'init' ) );

	}

	public function init() {

		// make sure we're running the required minimum version of Gravity Forms
		if( ! is_callable( array( 'GFFormsModel', 'get_physical_file_path' ) ) ) {
			return;
		}

		add_filter( 'gform_entry_post_save', array( $this, 'rename_uploaded_files' ), 9, 2 );
		add_filter( 'gform_entry_post_save', array( $this, 'stash_uploaded_files' ), 99, 2 );

		add_action( 'gform_after_update_entry', array( $this, 'rename_uploaded_files_after_update' ), 9, 2 );
		add_action( 'gform_after_update_entry', array( $this, 'stash_uploaded_files_after_update' ), 99, 2 );

	}

	function rename_uploaded_files( $entry, $form ) {

		if( ! $this->is_applicable_form( $form ) ) {
			return $entry;
		}

		foreach( $form['fields'] as &$field ) {

			if( ! $this->is_applicable_field( $field ) ) {
				continue;
			}

			$uploaded_files = rgar( $entry, $field->id );

			if( empty( $uploaded_files ) ) {
				continue;
			}

			$uploaded_files = $this->parse_files( $uploaded_files, $field );
			$stashed_files  = $this->parse_files( gform_get_meta( $entry['id'], 'gprf_stashed_files' ), $field );
			$renamed_files  = array();

			foreach( $uploaded_files as $_file ) {

				// Don't rename the same files twice.
				if( in_array( $_file, $stashed_files ) ) {
					$renamed_files[] = $_file;
					continue;
				}

				$dir  = wp_upload_dir();
				$dir  = $this->get_upload_dir( $form['id'] );
				$file = str_replace( $dir['url'], $dir['path'], $_file );

				if( ! file_exists( $file ) ) {
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
			if( empty( $renamed_files ) ) {
				continue;
			}

			if( $field->get_input_type() == 'post_image' ) {
				$value = str_replace( $uploaded_files[0], $renamed_files[0], rgar( $entry, $field->id ) );
			} else if( $field->multipleFiles ) {
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
		$dir = GFFormsModel::get_file_upload_path( $form_id, 'PLACEHOLDER' );
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

			$uploaded_files = rgar( $entry, $field->id );
			gform_update_meta( $entry['id'], 'gprf_stashed_files', $uploaded_files );

		}

		return $entry;
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

		// increment the filename if it already exists (i.e. balloons.jpg, balloons1.jpg, balloons2.jpg)
		while ( file_exists( $file_path ) ) {
			$file_path = str_replace( ".{$pathinfo['extension']}", "{$counter}.{$pathinfo['extension']}", GFFormsModel::get_physical_file_path( $file ) );
			$counter++;
		}

		$file = str_replace( basename( $file ), basename( $file_path ), $file );

		return $file;
	}

	function is_path( $filename ) {
		return strpos( $filename, '/' ) !== false;
	}

	function get_template_value( $template, $file, $entry ) {

		$info = pathinfo( $file );

		if( strpos( $template, '/' ) === 0 ) {
			$dir      = wp_upload_dir();
			$template = $dir['basedir'] . $template;
		} else {
			$template = $info['dirname'] . '/' . $template;
		}
		
		// removes the original file name - Added by Allison Logan
		$newname = str_replace($info['filename'], "", $info['filename']);
		
		// replace our custom "{filename}" psuedo-merge-tag
		$value = str_replace( '{filename}', $newname, $template );

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

	function remove_slashes( $value ) {
		return stripslashes( str_replace( '/', '', $value ) );
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
		return $this->remove_slashes( sanitize_title_with_dashes( strtr(
			utf8_decode( $str ),
			utf8_decode( 'ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ'),
			'SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy'
		), 'save' ) );
	}

	function get_url_by_path( $file, $form_id ) {

		$dir = $this->get_upload_dir( $form_id );
		$url = str_replace( $dir['path'], $dir['url'], $file );

		return $url;
	}

	function parse_files( $files, $field ) {

		if( empty( $files ) ) {
			return array();
		}

		if( $field->get_input_type() == 'post_image' ) {
			$file_bits = explode( '|:|', $files );
			$files = array( $file_bits[0] );
		} else if( $field->multipleFiles ) {
			$files = json_decode( $files );
		} else {
			$files = array( $files );
		}

		return $files;
	}

}

# Configuration

new GW_Rename_Uploaded_Files( array(
	'form_id' => $theformID,
	'field_id' => 18,
	'template' => '{Course Number:14}_{Semester:16}{Year:17}-{filename}'
) ); //end file renaming 

/*Plugin shortcode*/
function syllabi_table_shortcode() {

	// Assets 
	wp_enqueue_style( 'hwcoe-syllabi-datatables' );
    wp_enqueue_style( 'hwcoe-syllabi' );
    wp_enqueue_script( 'hwcoe-syllabi-datatables' );
    wp_enqueue_script( 'hwcoe-syllabi' );
	
	//Query
	$the_query = new WP_Query(array( 'post_type' => 'hwcoe-syllabi', 'posts_per_page' => 100 ));
	
	//Table
	$output = '<table id="syllabi-table">
				<thead>
					<tr>
						<th>Title (click to open)</th>
						<th>Course Number</th>
						<th>Section(s)</th>
						<th>Instructor</th>
						<th>Semester</th>
						<th>Year</th>
					</tr>
				</thead>
				<tbody>';
	
	while ( $the_query->have_posts() ) : $the_query->the_post();
			$output .= '<tr>
							<td><a href="' .get_field( 'su_syllabi_upload' ). '" target="_blank">' .get_field( 'su_course_title' ). '</a></td>
							<td>' .get_field( 'su_course_number' ). '</td>';
				if(get_field( 'su_course_sections' )):  //if the field is not empty
					$output .= '<td>' .get_field( 'su_course_sections' ). '</td>'; //display it
					else: 
					$output .= '<td>n/a</td>';
					endif; 		
				$output .= '<td>' .get_field( 'su_instructor' ). '</td>
							<td>' .get_field( 'su_semester' ). '</td>
							<td>' .get_field( 'su_year' ). '</td>';
			$output .= '</tr>';
	endwhile;
	wp_reset_query();
	
	$output .= '</tbody>
				</table>';
	
	//Return code
	return $output;
}

add_shortcode('syllabi-table', 'syllabi_table_shortcode'); 
