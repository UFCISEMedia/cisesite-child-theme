<div class="flexible-box-content">
	<?php if ( get_sub_field( 'flex_box_title') ): ?>
		<?php esc_attr( the_sub_field( 'flex_box_title' ) ); ?>
	<?php endif ?>
	<?php if ( get_sub_field( 'flex_box_blurb') ): ?>
		<?php esc_attr( the_sub_field( 'flex_box_blurb' ) ); ?>
	<?php endif ?>
	<div class="flex-box-container">
		<?php if( have_rows( 'flex_box_content' ) ): ?>
			<?php while( have_rows( 'flex_box_content' ) ): the_row(); ?>
				<div class="flex-item">
					<p><?php esc_attr( the_sub_field( 'content' ) ); ?></p>	
				</div>
			<?php endwhile // the_row ?>
		<?php endif // have_rows ?>
	</div>
</div>