<?php
/**
 * The template file for archives.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package HWCOE_UFL
 */
get_header(); ?>

<div id="main" class="container main-content">
<div class="row">
  <div class="col-sm-12">
    <header class="entry-header">
      <?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
    </header>
    <!-- .entry-header --> 
  </div>
</div>
<div class="row">
  <div class="col-md-9">
    <?php 
	  global $wp_query;
	  $researchareaID = get_cat_ID( 'research-areas-pg' );
	  $facultyID = get_cat_ID( 'faculty-pg' );
	  $speakerID = get_cat_ID( 'speaker' );
	  $args = array_merge( $wp_query->query, array( 'category__not_in' => array($facultyID, $speakerID, $researchareaID) ) ); //displays all categories except the faculty-pg, speaker and research-areas-pg categories
	  query_posts( $args );

		while ( have_posts() ) : the_post();
			get_template_part( 'template-parts/content', get_post_type() );
		endwhile; // End of the loop. 
		
		// Previous/next page navigation.
		the_posts_pagination();
	?>
  </div>
  <div class="col-md-3">
    <div id="post-sidebar" class="widget-area" role="complementary">
      <?php the_widget( 'WP_Widget_Archives', array('title' => __('News Archive', 'hwcoe-ufl'), 'dropdown' => 1) ); ?>
   </div><!-- post_sidebar -->
  </div>
</div>
</div>

<?php get_footer(); ?>
