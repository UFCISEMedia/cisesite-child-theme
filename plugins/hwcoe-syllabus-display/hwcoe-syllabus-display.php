<?php
/*
Plugin Name: HWCOE Syllabi Display
Description: This plugin allows admin to display a dynamic table of entries using the Syllabus Upload custom_post_type. Use this shortcode to display the table: <strong>[syllabi_table]</strong>.
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

function syllabi_table_shortcode() {
	
	//Query
	$the_query = new WP_Query(array( 'post_type' => 'hwcoe-syllabi', 'posts_per_page' => 100 ));
	
	//Table
	$output = '<table id="syllabi-table">
				<thead>
					<tr>
						<th>Title</th>
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

add_shortcode('syllabi_table', 'syllabi_table_shortcode'); 