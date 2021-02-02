<?php

/// pre query test 

if ( ! function_exists( 'lsi_product_query_filter' ) ) {

    function lsi_product_query_filter($query) {
        if (!current_user_can( 'show_all_products' )) {

    	    if (!is_admin() AND is_post_type_archive('product') AND $query->is_main_query()) {
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


if ( ! function_exists( 'filter_recentposts_events' ) ) {
	function filter_recent_posts_widget($args) {

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
	                $args['meta_key'] = 'groups_list';
	    	        $args['meta_query'] = $meta_query;



	    return $args;
	}
}
