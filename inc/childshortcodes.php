<?php
/**
 * Child Theme shortcodes
 *
 *	[ufl-image-card-left][/ufl-image-card-left]
 *	[ufl-image-card-right][/ufl-image-card-right]
 *	[ufl-text-card][/ufl-text-card]
 *	 
 * @package HWCOE_UFL_CHILD
 */
 

/**
 * Add Image Card Left
 * 
 * Example [ufl-image-card-left][/ufl-image-card-left]
 * @param  array $atts Shortcode attributes
 * @param  string [$content = ''] Content between shortcode tags
 * @return string Shortcode output
 */
function hwcoe_ufl_image_card_left($atts, $content = NULL ) {
	
	extract( shortcode_atts( 
		array(
			'title' => '',
			'image' => '',
			'alt' => '',
			'height' => '',
		), $atts )
	);
	 
	// Support either image ID or image url
	$image = ( is_numeric( $image ) )? wp_get_attachment_image( $image ) : array($image);
	
	// Shortcode callbacks must return content, so use output buffering
	ob_start();
	?>
		<div class="image-card-left" style="min-height:<?php echo esc_html( $height ); ?>;">
			<div class="col-md-5 image-card-left-img">
				<img src="<?php echo esc_url( $image[0] ); ?>" alt="<?php echo esc_html( $alt ); ?>" width="262px">
			</div>
			<div class="col-md-7 image-card-left-text">
				<?php if (!empty( $title )){ ?>
					<h2><?php echo esc_html( $title ); ?></h2>
				<?php } ?>
				<?php echo wpautop( wp_kses_post( $content ) ); ?>
			</div>
		</div>

	 <?php 
	return ob_get_clean();
}
add_shortcode('ufl-image-card-left', 'hwcoe_ufl_image_card_left');


/**
 * Add Image Card Right
 * 
 * Example [ufl-image-card-right][/ufl-image-card-right]
 * @param  array $atts Shortcode attributes
 * @param  string [$content = ''] Content between shortcode tags
 * @return string Shortcode output
 */
function hwcoe_ufl_image_card_right($atts, $content = NULL ) {
	
	extract( shortcode_atts( 
		array(
			'title' => '',
			'image' => '',
			'alt' => '',
			'height' => '',
		), $atts )
	);
	 
	// Support either image ID or image url
	$image = ( is_numeric( $image ) )? wp_get_attachment_image( $image ) : array($image);
	
	// Shortcode callbacks must return content, so use output buffering
	ob_start();
	?>
		<div class="image-card-right" style="min-height:<?php echo esc_html( $height ); ?>;">
			<div class="col-md-7 image-card-right-text">
				<?php if (!empty( $title )){ ?>
					<h2><?php echo esc_html( $title ); ?></h2>
				<?php } ?>
				<?php echo wpautop( wp_kses_post( $content ) ); ?>
			</div>
			<div class="col-md-5 image-card-right-img">
				<img src="<?php echo esc_url( $image[0] ); ?>" alt="<?php echo esc_html( $alt ); ?>" width="262px">
			</div>
		</div>

	 <?php 
	return ob_get_clean();
}
add_shortcode('ufl-image-card-right', 'hwcoe_ufl_image_card_right');

/**
 * Add Text Card
 * 
 * Example [ufl-text-card][/ufl-text-card]
 * @param  array $atts Shortcode attributes
 * @param  string [$content = ''] Content between shortcode tags
 * @return string Shortcode output
 */
function hwcoe_ufl_text_card($atts, $content = NULL ) {
	
	extract( shortcode_atts( 
		array(
			'title' => '',
			'height' => '',
			'background' => '',
		), $atts )
	);
	 
	// Shortcode callbacks must return content, so use output buffering
	ob_start();
	?>
		<div class="text-card" style="height:<?php echo esc_html( $height ); ?>; background:<?php echo esc_html( $background ); ?>;">
			<div class="col-md-12 text-card-text">
				<?php if (!empty( $title )){ ?>
					<h2><?php echo esc_html( $title ); ?></h2>
				<?php } ?>
				<?php echo wpautop( wp_kses_post( $content ) ); ?>
			</div>
		</div>

	 <?php 
	return ob_get_clean();
}
add_shortcode('ufl-text-card', 'hwcoe_ufl_text_card');