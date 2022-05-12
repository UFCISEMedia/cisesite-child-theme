<div class="accordion_block">
	<?php if ( get_sub_field( 'accordion_title') ): ?>
		<?php esc_attr( the_sub_field( 'accordion_title' ) ); ?>
	<?php endif ?>
	<?php if( have_rows( 'accordion_content' ) ): ?>
		<?php while( have_rows( 'accordion_content' ) ): the_row(); ?>
			<div class="accordion_style_content">
				<button class="accordion_style"><?php esc_attr( the_sub_field( 'title' ) ); ?></button>
					<div class="ac_panel">
						<p><?php esc_attr( the_sub_field( 'content' ) ); ?></p>	
					</div>
			</div>
		<?php endwhile // the_row ?>
	<?php endif // have_rows ?>
</div>