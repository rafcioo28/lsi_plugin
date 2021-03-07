<?php

/**
 * Add a premium price to the product price tab
 */
function premium_customer_price() {

    echo '<div class="options_group">';
    
    woocommerce_wp_text_input(
        array(
            'id'                => '_premium_price',
            'label'             => __( 'Premium pice', 'lis' ),
            'class'             => 'short wc_input_price',
            'placeholder'       => '',
            'desc_tip'    		=> true,
            'description'       => __( "Premium price for a premium customer", 'lsi' ),
            'type'              => 'text',
        )
    );
    ?>
 	<?php

     echo '</div>';

}

/**
 * Save our simple product fields
 * @param int $product_id The ID of the product .
 */
function premium_price_field_save( $product_id ){

    $woocommerce_text_field = $_POST['_premium_price'];
    update_post_meta( $product_id, '_premium_price', esc_attr( $woocommerce_text_field ) );
}


function change_price_regular_member($price, $product){
    
    $taxonomy_off = get_theme_mod('taxonomy_price_off');
    $parent_taxonomy = get_term_by( 'name', $taxonomy_off, get_query_var( 'product_cat' ) );
    // If product category is software then get field value of discount
    if( has_term($parent_taxonomy, 'product_cat', $product->get_id() ) ) {
        $user_id = get_current_user_id();
        $discount_level = get_field('discount_level', 'user_'. $user_id, false);
        $discount_percent = get_field('discount', $discount_level[0]);
        $price = $price * (1 - ($discount_percent/100));

    } else {
        //$roles = wp_get_current_user()->roles;
        //$premium_price = get_post_meta($product->get_id(), '_premium_price', true );
        //if(in_array('premium_customer', $roles) AND $premium_price) {
          //  $price = $premium_price;
        //}
    }
    return $price;
}
