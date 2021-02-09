<?php

/**
* Register customer taxonomy for files custom post type
*/
function file_category() {
    $labels = array(
        'name'                       => _x( 'Files category', 'Files Name', 'lsi' ),
        'singular_name'              => _x( 'File', 'Name of the file', 'lsi' ),
        'menu_name'                  => __( 'Category', 'lsi' ),
        'all_items'                  => __( 'All categories', 'lsi' ),
        'parent_item'                => __( 'Parent Category', 'lsi' ),
        'parent_item_colon'          => __( 'Parent Category:', 'lsi' ),
        'new_item_name'              => __( 'New Category Name', 'lsi' ),
        'add_new_item'               => __( 'Add Category', 'lsi' ),
        'edit_item'                  => __( 'Edit Category', 'lsi' ),
        'update_item'                => __( 'Update Category', 'lsi' ),
        'view_item'                  => __( 'View Category', 'lsi' ),
        'search_items'               => __( 'Search Categories', 'lsi' ),
        'not_found'                  => __( 'Not Found', 'lsi' ),
        'no_terms'                   => __( 'No categories', 'lsi' ),
        'items_list'                 => __( 'Categories list', 'lsi' ),
        'items_list_navigation'      => __( 'Categories list navigation', 'lsi' ),
    );
    $args = array(
        'hierarchical'              => true,
        'labels'                    => $labels,
        'public'                    => true,
        'show_ui'                   => true,
        'show_admin_column'         => true,
        'query_var'                 => true,
        'rewrite'                   => array('slug' => 'file_category')
    );
    register_taxonomy( 'file_category', 'file', $args );
}


/**
*   Functions for display files table on taxonomy archive page
*/

function single_file_tax($query) {
	if (!is_admin() AND is_tax('file_category') AND $query->is_main_query()) {
		$query->set('posts_per_page', 1);
	}
}

function display_title_of_taxonomy( $post ) {
	global $wp_query;

	if (is_tax('file_category')) {
		$tax = $wp_query->get_queried_object();
		$post->post_title = $tax->name;
	}
}


// Display pages with files grouped in taxonomies
function file_taxonomy_pages( $content ){
	global $wp_query;

	if (is_tax('file_category')) {
		$tax = $wp_query->get_queried_object();

		$manuals = get_posts(array(
			'post_type' => 'file',
			'tax_query'	=> array(
				array(
					'taxonomy' 	=> 'file_category',
					'field'	  	=> 'term_is',
					'terms'		=> $tax->term_id,
				),
			),
		)
		);
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
