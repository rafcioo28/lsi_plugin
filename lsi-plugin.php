<?php
/**
 * Plugin Name: LSI
 * Version 1.0
 * Author: Rafał Herszkowicz
 * Text Domain: LSI
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require plugin_dir_path(__FILE__) . '/inc/login-form.php';
require plugin_dir_path(__FILE__) . '/inc/custom-post-types.php';
require plugin_dir_path(__FILE__) . '/inc/user-taxonomy.php';
require plugin_dir_path(__FILE__) . '/inc/files-taxonomy.php';
require plugin_dir_path(__FILE__) . '/inc/woo-premium-user-price.php';
require plugin_dir_path(__FILE__) . '/inc/view-functions.php';
require plugin_dir_path(__FILE__) . '/inc/group-filter-for-products.php';
require plugin_dir_path(__FILE__) . '/inc/query-modifications.php';
require plugin_dir_path(__FILE__) . '/inc/custom-tax-widget.php';

//require get_theme_file_path('/inc/woo-tweaks.php');
//require get_theme_file_path('/inc/package-products-tweaks.php');

add_action( 'wp', 'login_redirect' ); // Login redirect
add_action( 'init', 'lsi_post_types' ); // Init custom post types
add_action( 'init', 'customer_group' );	// User groups - Taxonomy
add_action( 'init', 'file_category' );	// Files category - Taxonomy
add_action( 'admin_menu', 'add_customer_group_admin_page' ); // Add User groups to the admin menu
add_filter( 'woocommerce_quantity_input_args', 'default_quantity', 10, 2 ); // set auto quantity to group of products
add_action( 'woocommerce_product_options_pricing', 'premium_customer_price' );  // Add metafield premium price to product 
add_action( 'woocommerce_process_product_meta', 'premium_price_field_save' ); // Save product Hook

add_filter( 'acf/load_field/name=customer_group', 'acf_load_group_field_choices' ); // Field to pick a customer group on product pages

add_filter( 'the_content', 'add_products_to_post' ); // Add related products to post
add_filter('widget_posts_args', 'filter_recent_posts_widget'); // recent posts filter


// SHORTCODE
add_shortcode('manuals', 'manuals_table'); // register shortcode for files download 


/**
*	View customization 
*/

add_action('woocommerce_single_product_summary', 'lsiDisplayFiles', 41); // Add files button to product
add_action('pre_get_posts', 'lsi_product_query_filter'); // Add product filter by customer's group 

//filter
add_filter( 'woocommerce_after_single_product_summary', 'products_package', 10);  // Display product packages 


/**
 * Display customer group selector in profile page
 */
 add_action( 'show_user_profile', 'edit_user_customer_group_section' );
 add_action( 'edit_user_profile', 'edit_user_customer_group_section' );
 add_action( 'user_new_form', 'edit_user_customer_group_section' );
 /*************** */

 /**
 * Save selected customer groups
 */
// add_action( 'personal_options_update', 'save_user_customer_group_terms' ); // Not used on a profile page
add_action( 'edit_user_profile_update', 'save_user_customer_group_terms' );
add_action( 'user_register', 'save_user_customer_group_terms' );


//tests



function single_file_tax($query) {
	if (!is_admin() AND is_tax('file_category') AND $query->is_main_query()) {
		$query->set('posts_per_page', 1);
	}
}

function my_the_post_action( $post ) {
	global $wp_query;

	if (is_tax('file_category')) {
		$tax = $wp_query->get_queried_object();

		$post->post_title = $tax->name;
	}
}



function custom_wpquery( $content ){
	
	if (is_tax('file_category')) {

		$manuals = get_posts(array('post_type' => 'file'));

		$content .= '<div class="entry-content"><table>';

	foreach($manuals as $manual) {
		$field = get_field('file', $manual->ID);
		$content .= '<tr>';
		$content .= '<td>';
		$content .= $manual->post_title;
		$content .= '</td>';
		$content .= '<td>';
		$content .= '<a href="' . $field['url'] . '">Pobierz</a>';
		$content .= '</td>';
		$content .= '</tr>';
	}
	 

		$content .= '</table></div>';


		echo $content;
	}	else {
	return $content;
	}

};

add_action( 'the_post', 'my_the_post_action' );
add_action( 'pre_get_posts', 'single_file_tax' ); 
add_action( 'the_content', 'custom_wpquery', 10);


