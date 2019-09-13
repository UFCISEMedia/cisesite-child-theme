<?php
/*
Plugin Name: HWCOE Syllabi Display
Description: This plugin allows admin to display a dynamic table of entries using the Syllabus Upload custom_post_type. Use this shortcode to display the table: <strong>[syllabi-table]</strong>.
Requirements: Advanced Custom Fields with the Student Registration Modules field group; hwcoe-ufl-child theme with career fair modifications; Optional: Gravity Forms with 
Version: 1.0
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
      'has_archive' => true,
    )
  );
}
add_action( 'init', 'create_post_type' );

/*Add in custom columns in the admin panel*/
add_filter( 'manage_edit-hwcoe-syllabi_columns', 'hwcoe_syllabi_columns' ) ;

function hwcoe_syllabi_columns( $columns ) {

	$columns = array(
		'cb' => '&lt;input type="checkbox" />',
		'title' => __( 'Title' ),
		'instructor' => __( 'Instructor' ),
		'number' => __( 'Course Number' ),
		'semester' => __( 'Semester' ),
		'year' => __( 'Year' ),
		'syllabi' => __( 'Syllabi' ),
		'date' => __( 'Date' )		
	);

	return $columns;
}

add_action( 'manage_hwcoe-syllabi_posts_custom_column', 'manage_syllabi_columns', 10, 2 );

/*Pull in data for the custom columns*/
function manage_syllabi_columns( $column, $post_id ) {
	global $post;

	switch( $column ) {

		/* If displaying the 'instructor' column. */
		case 'instructor' :

			/* Get the post meta. */
			$instructor = get_post_meta( $post_id, 'su_instructor', true );

			/* Display the post meta. */
			printf( $instructor );

			break;

		/* If displaying the 'number' column. */
		case 'number' :

			/* Get the post meta. */
			$number = get_post_meta( $post_id, 'su_course_number', true );

			/* Display the post meta. */
			printf( $number );

			break;			

		/* If displaying the 'semester' column. */
		case 'semester' :

			/* Get the post meta. */
			$semester = get_post_meta( $post_id, 'su_semester', true );

			/* Display the post meta. */
			printf( $semester );

			break;	
			
		/* If displaying the 'year' column. */
		case 'year' :

			/* Get the post meta. */
			$year = get_post_meta( $post_id, 'su_year', true );

			/* Display the post meta. */
			printf( $year );

			break;
			
		/* If displaying the 'syllabi' column. */
		case 'syllabi' :

			/* Get the post meta. */
			$syllabi = get_post_meta( $post_id, 'su_syllabi_upload', true );

			/* Display the post meta. */
			printf( '<a href="' . $syllabi . '">Syllabus</a>');

			break;			

		/* Just break out of the switch statement for everything else. */
		default :
			break;
	}
}

/*Plugin shortcode*/
function syllabi_table_shortcode() {
	
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