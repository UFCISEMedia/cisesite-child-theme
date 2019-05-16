<?php
/**
 * Template Name: Newsletter Single Page
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
	<header class="entry-header nl_header">
		<?php the_title( '<h1 class="page-title">', '</h1>' ); ?>
		<?php 
			if(get_field('nl_pg_issue')){ //if the field is not empty
				echo '<h3>Issue: ' . get_field('nl_pg_issue') . '</h3>'; //display it
			} 
		?>
	</header>
	<!-- .entry-header --> 
  </div>
</div>
<div class="row">
	<div class="col-sm-12 newsletter_details">
	  <div class="row">
		<div class="col-md-12">
			<?php 
				while ( have_posts() ) : the_post();
					get_template_part( 'template-parts/content-newsletter', get_post_type() );
				endwhile; // End of the loop.
			?>
		</div>
	  </div>
  </div>
</div>
</div>

<?php get_footer(); ?>
