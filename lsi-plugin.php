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
require plugin_dir_path(__FILE__) . '/inc/disable-comments.php';
require plugin_dir_path(__FILE__) . '/inc/custom-post-types.php';
require plugin_dir_path(__FILE__) . '/inc/user-taxonomy.php';
require plugin_dir_path(__FILE__) . '/inc/files-taxonomy.php';
require plugin_dir_path(__FILE__) . '/inc/woo-premium-user-price.php';
require plugin_dir_path(__FILE__) . '/inc/view-functions.php';
require plugin_dir_path(__FILE__) . '/inc/group-filter-for-products.php';
require plugin_dir_path(__FILE__) . '/inc/query-modifications.php';
require plugin_dir_path(__FILE__) . '/inc/custom-tax-widget.php';
require plugin_dir_path(__FILE__) . '/inc/tax-for-price-disc.php';
require plugin_dir_path(__FILE__) . '/inc/woo-tweaks.php';
require plugin_dir_path(__FILE__) . '/inc/licence-functions.php';
require plugin_dir_path(__FILE__) . '/inc/extra-field-order.php';

//require get_theme_file_path('/inc/woo-tweaks.php');

add_action( 'wp', 'login_redirect' ); // Login redirect
add_action( 'init', 'lsi_post_types' ); // Init custom post types
add_action( 'init', 'customer_group' );	// User groups - Taxonomy
add_filter( 'parent_file', 'user_taxonomy_parent_file' ); // Menu fix for user taxonomy 
add_action( 'init', 'file_category' );	// Files category - Taxonomy
add_action( 'admin_menu', 'add_customer_group_admin_page' ); // Add User groups to the admin menu
add_filter( 'woocommerce_quantity_input_args', 'default_quantity', 10, 2 ); // set auto quantity to group of products
//add_action( 'woocommerce_product_options_pricing', 'premium_customer_price' );  // Add metafield premium price to product 
add_action( 'woocommerce_product_get_price','change_price_regular_member', 10, 2 );// Zmiana cen produktu w oparciu o logikę 
//add_action( 'woocommerce_process_product_meta', 'premium_price_field_save' ); // Save product Hook

//add_action( 'woocommerce_thankyou', 'custom_woocommerce_auto_complete_order' ); //Auto complete order 
add_action( 'woocommerce_after_order_notes', 'lsi_vat_field' ); // NIP Number
add_action( 'woocommerce_checkout_update_order_meta', 'lsi_checkout_vat_number_update_order_meta' );

add_action( 'woocommerce_admin_order_data_after_billing_address', 'lsi_vat_number_display_admin_order_meta', 10, 1 );

add_action( 'customize_register', 'set_taxonomy_price_off' ); // set taxonomy for prices off (customizer)

add_filter( 'acf/load_field/name=customer_group', 'acf_load_group_field_choices' ); // Field to pick a customer group on product pages

add_filter( 'the_content', 'add_products_to_post' ); // Add related products to post
add_filter( 'widget_posts_args', 'filter_recent_posts_widget' ); // recent posts filter

add_action( 'the_post', 'display_title_of_taxonomy' ); //Display taxonomy name on taxonomy archive page
add_action( 'pre_get_posts', 'single_file_tax' ); // get only one post on taxonomy archive page
add_action( 'the_content', 'file_taxonomy_pages'); // display table of files

add_action('woocommerce_after_order_notes', 'custom_checkout_field'); //checkout extra fields

// SHORTCODE
add_shortcode( 'manuals', 'manuals_table' ); // register shortcode for files download 
add_shortcode( 'display_licence', 'display_licence' ); // register shortcode for licence lists 


/**
*	View customization 
*/

add_action( 'woocommerce_single_product_summary', 'lsiDisplayFiles', 41 ); // Add files button to product
add_action( 'pre_get_posts', 'lsi_product_query_filter' ); // Add product filter by customer's group 

//filter
add_filter( 'woocommerce_after_single_product_summary', 'products_package', 10 );  // Display product packages 
add_action( 'woocommerce_after_single_product_summary', 'related_product_filter_lsi', 20 );


add_action( 'init', 'lsi_remove_hooks', 11 );
function lsi_remove_hooks(){ 
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

}


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





