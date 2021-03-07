<?php
// Default quantity for package of products
add_filter( 'woocommerce_quantity_input_args', 'default_quantity', 10, 2 );

function default_quantity( $args, $product ) {
    $args['input_value'] = 1;
    return $args;
}


// Get parent package id
if ( ! function_exists( 'wc_get_parent_grouped_id' ) ) {
    function wc_get_parent_grouped_id( $id ){   
        global $wpdb;
    
        $cdata = wp_cache_get( __FUNCTION__, 'woocommerce' );
        if ( ! is_array($cdata) )
            $cdata = array();
        if ( ! isset($cdata[$id]) ) {
            $cdata[$id] = $parent_id = $children = false;
            $qdata = $wpdb->get_row("SELECT post_id, meta_value
                                     FROM $wpdb->postmeta
                                     WHERE meta_key = '_children' 
                                     AND meta_value LIKE '%$id%'");
            if ( is_object($qdata) ) {    
                $parent_id = $qdata->post_id;
                $children = $qdata->meta_value;
                if ( is_string($children) )
                    $children = unserialize($children);
                if ( is_array($children) && count($children) > 0 )
                    foreach ($children as $child_id)
                        $cdata[$child_id] = $parent_id;
            }
            wp_cache_set( __FUNCTION__, apply_filters( __FUNCTION__ . '_filter', $cdata, $id, $parent_id, $children, $qdata ), 'woocommerce' );
        }
        //return $cdata[$id];
        return $cdata[$id];
    }
}



function products_package($test) {
    global $product;
    $group_id = wc_get_parent_grouped_id( $product->get_id());
    if ($group_id) { ?>
        <h2>Produkt w zestawie</h2>
        
        <?php
        $product_group    = wc_get_product( $group_id );
        $children   = $product_group->get_children();
        $product_shortcode = '[products ids="' . implode(',', $children) . '" columns="6"]';
        
        echo do_shortcode($product_shortcode);
    }
}

add_filter( 'woocommerce_after_single_product_summary', 'products_package', 10);  
