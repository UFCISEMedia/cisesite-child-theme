<?php
/**
 * Template Name: Wide Single Page
 * Template Post Type: post
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
		<?php hwcoe_ufl_entry_title(); ?>
	</header>
	<!-- .entry-header --> 
  </div>
</div>
<div class="row">
  <div class="col-md-12">
	<?php 
		while ( have_posts() ) : the_post();
			get_template_part( 'template-parts/content', get_post_type() );
		endwhile; // End of the loop.
		
		the_post_navigation( array(
			'next_text' 	=> '<span class="meta-nav" aria-hidden="true">' . __( 'Next', 'hwcoe-ufl' ) . '</span> ' .
				'<span class="screen-reader-text">' . __( 'Next post:', 'hwcoe-ufl' ) . '</span> ' .
				'<span class="post-title">%title</span>',
			'prev_text' 	=> '<span class="meta-nav" aria-hidden="true">' . __( 'Previous', 'hwcoe-ufl' ) . '</span> ' .
				'<span class="screen-reader-text">' . __( 'Previous post:', 'hwcoe-ufl' ) . '</span> ' .
				'<span class="post-title">%title</span>',
			'in_same_term' 	=> true,
			'taxonomy'  	=> 'category',
			'exclude_terms' =>  array(67),
		) );
	?>
  </div> 
</div>
</div>

<?php get_footer(); ?>
