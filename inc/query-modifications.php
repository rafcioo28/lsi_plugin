<?php

/// pre query test 

if ( ! function_exists( 'lsi_product_query_filter' ) ) {
    function lsi_product_query_filter($query) {
        if (!current_user_can( 'show_all_products' )) {

    	    if (!is_admin() AND (is_post_type_archive('product') OR is_archive()) AND $query->is_main_query()) {
    	    	// List of products with filter by customers groups
    	    	//$user_groups = get_the_terms(get_current_user_id(), 'customer_group');
    	    	$user_groups = wp_get_object_terms(get_current_user_id(), 'customer_group', array('fields' => 'all_with_object_id'));  // Get user group detail

    	    	// Create meta query
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
                $query->set('meta_key', 'groups_list');
    	        $query->set('meta_query', $meta_query);
            
			}


			//Query filter on homepage
			if (!is_admin() AND $query->is_main_query() AND $query->is_home() ) {
    	    	// List of products with filter by customers groups
    	    	//$user_groups = get_the_terms(get_current_user_id(), 'customer_group');
    	    	$user_groups = wp_get_object_terms(get_current_user_id(), 'customer_group', array('fields' => 'all_with_object_id'));  // Get user group detail

    	    	// Create meta query
    	    	$meta_query = array(
    	    		'relation'  =>  'OR',
    	    		array(
    	    			'key'     => 'groups_list',
    	    			'value'   => ' ',
    	    			'compare' => '='
    	    		),
    	    	);	

    	    	if($user_groups) {
    	    		foreach($user_groups as $term) {
    	    			array_push($meta_query, array(
    	    				'key'       =>  'groups_list',
    	    				'value'     =>  '"' .$term->term_id . '"',
    	    				'compare'   =>  'LIKE',
    	    			));
    	    		}
    	    	}
                $query->set('meta_key', 'groups_list');
    	        $query->set('meta_query', $meta_query);
            
            }
        }
    }
}

// filter query on related product block
if (! function_exists( 'related_product_filter_lsi' )) {

	function related_product_filter_lsi() {
		global $product;
		$terms = get_the_terms($product->ID, 'product_cat');
		$user_groups = wp_get_object_terms(get_current_user_id(), 'customer_group', array('fields' => 'all_with_object_id'));  // Get user group detail
    	// Create meta query
		

		$query = array(
			'post_type'      => 'product',
			'orderby'   => 'rand',
			'posts_per_page' => 5,
			'product_cat'    => $terms[0]->slug,
		);


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

		$query['meta_key'] = 'customer_group';
	    $query['meta_query'] = $meta_query;


		$related_products = get_posts($query);
		$related_after_filter = array();
		foreach($related_products as $prod) {
			$related_after_filter[] = $prod->ID;
		}

        $product_shortcode = '[products ids="' . implode(',', $related_after_filter) . '" columns="5"]';
        
		echo '<h2>Powiązane produkty</h2><br>';

        echo do_shortcode($product_shortcode);

	}
}



if ( ! function_exists( 'filter_recent_posts_widget' ) ) {
	function filter_recent_posts_widget($query) {

		$user_groups = wp_get_object_terms(get_current_user_id(), 'customer_group', array('fields' => 'all_with_object_id'));  // Get user group detail

	    	    	// Create meta query

					$meta_query = array(
	    	    		'relation'  =>  'OR',
	    	    		array(
	    	    			'key'     => 'groups_list',
	    	    			'value'   => ' ',
	    	    			'compare' => '='
	    	    		),
	    	    	);	

	    	    	if($user_groups) {
	    	    		foreach($user_groups as $term) {
	    	    			array_push($meta_query, array(
	    	    				'key'       =>  'groups_list',
	    	    				'value'     =>  '"' .$term->term_id . '"',
	    	    				'compare'   =>  'LIKE',
	    	    			));
	    	    		}
	    	    	}
	                $query['meta_key'] = 'groups_list';
	    	        $query['meta_query'] = $meta_query;



	    return $query;
	}
}
