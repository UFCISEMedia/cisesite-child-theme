<?php
/**
 * Change the default embed height to 16:9 dimensions
 * 
 * @since 0.1.0
 */

/**
 * Custom image sizes 
 *
 * @since 0.1.0
 */
function hwcoe_ufl_child_image_sizes(){
	// available for inserting in pages/posts
	add_image_size('nl_feature_img', 490, 339, array('center', 'center'));
	add_image_size('nl_images', 200, 200, array('center', 'top'));
	add_image_size('digital_nl_images', 264, 184, array('center', 'top'));

}
add_action( 'after_setup_theme', 'hwcoe_ufl_child_image_sizes' );

/**
 * Show additional sizes in the insert image dialog
 *
 * @param array $sizes	All defined image sizes
 * @since 0.2.5
 */
function hwcoe_ufl_child_show_custom_sizes( $sizes ) {
	 return array_merge( $sizes, array(
		'nl_feature_img' => __( 'Newsletter Feature Images', 'hwcoe-ufl-child' ),
		'nl_images' => __( 'Newsletter Images', 'hwcoe-ufl-child' ),
		'digital_nl_images' => __( 'Digital Newsletter Images', 'hwcoe-ufl-child' ),
	 ) );
}
add_filter( 'image_size_names_choose', 'hwcoe_ufl_child_show_custom_sizes' );

