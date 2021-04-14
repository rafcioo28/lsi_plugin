<?php


function set_taxonomy_price_off( $wp_customize ) {
    $wp_customize->add_setting( 'taxonomy_price_off' , array(
    'default'   => '',
    'transport' => 'postMessage',
    ) );

    $wp_customize->add_section( 'taxonomy_price_off_section' , array(
        'title'      => __( ' Ustawienia dla LSI', 'lis' ),
        'priority'   => 30,
    ) );

    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'tax_price_off', array(
        'label'      => __( 'Nazwa taxonomi dla upustów cenowych (slug)', 'lsi' ),
        'section'    => 'taxonomy_price_off_section',
        'settings'   => 'taxonomy_price_off',
    ) ) );
}