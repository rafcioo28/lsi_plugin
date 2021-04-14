<?php
/**
 *  Remove menu items in Woocommerce admin dashboard 
 */
function remove_woo_menu_items() {
    $remove = array( 'wc-settings', 'wc-status', 'wc-addons');
    foreach ( $remove as $submenu_slug ) {
        if ( ! current_user_can( 'update_core' ) ) {
            remove_submenu_page( 'woocommerce', $submenu_slug );
        }
    }
}


/**
 * Auto Complete all WooCommerce orders.
 */
function custom_woocommerce_auto_complete_order( $order_id ) { 
    if ( ! $order_id ) {
        return;
    }

    $order = wc_get_order( $order_id );
    $order->update_status( 'completed' );
}


/**
 * @param object $query The main query object.
 */
function query_customer_group_filter($query) {
    global $pagenow;

    // disable on media library popup
    if(in_array( $pagenow, array( 'upload.php', 'admin-ajax.php' ))) {
        return;
    }

    if (!current_user_can( 'show_all_products' )) { // show_all_products cap for no customers users
        if(!is_admin() AND is_post_type_archive('product') OR (!is_admin() AND $query->get('post_type') == 'product')) {
            $user_groups = get_the_terms(get_current_user_id(), 'customer_group');
            $meta_query = array(
                'relation'  =>  'OR',
                array(
                    'key'     => 'customer_group',
                    'value'   => ' ',
                    'compare' => '='
                ),
            );
            if($user_groups) {
                foreach($user_groups as $term) {
                    array_push($meta_query, array(
                        'key'       =>  'customer_group',
                        'value'     =>  '"' .$term->slug . '"',
                        'compare'   =>  'LIKE',
                    ));
                }
            }
            $query->set('meta_key', 'customer_group');
            $query->set('meta_query', $meta_query);
        }
    }
}


/**
* Pole NIP w zamówieniu
*/
function lsi_vat_field( $checkout ) {

    echo '<div id="wpdesk_vat_field"><h2>' . __('Dane do Faktury') . '</h2>';
    
    woocommerce_form_field( 'vat_number', array(
        'type'          => 'text',
        'class'         => array( 'vat-number-field form-row-wide') ,
        'label'         => __( 'NIP' ),
        'placeholder'   => __( 'Wpisz NIP, aby otrzymać fakturę' ),
    ), $checkout->get_value( 'vat_number' ));
    
    echo '</div>';

}


/**
* Save NIP Number in the order meta
*/
function lsi_checkout_vat_number_update_order_meta( $order_id ) {
    if ( ! empty( $_POST['vat_number'] ) ) {
        update_post_meta( $order_id, '_vat_number', sanitize_text_field( $_POST['vat_number'] ) );
    }
}


/**
 * Wyświetlenie pola NIP
 */
function lis_vat_number_display_admin_order_meta( $order ) {
    echo '<p><strong>' . __( 'NIP', 'woocommerce' ) . ':</strong> ' . get_post_meta( $order->id, '_vat_number', true ) . '</p>';
}