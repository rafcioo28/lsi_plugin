<?php

function lsi_post_types() {
//Files post type
    register_post_type('file', array(
        'show_in_rest'      => true,
        'supports'          => array('title', 'excerpt'),
        'rewrite'           =>  array('slug' => 'files'),
        'has_archive'       =>  true,
        'public'            =>  true,
        'labels'            =>  array(
            'name'              => 'Pliki',
            'add_new_item'      => 'Dodaj nowy plik',
            'edit_item'         => 'Edytuj plik',
            'all_items'         => 'Wszystkie pliki',
            'singular_name'     => 'Plik',
        ),
        'menu_icon'         =>  'dashicons-portfolio',
    ));

    register_post_type('price_discount', array(
        'show_in_rest'      => true,
        'supports'          => array('title'),
        'has_archive'       =>  false,
        'public'            =>  false,
        'show_ui'           =>  true,
        'labels'            =>  array(
            'name'              => 'Poziomy cenowe',
            'add_new_item'      => 'Dodaj nowy poziom',
            'edit_item'         => 'Edytuj',
            'all_items'         => 'Wszystkie poziomy',
            'singular_name'     => 'Poziom cenowy',
        ),
        'menu_icon'         =>  'dashicons-money-alt',
    ));

}