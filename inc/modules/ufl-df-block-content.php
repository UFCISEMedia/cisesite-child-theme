<?php if( have_rows( 'shaded_block_content' ) ): ?>
	<?php while( have_rows( 'shaded_block_content' ) ): the_row(); ?>
		<div class="shaded_block_text">
			<h3><?php if ( get_sub_field( 'lab_title') ): ?>
					<a href="<?php esc_url( the_sub_field( 'lab_title_link' ) ); ?>" target="_blank"><?php esc_attr( the_sub_field( 'lab_title' ) ); ?></a>
				<?php endif ?>
			</h3>
				<p><?php esc_attr( the_sub_field( 'centers-lab-content' ) ); ?></p>
				<p><?php esc_attr( the_sub_field( 'lab_director' ) ); ?></p>
		</div>
	<?php endwhile // the_row ?>
<?php endif // have_rows ?>