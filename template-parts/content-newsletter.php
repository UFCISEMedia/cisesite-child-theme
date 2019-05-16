<?php
/**
 * Template part for displaying newsletter content.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package HWCOE_UFL
 */
?>
<!-- content -->
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( is_archive() ): ?>
		
		<header class="entry-header">
			<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
		</header><!-- .entry-header -->
		
		<?php if ( has_post_thumbnail() ): ?>
			<div class="entry-thumbnail">
				<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'alignleft' ) ); ?>
			</div>
		<?php endif; ?>
		
	<?php else: ?>
		<?php if ( has_post_thumbnail() ): ?>
			<header class="entry-header">
				<?php echo hwcoe_ufl_post_featured_image(); ?>	
			</header>
		<?php endif; ?>
		
		<div class="entry-meta">
			<p><?php the_time('F j, Y'); ?> in <?php the_category(', '); ?></p>
		</div><!-- .entry-meta -->
	<?php endif; ?>
	
	<div class="entry-content">
		<?php
			if ( is_singular() ):
				the_content();
			else: 
		?>
			<div class="entry-meta">
				<p><?php the_time('F j, Y'); ?> in <?php the_category(', '); ?></p>
			</div><!-- .entry-meta -->
		<?php	the_excerpt();
			endif;
		?>
	</div><!-- .entry-content -->
	
	<div class="nl_featured_stories">
		<!--Featured Stories-->
		<?php if( have_rows( 'nl_featured_stories' ) ): ?>
			<h2>Featured Stories</h2>
			<?php while( have_rows( 'nl_featured_stories' ) ): the_row(); ?>
				<?php
					$imageArray  = get_sub_field( 'nl_pg_image' );
					$imageAlt    = esc_attr($imageArray['alt']);
					$image       = esc_url($imageArray['sizes']['large']);
				?>
				<div class="nl-featured-story">
					<img src="<?php echo $image; ?>" alt="<?php echo $imageAlt; ?>" class="nl-featured-st-img">
					<?php if ( get_sub_field( 'nl_pg_title') ): ?>
						<h3><a href="<?php esc_url( the_sub_field( 'nl_pg_title_url' ) ); ?>" target="_blank"><?php esc_attr( the_sub_field( 'nl_pg_title' ) ); ?></a></h3>
					<?php endif ?>
				</div>
			<?php endwhile // the_row ?>
		<?php endif // have_rows ?>
	</div>	<!--End Featured Stories-->
	
	<div><!--Stories-->
		<!--Department Stories-->
		<?php if( have_rows( 'nl_pg_department' ) ): ?>
			<h2>Department</h2>
			<?php while( have_rows( 'nl_pg_department' ) ): the_row(); ?>
				<div class="nl-department-story">
					<h3><?php esc_attr( the_sub_field( 'nl_pg_dept_title' ) ); ?></h3>
					<p><?php esc_attr( the_sub_field( 'nl_pg_dept_excerpt' ) ); ?>
					<a href="<?php esc_url( the_sub_field( 'nl_pg_dept_story_link' ) ); ?>" target="_blank">... Read more >></a></p>
				</div>
			<?php endwhile // the_row ?>
		<?php endif // have_rows ?>

		<!--In The News-->
		<?php if( have_rows( 'nl_pg_news' ) ): ?>
			<h2>In the News</h2>
			<?php while( have_rows( 'nl_pg_news' ) ): the_row(); ?>
				<div class="nl-news-story">
					<h3><?php esc_attr( the_sub_field( 'nl_pg_news_title' ) ); ?></h3>
					<p><?php esc_attr( the_sub_field( 'nl_pg_news_excerpt' ) ); ?>
					<a href="<?php esc_url( the_sub_field( 'nl_pg_news_story_link' ) ); ?>" target="_blank">... Read more >></a></p>
				</div>
			<?php endwhile // the_row ?>
		<?php endif // have_rows ?>

		 <!--Faculty Stories-->
		 <?php if( have_rows( 'nl_pg_faculty' ) ): ?>
			<h2>Faculty</h2>
			<?php while( have_rows( 'nl_pg_faculty' ) ): the_row(); ?>
				<?php
					$imageArray  = get_sub_field( 'nl_pg_fac_image' );
					$imageAlt    = esc_attr($imageArray['alt']);
					$image       = esc_url($imageArray['sizes']['large']);
				?>
				<div class="nl-faculty-story">
					<?php if(get_sub_field('nl_pg_fac_image')){ //if the field is not empty
					echo '<img src="' . $image . '" alt="' . $imageAlt . '">'; //display it
					} ?>
					<?php if(get_sub_field('nl_pg_fac_title')){ //if the field is not empty
					echo '<h3>' . get_sub_field('nl_pg_fac_title') . '</h3>'; //display it
					} ?>
					<?php if(get_sub_field('nl_pg_fac_excerpt')){ //if the field is not empty
					echo '<p>' . get_sub_field('nl_pg_fac_excerpt'); //display it
					} ?>
					<?php if(get_sub_field('nl_pg_fac_story_link')){ //if the field is not empty
					echo '<a href="' . get_sub_field('nl_pg_fac_story_link') . '" target="_blank">... Read more >></a></p>'; //display it
					} ?>		
				</div>
			<?php endwhile // the_row ?>
		<?php endif // have_rows ?>

		<!--Event Stories-->
		<?php if( have_rows( 'nl_pg_events' ) ): ?>
			<h2>Events</h2>
			<?php while( have_rows( 'nl_pg_events' ) ): the_row(); ?>
				<?php
					$imageArray  = get_sub_field( 'nl_pg_events_image' );
					$imageAlt    = esc_attr($imageArray['alt']);
					$image       = esc_url($imageArray['sizes']['large']);
				?>
				<div class="nl-events-story">
					<?php if(get_sub_field('nl_pg_events_image')){ //if the field is not empty
					echo '<img src="' . $image . '" alt="' . $imageAlt . '">'; //display it
					} ?>
					<?php if(get_sub_field('nl_pg_events_title')){ //if the field is not empty
					echo '<h3>' . get_sub_field('nl_pg_events_title') . '</h3>'; //display it
					} ?>
					<?php if(get_sub_field('nl_pg_events_excerpt')){ //if the field is not empty
					echo '<p>' . get_sub_field('nl_pg_events_excerpt'); //display it
					} ?>
					<?php if(get_sub_field('nl_pg_events_story_link')){ //if the field is not empty
					echo '<a href="' . get_sub_field('nl_pg_events_story_link') . '" target="_blank">... Read more >></a></p>'; //display it
					} ?>		
				</div>
			<?php endwhile // the_row ?>
		<?php endif // have_rows ?>

		<!--Student Stories-->
		<?php if( have_rows( 'nl_pg_students' ) ): ?>
			<h2>Students</h2>
			<?php while( have_rows( 'nl_pg_students' ) ): the_row(); ?>
				<?php
					$imageArray  = get_sub_field( 'nl_pg_stud_image' );
					$imageAlt    = esc_attr($imageArray['alt']);
					$image       = esc_url($imageArray['sizes']['large']);
				?>
				<div class="nl-students-story">
					<?php if(get_sub_field('nl_pg_stud_image')){ //if the field is not empty
					echo '<img src="' . $image . '" alt="' . $imageAlt . '">'; //display it
					} ?>
					<?php if(get_sub_field('nl_pg_stud_title')){ //if the field is not empty
					echo '<h3>' . get_sub_field('nl_pg_stud_title') . '</h3>'; //display it
					} ?>
					<?php if(get_sub_field('nl_pg_stud_excerpt')){ //if the field is not empty
					echo '<p>' . get_sub_field('nl_pg_stud_excerpt'); //display it
					} ?>
					<?php if(get_sub_field('nl_pg_stud_story_link')){ //if the field is not empty
					echo '<a href="' . get_sub_field('nl_pg_stud_story_link') . '" target="_blank">... Read more >></a></p>'; //display it
					} ?>	
				</div>
			<?php endwhile // the_row ?>
		<?php endif // have_rows ?>

		<!--Digital Newsletter Stories-->
		<?php if( have_rows( 'nl_pg_digital' ) ): ?>
			<?php while( have_rows( 'nl_pg_digital' ) ): the_row(); ?>
				<?php
					$imageArray  = get_sub_field( 'nl_pg_digital_image' );
					$imageAlt    = esc_attr($imageArray['alt']);
					$image       = esc_url($imageArray['sizes']['large']);
				?>
				<div class="nl-digital-story">
					<?php if(get_sub_field('nl_pg_digital_image')){ //if the field is not empty
					echo '<img src="' . $image . '" alt="' . $imageAlt . '">'; //display it
					} ?>
					<?php if(get_sub_field('nl_pg_digital_title')){ //if the field is not empty
					echo '<h3>' . get_sub_field('nl_pg_digital_title') . '</h3>'; //display it
					} ?>
					<?php if(get_sub_field('nl_pg_digital_excerpt')){ //if the field is not empty
					echo '<p>' . get_sub_field('nl_pg_digital_excerpt') . '</p>'; //display it
					} ?>
					<?php if(get_sub_field('nl_pg_digital_story_link')){ //if the field is not empty
					echo '<p><a href="' . get_sub_field('nl_pg_digital_story_link') . '" target="_blank">Read more >>></a></p>'; //display it
					} ?>	
				</div>
			<?php endwhile // the_row ?>
		<?php endif // have_rows ?>
	
	</div><!--End Stories-->
	
	
	<div class="entry-meta">
		<?php the_tags('<p class="tag">', ' ','</p>'); ?>
	</div>
	
	<footer class="entry-footer">
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'hwcoe-ufl' ),
				'after'  => '</div>',
			) );
			
			edit_post_link(
				sprintf(
					esc_html__( 'Edit %s', 'hwcoe-ufl' ),
					the_title( '<span class="sr-only">"', '"</span>', false )
				),
				'<span class="edit-link">',
				'</span>'
			);
		?>
	</footer><!-- .entry-footer -->
	
</article><!-- #post-## -->
