<?php
function acf_load_group_field_choices( $field ) {
    // reset choices
    $field['choices'] = array(); 

    $taxonomy = get_terms(array(
        'taxonomy'      => 'customer_group',
        'hide_empty'    => false,
        'parent'        => 0,
        'orderby'       => 'name',
    ));

    $arr = array();
    // loop through array and add to field 'choices'            
    foreach( $taxonomy as $tax_term ) {
        $arr[$tax_term->slug] = $tax_term->name;
    }       
    
    $field['choices'] = $arr;
    return $field;
}