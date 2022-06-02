<?php
/**
 * Template part for displaying page content in page.php.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package HWCOE_UFL
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    
    <div class="entry-content">
        <?php the_content(); ?>
		<?php if( have_rows('default_page_modules') ): ?>
			<?php while ( have_rows('default_page_modules') ) : the_row(); ?>
				<?php
				  /*
				   * General Content- No Formatting 
				   */
				  ?>
				<?php if( get_row_layout() == 'default_general_content' ): ?>
					<?php include( HWCOE_UFL_CHILD_INC_DIR . '/ufl-df-content.php' ); ?>
				<?php endif // default_general_content ?>
				<?php
				/*
				   * Image Cards
				   * Three image cards
				   */
				  ?>
				<?php if( get_row_layout() == 'default_image_cards' ): ?>
					<?php include( HWCOE_UFL_CHILD_INC_DIR . '/ufl-df-image-cards.php' ); ?>
				<?php endif // default_image_cards ?>
				<?php
				/*
				   * Shaded Block Text
				   * Blocks of content
				   */
				  ?>
				<?php if( get_row_layout() == 'shaded_block_text' ): ?>
					<?php include( HWCOE_UFL_CHILD_INC_DIR . '/ufl-df-block-content.php' ); ?>
				<?php endif // shaded_block_text ?>
				<?php
				/*
				   * Accordion Style Content
				   * Content placed into an accordion
				   */
				  ?>
				<?php if( get_row_layout() == 'accordion_style_content' ): ?>
					<?php include( HWCOE_UFL_CHILD_INC_DIR . '/ufl-df-accordion.php' ); ?>
				<?php endif // hwcoe_accordion_content ?>
				<?php
				/*
				   * Flex Box Content
				   * Content placed into a flexible div
				   */
				  ?>
				<?php if( get_row_layout() == 'flex_box_content' ): ?>
					<?php include( HWCOE_UFL_CHILD_INC_DIR . '/ufl-df-flexbox.php' ); ?>
				<?php endif // flex_box_content ?>		
 			<?php endwhile // the_row ?>
		<?php endif // have_rows ?>
	</div><!-- .entry-content -->
    
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