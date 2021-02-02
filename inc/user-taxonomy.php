<?php

/**
* Register customer groups taxonomy
*/
function customer_group() {
    $labels = array(
        'name'                       => _x( 'Groups', 'Groups Name', 'lsi' ),
        'singular_name'              => _x( 'Group', 'Department Name', 'lsi' ),
        'menu_name'                  => __( 'Groups', 'lsi' ),
        'all_items'                  => __( 'All Group', 'lsi' ),
        'parent_item'                => __( 'Parent Group', 'lsi' ),
        'parent_item_colon'          => __( 'Parent Group:', 'lsi' ),
        'new_item_name'              => __( 'New Group Name', 'lsi' ),
        'add_new_item'               => __( 'Add Group', 'lsi' ),
        'edit_item'                  => __( 'Edit Group', 'lsi' ),
        'update_item'                => __( 'Update Group', 'lsi' ),
        'view_item'                  => __( 'View Group', 'lsi' ),
        'search_items'               => __( 'Search Groups', 'lsi' ),
        'not_found'                  => __( 'Not Found', 'lsi' ),
        'no_terms'                   => __( 'No groups', 'lsi' ),
        'items_list'                 => __( 'Groups list', 'lsi' ),
        'items_list_navigation'      => __( 'Groups list navigation', 'lsi' ),
    );
    $args = array(
        'labels'                     => $labels,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
    );
    register_taxonomy( 'customer_group', 'user', $args );
}

/**
 * Admin page for the 'departments' taxonomy
 */
function add_customer_group_admin_page() {
    $tax = get_taxonomy( 'customer_group' );
    add_users_page(
        esc_attr( $tax->labels->menu_name ),
        esc_attr( $tax->labels->menu_name ),
        'manage_groups',
        'edit-tags.php?taxonomy=' . $tax->name
    );
}

/**
 * Unset the 'posts' column and adds a 'users' column on the manage customer groups admin page.
 */
function manage_customer_group_column( $columns ) {
    unset( $columns['posts'] );
    $columns['users'] = __( 'Users' );
    return $columns;
}


/**
 * @param string $display WP just passes an empty string here.
 * @param string $column The name of the custom column.
 * @param int $term_id The ID of the term being displayed in the table.
 */
function manage_customer_group_count_column( $display, $column, $term_id ) {
    if ( 'users' === $column ) {
        $term = get_term( $term_id, 'customer_group' );
        echo $term->count;
    }
}

/**
 * @param object $user The user object currently being edited.
 */
function edit_user_customer_group_section( $user ) {
    global $pagenow;
    $tax = get_taxonomy( 'customer_group' );
    /* Make sure the user can assign terms of the customer groups taxonomy before proceeding. */
    if ( !current_user_can( 'manage_groups' ) )
        return;
    /* Get the terms of the 'customer_group' taxonomy. */
    $terms = get_terms( 'customer_group', array( 'hide_empty' => false ) ); ?>
    <h2><?php echo __( 'Customer groups' ); ?></h2>
    <table class="form-table">
        <tr>
            <th><label for="customer_group"><?php _e( 'Select a customer group' ); ?></label></th>
            <td><div class="wp-tab-panel">
            <ul>
            <?php
            /* If there are any customer group terms, loop through them and display checkboxes. */
            if ( !empty( $terms ) ) {
                foreach ( $terms as $term ) { 
                ?>
                <li>
                    <label for="customer_group-<?php echo esc_attr( $term->slug ); ?>">
                        <input type="checkbox" name="customer_group[]" id="customer_group-<?php echo esc_attr( $term->slug ); ?>" value="<?php echo $term->slug; ?>" <?php if ( $pagenow !== 'user-new.php' ) checked( true, is_object_in_term( $user->ID, 'customer_group', $term->slug ) ); ?>>
                        <?php echo $term->name; ?>
                    </label>
                </li>
                <?php
                }
            }
            /* If there are no customer group terms, display a message. */
            else {
                _e( 'There are no customer group available.' );
            }
            ?>
            </ul>
            </div></td>
        </tr>
    </table>
<?php 
}

/**
 * @param int $user_id The ID of the user to save the terms for.
 */
function save_user_customer_group_terms( $user_id ) {
    $tax = get_taxonomy( 'customer_group' );
    /* Make sure the current user can edit the user and assign terms before proceeding. */
    if ( !current_user_can( 'edit_user', $user_id ) && !current_user_can( 'manage_groups' ) )
      return false;
    $term = $_POST['customer_group'];
    /* Sets the terms (we're just using a single term) for the user. */
    wp_set_object_terms( $user_id, $term, 'customer_group', false);
    clean_object_term_cache( $user_id, 'customer_group' );
  }
