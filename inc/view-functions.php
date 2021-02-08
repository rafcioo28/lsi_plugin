<?php

// Redirect not logged in users
if ( ! function_exists( 'default_quantity' ) ) {
    function login_redirect() {  
        global $pagenow;
        if(!is_user_logged_in() && $pagenow != 'wp-login.php')
            auth_redirect();
    }
}

// Default quantity for package of products
if ( ! function_exists( 'default_quantity' ) ) {
    function default_quantity( $args, $product ) {
        $args['input_value'] = 1;
        return $args;
    }
}

// Display field files on product page when available 
if ( ! function_exists( 'lsiDisplayFiles' ) ) {
    function lsiDisplayFiles() {
    ?>
    <div>
    <?php
            $relatedFiles = new WP_Query(array( 
            'posts_per_page'        =>  -1,
            'post_type'             =>  'file',
            'orderby'               =>  'title',
            'order'                 => 'ASC',
            'meta_query'            =>  array(
                array(
                    'key'       =>  'related_product',
                    'compare'   =>  'LIKE',
                    'value'     =>  '"' . get_the_ID() . '"',
                ),
            ),
        ));

        if ($relatedFiles->have_posts()) {
            while($relatedFiles->have_posts()) {
                $relatedFiles->the_post();
                $manual = get_field('file');?>
                <a href="<?php echo $manual['url']; ?>" class="button">Pobierz instrukcję</a>
            
            <?php }
        }
        wp_reset_postdata();

        ?>

    </div>

    <?php
    }
}

/** 
*   Group of related products in group
*/
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

// Display product packages in group
function products_package($test) {
    global $product;
    $group_id = wc_get_parent_grouped_id( $product->get_id());
    if ($group_id) { ?>
        <h2>Produkt w zestawie</h2>
        
        <?php
        $product_group    = wc_get_product( $group_id );
        $children   = $product_group->get_children();
        $product_shortcode = '[products ids="' . implode(',', $children) . '" columns="4"]';
        
        echo do_shortcode($product_shortcode);
    }
}

// Add related products to post
if ( ! function_exists( 'add_products_to_post' ) ) {
    function add_products_to_post( $content ){

    	$add_content = '';
    		$relatedProducts = get_field('product');
    		if ($relatedProducts) {
    			foreach($relatedProducts as $product) {
    				$thumbnail = get_the_post_thumbnail_url($product, 'thumbnail');
    				$add_content .= '<div class="" style="width: 15rem; display: inline-block;">';
    				$add_content .= '<a href="' . get_the_permalink($product) . '" class="button">';
                    $add_content .= '<img class="card-img-top" src="' . $thumbnail . '" alt="Card image cap">';
                    $add_content .= '<div class="card-body">';
                    $add_content .= '<h5 class="card-title">' . get_the_title($product) . '</h5>';
                    $add_content .= '<div class="text-end"></div></div></a></div>';
    			}
    		}
    	$content .= $add_content;	
    	return $content;
    }
}


function manuals_table() { 
	
	$manuals = get_posts(array('post_type' => 'file'));

	$content = '<table>';

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
	 

	$content .= '</table>';

	return $content;
} 
