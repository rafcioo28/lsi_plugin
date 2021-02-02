<?php 

function initial_role_remove() {
	remove_role('author');
	remove_role('contributor');
	remove_role('subscriber');
}
 
// Remove roles.
add_action( 'init', 'initial_role_remove' );

function initial_add_role() {
    update_option('default_role','customer');

    add_role(
		'salesman',
		'Salesman',
		array(
            'read'              => true,
            'edit_posts'        => true,
            'upload_files'      => true,
            'manage_groups'     => true,
            'show_all_products' => true,
        )
    );
    
    add_role(
        'premium_customer',
        'Premium Customer',
        array(
            'read'  =>  true,
        )
    );

    $manager_role = get_role('shop_manager');
    $manager_role->add_cap('manage_groups');
    $manager_role->add_cap('show_all_products');
}

add_action( 'init', 'initial_add_role' );