<?php
function custom_checkout_field( $checkout ) {
	/* Set $cat_in_cart to false */
	$cat_in_cart = false;

	$term_id  = get_term_by( 'slug', get_theme_mod( 'taxonomy_price_off' ), 'product_cat' )->term_id;
	$children = get_term_children( $term_id, 'product_cat' );
	array_push( $children, $term_id );

	/* Loop through all products in the Cart */
	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

		/* If Cart has category, set $cat_in_cart to true */
		if ( has_term( $children, 'product_cat', $cart_item['product_id'] ) ) {
			$cat_in_cart = true;
			break;
		}
	}
	/* Do something if category "download" is in the Cart */
	if ( $cat_in_cart ) {

		echo '<div id="licence_fields">';
		echo '<h2>' . esc_html_e( 'Dane do licencji', 'lsi' ) . '</h2>';
		echo '<h3>' . esc_html_e( 'Dane kliena', 'lsi' ) . '</h3>';

		woocommerce_form_field(
			'customer_name',
			array(
				'type'        => 'text',
				'class'       => array( 'form-row-wide' ),
				'label'       => __( 'Nazwa', 'lsi' ),
				'placeholder' => __( 'Nazwa klienta', 'lsi' ),
				'required'    => true,
			),
			$checkout->get_value( 'customer_name' )
		);

		woocommerce_form_field(
			'customer_NIP',
			array(
				'type'        => 'text',
				'class'       => array( 'form-row-wide' ),
				'label'       => __( 'NIP', 'lsi' ),
				'placeholder' => __( 'NIP klienta', 'lsi' ),
				'required'    => true,
			),
			$checkout->get_value( 'customer_NIP' )
		);

		woocommerce_form_field(
			'customer_email',
			array(
				'type'        => 'email',
				'class'       => array( 'form-row-wide' ),
				'label'       => __( 'e-mail', 'lsi' ),
				'placeholder' => __( 'e-mail klienta', 'lsi' ),
			),
			$checkout->get_value( 'customer_email' )
		);

		woocommerce_form_field(
			'customer_street',
			array(
				'type'        => 'text',
				'class'       => array( 'form-row-wide' ),
				'label'       => __( 'Ulica', 'lsi' ),
				'placeholder' => __( 'Ulica', 'lsi' ),
				'required'    => true,
			),
			$checkout->get_value( 'customer_street' )
		);

		woocommerce_form_field(
			'customer_postal_code',
			array(
				'type'        => 'text',
				'class'       => array( 'form-row-wide' ),
				'label'       => __( 'Kod pocztowy', 'lsi' ),
				'placeholder' => __( '99-999', 'lsi' ),
				'required'    => true,
			),
			$checkout->get_value( 'customer_postal_code' )
		);

		woocommerce_form_field(
			'customer_city',
			array(
				'type'     => 'text',
				'class'    => array( 'form-row-wide' ),
				'label'    => __( 'Miasto', 'lsi' ),
				'required' => true,
			),
			$checkout->get_value( 'customer_city' )
		);

		echo '<h3>' . esc_html_e( 'Dane kliena', 'lsi' ) . '</h3>';

		woocommerce_form_field(
			'local_name',
			array(
				'type'        => 'text',
				'class'       => array( 'form-row-wide' ),
				'label'       => __( 'Nazwa', 'lsi' ),
				'placeholder' => __( 'Nazwa lokalu', 'lsi' ),
				'required'    => true,
			),
			$checkout->get_value( 'locale_name' )
		);

		woocommerce_form_field(
			'local_NIP',
			array(
				'type'     => 'text',
				'class'    => array( 'form-row-wide' ),
				'label'    => __( 'NIP lokal', 'lsi' ),
				'required' => true,
			),
			$checkout->get_value( 'locale_name' )
		);

		woocommerce_form_field(
			'local_email',
			array(
				'type'     => 'email',
				'class'    => array( 'form-row-wide' ),
				'label'    => __( 'e-mail - lokal', 'lsi' ),
				'required' => true,
			),
			$checkout->get_value( 'locale_name' )
		);

		woocommerce_form_field(
			'local_street',
			array(
				'type'        => 'text',
				'class'       => array( 'form-row-wide' ),
				'label'       => __( 'Ulica', 'lsi' ),
				'placeholder' => __( 'Ulica', 'lsi' ),
				'required'    => true,
			),
			$checkout->get_value( 'local_street' )
		);

		woocommerce_form_field(
			'local_postal_code',
			array(
				'type'        => 'text',
				'class'       => array( 'form-row-wide' ),
				'label'       => __( 'Kod pocztowy', 'lsi' ),
				'placeholder' => __( '99-999', 'lsi' ),
				'required'    => true,
			),
			$checkout->get_value( 'local_postal_code' )
		);

		woocommerce_form_field(
			'local_city',
			array(
				'type'     => 'text',
				'class'    => array( 'form-row-wide' ),
				'label'    => __( 'Miasto', 'lsi' ),
				'required' => true,
			),
			$checkout->get_value( 'local_city' )
		);
		echo '</div>';
	}
}

/**
* Checkout Process
*/

// add_action('woocommerce_checkout_process', 'customised_checkout_field_process');
// function customised_checkout_field_process()
// {
//	if (!$_POST['my_extra_field']) wc_add_notice(__('Please enter value!') , 'error');
//}

/**
* Update the value given in custom field
*/

add_action('woocommerce_checkout_update_order_meta', 'custom_checkout_field_update_order_meta');

function custom_checkout_field_update_order_meta($order_id)

{
	if (!empty($_POST['customer_name'])) {
		update_post_meta($order_id, 'customer_name', sanitize_text_field($_POST['customer_name']));
	}
	if (!empty($_POST['customer_NIP'])) {
		update_post_meta($order_id, 'customer_NIP', sanitize_text_field($_POST['customer_NIP']));
	}
	if (!empty($_POST['customer_email'])) {
		update_post_meta($order_id, 'customer_email', sanitize_text_field($_POST['customer_email']));
	}
	if (!empty($_POST['customer_street'])) {
		update_post_meta($order_id, 'customer_street', sanitize_text_field($_POST['customer_street']));
	}
	if (!empty($_POST['customer_postal_code'])) {
		update_post_meta($order_id, 'customer_postal_code',sanitize_text_field($_POST['customer_postal_code']));
	}
	if (!empty($_POST['customer_city'])) {
		update_post_meta($order_id, 'customer_city',sanitize_text_field($_POST['customer_city']));
	}
	if (!empty($_POST['local_name'])) {
		update_post_meta($order_id, 'local_name', sanitize_text_field($_POST['local_name']));
	}
	if (!empty($_POST['local_NIP'])) {
		update_post_meta($order_id, 'local_NIP', sanitize_text_field($_POST['local_NIP']));
	}
	if (!empty($_POST['local_email'])) {
		update_post_meta($order_id, 'local_email', sanitize_text_field($_POST['local_email']));
	}
	if (!empty($_POST['local_street'])) {
		update_post_meta($order_id, 'local_street', sanitize_text_field($_POST['local_street']));
	}
	if (!empty($_POST['local_postal_code'])) {
		update_post_meta($order_id, 'local_postal_code', sanitize_text_field($_POST['local_postal_code']));
	}
	if (!empty($_POST['local_city'])) {
		update_post_meta($order_id, 'local_city', sanitize_text_field($_POST['local_city']));
	}
}

/**
 * Display field value on the order edit page
 */
add_action( 'woocommerce_admin_order_data_after_billing_address', 'my_custom_checkout_field_display_admin_order_meta', 10, 1 );

function my_custom_checkout_field_display_admin_order_meta($order){
	echo '<h3>' . esc_html_e( 'Dane kliena', 'lsi' ) . '</h3>';
	echo '<p><strong>'.__('Nazwa: ').':</strong> ' . get_post_meta( $order->id, 'customer_name', true ) . '</p>';
	echo '<p><strong>'.__('NIP: ').':</strong> ' . get_post_meta( $order->id, 'customer_NIP', true ) . '</p>';
	echo '<p><strong>'.__('e-mail: ').':</strong> ' . get_post_meta( $order->id, 'customer_email', true ) . '</p>';
	echo '<p><strong>'.__('Ulica: ').':</strong> ' . get_post_meta( $order->id, 'customer_street', true ) . '</p>';
	echo '<p><strong>'.__('Kod pocztowy: ').':</strong> ' . get_post_meta( $order->id, 'customer_postal_code', true ) . '</p>';
	echo '<p><strong>'.__('Miasto: ').':</strong> ' . get_post_meta( $order->id, 'customer_city', true ) . '</p>';

	echo '<h3>' . esc_html_e( 'Dane lokalu', 'lsi' ) . '</h3>';
	echo '<p><strong>'.__('Nazwa: ').':</strong> ' . get_post_meta( $order->id, 'local_name', true ) . '</p>';
	echo '<p><strong>'.__('NIP: ').':</strong> ' . get_post_meta( $order->id, 'local_NIP', true ) . '</p>';
	echo '<p><strong>'.__('e-mail: ').':</strong> ' . get_post_meta( $order->id, 'local_email', true ) . '</p>';
	echo '<p><strong>'.__('Ulica: ').':</strong> ' . get_post_meta( $order->id, 'local_street', true ) . '</p>';
	echo '<p><strong>'.__('Kod pocztowy: ').':</strong> ' . get_post_meta( $order->id, 'local_postal_code', true ) . '</p>';
	echo '<p><strong>'.__('Miasto: ').':</strong> ' . get_post_meta( $order->id, 'local_city', true ) . '</p>';
}