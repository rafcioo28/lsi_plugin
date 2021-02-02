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
