<div class="df-image-cards-container">
	<?php if( have_rows( 'cards_image_callout' ) ): ?>
		<?php while( have_rows( 'cards_image_callout' ) ): the_row(); ?>
			<?php 
				$image    = get_sub_field( 'default_image_for_cards' );
				$alt      = $image['alt'];
				$img_src  = $image['sizes']['large'];
			?>
				<div class="img-callout df-img-cards">
					<img src="<?php echo $img_src; ?>" alt="<?php echo $alt; ?>" class="img-full">
					<h2><?php esc_attr( the_sub_field( 'cards_headline' ) ); ?></h2>
					<?php if ( get_sub_field( 'cards_link_text') ): ?>
						<a href="<?php esc_url( the_sub_field( 'cards_link_url' ) ); ?>" class="read-more"><?php esc_attr( the_sub_field( 'cards_link_text' ) ); ?></a>
					<?php endif ?>
				</div>
		<?php endwhile // the_row ?>
	<?php endif // have_rows ?>
</div>