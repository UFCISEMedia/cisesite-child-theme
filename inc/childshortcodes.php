<?php
/**
 * Child Theme shortcodes
 *
 *	[ufl-contact-card][/ufl-contact-card]
 *	 
 * @package HWCOE_UFL_CHILD
 */
 

/**
 * Add Contact Card
 * 
 * Example [ufl-contact-card][/ufl-contact-card]
 * @param  array $atts Shortcode attributes
 * @param  string [$content = ''] Content between shortcode tags
 * @return string Shortcode output
 */
function hwcoe_ufl_contact_card($atts, $content = NULL ) {
	
	extract( shortcode_atts( 
		array(
			'name' => '',
			'image' => '',
		), $atts )
	);
	 
	// Support either image ID or image url
	$image = ( is_numeric( $image ) )? wp_get_attachment_image( $image ) : array($image);
	
	// Shortcode callbacks must return content, so use output buffering
	ob_start();
	?>
		<div class="contact-card">
			<div class="col-md-5 contact-card-img">
				<img src="<?php echo esc_url( $image[0] ); ?>" alt="" width="262px">
			</div>
			<div class="col-md-7 contact-card-text">
					  <?php if (!empty( $name )){ ?>
						<h2><?php echo esc_html( $name ); ?></h2>
				<?php } ?>
				<?php echo wpautop( wp_kses_post( $content ) ); ?>
			</div>
		</div>

	 <?php 
	return ob_get_clean();
}
add_shortcode('ufl-contact-card', 'hwcoe_ufl_contact_card');
