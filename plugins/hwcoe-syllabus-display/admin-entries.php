<?php
/*
** HWCOE Syllabi admin panel customizations
**
*/


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

//Make columns sortable in the Admin Edit panel
add_filter( 'manage_edit-hwcoe-syllabi_sortable_columns', 'hwcoe_syllabi_sortable_columns' ) ;

function hwcoe_syllabi_sortable_columns( $columns ) {

	$columns['instructor'] = 'Instructor';
	$columns['semester'] = 'Semester';
	$columns['year'] = 'Year';

	return $columns;
}

// Only run our customization on the 'edit.php' page in the admin.
add_action( 'load-edit.php', 'my_edit_hwcoe_syllabi_load' );

function my_edit_hwcoe_syllabi_load() {
	add_filter( 'request', 'my_sort_hwcoe_syllabi' );
}

// Sorts the custom hwcoe-syllabi columns.
function my_sort_hwcoe_syllabi( $vars ) {

	/* Check if we're viewing the 'hwcoe-syllabi' post type. */
	if ( isset( $vars['post_type'] ) && 'hwcoe-syllabi' == $vars['post_type'] ) {

		/* Check if 'orderby' is set to 'instructor'. */
		if ( isset( $vars['orderby'] ) && 'Instructor' == $vars['orderby'] ) {

			/* Merge the query vars with our custom variables. */
			$vars = array_merge(
				$vars,
				array(
					'meta_key' => 'su_instructor',
					'orderby' => 'meta_value'
				)
			);
		}

		/* Check if 'orderby' is set to 'semester'. */
		if ( isset( $vars['orderby'] ) && 'Semester' == $vars['orderby'] ) {

			/* Merge the query vars with our custom variables. */
			$vars = array_merge(
				$vars,
				array(
					'meta_key' => 'su_semester',
					'orderby' => 'meta_value'
				)
			);
		}
		
		/* Check if 'orderby' is set to 'year'. */
		if ( isset( $vars['orderby'] ) && 'Year' == $vars['orderby'] ) {

			/* Merge the query vars with our custom variables. */
			$vars = array_merge(
				$vars,
				array(
					'meta_key' => 'su_year',
					'orderby' => 'meta_value_num'
				)
			);
		}
	}

	return $vars;
}

//Customize the search of admin panel edit page
add_filter( 'posts_join', 'hwcoe_syllabi_search' );
function hwcoe_syllabi_search ( $join ) {
    global $pagenow, $wpdb;

    // I want the filter only when performing a search on edit page of Custom Post Type named "hwcoe-syllabi".
    if ( is_admin() && 'edit.php' === $pagenow && 'hwcoe-syllabi' === $_GET['post_type'] && ! empty( $_GET['s'] ) ) {    
        $join .= 'LEFT JOIN ' . $wpdb->postmeta . ' ON ' . $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
    }
    return $join;
}

add_filter( 'posts_where', 'hwcoe_syllabi_search_where' );
function hwcoe_syllabi_search_where( $where ) {
    global $pagenow, $wpdb;

    // I want the filter only when performing a search on edit page of Custom Post Type named "hwcoe-syllabi".
    if ( is_admin() && 'edit.php' === $pagenow && 'hwcoe-syllabi' === $_GET['post_type'] && ! empty( $_GET['s'] ) ) {
        $where = preg_replace(
            "/\(\s*" . $wpdb->posts . ".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
            "(" . $wpdb->posts . ".post_title LIKE $1) OR (" . $wpdb->postmeta . ".meta_value LIKE $1)", $where );
    }
    return $where;
}

function hwcoe_syllabi_search_distinct( $where ){
    global $pagenow, $wpdb;

    if ( is_admin() && $pagenow=='edit.php' && $_GET['post_type']=='hwcoe-syllabi' && $_GET['s'] != '') {
    return "DISTINCT";

    }
    return $where;
}
add_filter( 'posts_distinct', 'hwcoe_syllabi_search_distinct' );
//Ends search of admin panel edit page