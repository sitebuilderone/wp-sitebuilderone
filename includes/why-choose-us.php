<?php
// Register Custom Post Type
function sb1_register_why_choose_us_cpt() {
    $labels = array(
        'name' => 'Why Choose Us',
        'singular_name' => 'Why Choose Us Item',
        'menu_name' => 'Why Choose Us',
        'name_admin_bar' => 'Why Choose Us',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Reason',
        'edit_item' => 'Edit Reason',
        'new_item' => 'New Reason',
        'view_item' => 'View Reason',
        'all_items' => 'All Reasons',
        'search_items' => 'Search Reasons',
        'not_found' => 'No reasons found',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => false,
        'menu_icon' => 'dashicons-thumbs-up',
        'supports' => array('title', 'editor', 'thumbnail'),
        'show_in_rest' => true,
    );

    register_post_type('why_choose_us', $args);
}
add_action('init', 'sb1_register_why_choose_us_cpt');

// Optional: Register Taxonomy for Category Filter
/*
function sb1_register_why_choose_us_taxonomy() {
    register_taxonomy(
        'category',
        'why_choose_us',
        array(
            'label' => 'Categories',
            'hierarchical' => true,
            'show_ui' => true,
            'show_in_rest' => true,
        )
    );
}
add_action('init', 'sb1_register_why_choose_us_taxonomy');
*/

// Shortcode Display
function sb1_display_why_choose_us( $atts ) {
    $atts = shortcode_atts(array(
        'category' => '',
        'limit' => -1,
    ), $atts);

    $args = array(
        'post_type' => 'why_choose_us',
        'posts_per_page' => $atts['limit'],
        'meta_key' => 'priority_order',
        'orderby' => 'meta_value_num',
        'order' => 'ASC',
    );

    if (!empty($atts['category'])) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'category',
                'field' => 'slug',
                'terms' => $atts['category']
            )
        );
    }

    $query = new WP_Query($args);

    ob_start();
    echo '<section class="why-choose-section py-5">';
    echo '<div class="container">';
    echo '<div class="why-choose-us-list row">';

    while ($query->have_posts()) {
        $query->the_post();
        $icon = function_exists('get_field') ? get_field('icon') : '';
        echo '<div class="col-md-4 mb-4">';
        echo '<div class="why-choose-box p-3 border rounded h-100">';
        if ($icon) {
            echo '<div class="mb-2"><img src="' . esc_url($icon['url']) . '" alt="" class="img-fluid" style="max-height:40px;"></div>';
        }
        echo '<h5>' . esc_html(get_the_title()) . '</h5>';
        echo '<p>' . wp_kses_post(get_the_content()) . '</p>';
        echo '</div></div>';
    }

    echo '</div></div></section>';
    wp_reset_postdata();

    return ob_get_clean();
}
add_shortcode('why_choose_us', 'sb1_display_why_choose_us');


if (function_exists('acf_add_local_field_group')):

    acf_add_local_field_group(array(
      'key' => 'group_why_choose_us',
      'title' => 'Why Choose Us Fields',
      'fields' => array(
        array(
          'key' => 'field_why_icon',
          'label' => 'Icon',
          'name' => 'icon',
          'type' => 'image',
          'return_format' => 'array',
          'preview_size' => 'thumbnail',
          'library' => 'all',
        ),
        array(
          'key' => 'field_why_priority',
          'label' => 'Priority Order',
          'name' => 'priority_order',
          'type' => 'number',
          'instructions' => 'Controls the order in which this item is displayed.',
          'default_value' => 0,
        ),
        array(
          'key' => 'field_why_display_on_pages',
          'label' => 'Display on Pages',
          'name' => 'display_on_pages',
          'type' => 'relationship',
          'post_type' => array('page'),
          'return_format' => 'id',
          'filters' => array('search'),
        ),
      ),
      'location' => array(
        array(
          array(
            'param' => 'post_type',
            'operator' => '==',
            'value' => 'why_choose_us',
          ),
        ),
      ),
      'style' => 'default',
      'position' => 'normal',
      'label_placement' => 'top',
      'instruction_placement' => 'label',
    ));
    
    endif;
    
?>
