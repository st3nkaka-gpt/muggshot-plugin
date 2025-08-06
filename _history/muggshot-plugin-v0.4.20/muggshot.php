<?php
/*
Plugin Name: Muggshot
Description: Visar Instagram-inlägg i grid med metadata, röstning (både localStorage och databas) och topplistor via shortcode.
Version: 0.4.13
Author: Thomas & Effie
*/

defined('ABSPATH') or die('No script kiddies please!');

add_theme_support('post-thumbnails');

function muggshot_register_post_type() {
    register_post_type('muggshot_post', array(
        'labels' => array(
            'name' => __('Muggshot-inlägg'),
            'singular_name' => __('Muggshot-inlägg'),
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-format-gallery',
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
    ));
}
add_action('init', 'muggshot_register_post_type');

function muggshot_enqueue_assets() {
    wp_enqueue_style('muggshot-style', plugin_dir_url(__FILE__) . 'assets/css/muggshot.css');
    wp_enqueue_script('muggshot-rating', plugin_dir_url(__FILE__) . 'assets/js/ratings.js', array('jquery'), null, true);
    wp_localize_script('muggshot-rating', 'muggshot_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('muggshot_vote_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'muggshot_enqueue_assets');

// AJAX för att spara röster
add_action('wp_ajax_muggshot_vote', 'muggshot_vote_handler');
add_action('wp_ajax_nopriv_muggshot_vote', 'muggshot_vote_handler');
function muggshot_vote_handler() {
    check_ajax_referer('muggshot_vote_nonce', 'nonce');
    $post_id = intval($_POST['post_id']);
    $rating = intval($_POST['rating']);
    if ($post_id && $rating >= 1 && $rating <= 4) {
        $total = (int)get_post_meta($post_id, 'muggshot_total_score', true);
        $votes = (int)get_post_meta($post_id, 'muggshot_total_votes', true);
        $total += $rating;
        $votes += 1;
        update_post_meta($post_id, 'muggshot_total_score', $total);
        update_post_meta($post_id, 'muggshot_total_votes', $votes);
        wp_send_json_success(array('total' => $total, 'votes' => $votes));
    } else {
        wp_send_json_error('Invalid vote');
    }
    wp_die();
}

// Shortcode
function muggshot_render_list($atts) {
    $atts = shortcode_atts(array(
        'antal' => 10,
        'visa_caption' => 'nej',
        'visa_datum' => 'nej',
        'visa_hashtags' => 'nej',
        'kolumner' => 3,
        'top' => ''
    ), $atts, 'muggshot-lista');

    $args = array(
        'post_type' => 'muggshot_post',
        'posts_per_page' => $atts['antal']
    );

    if ($atts['top'] === 'nu') {
        $args['orderby'] = 'meta_value_num';
        $args['meta_key'] = 'muggshot_total_score';
        $args['order'] = 'DESC';
        $args['date_query'] = array(array(
            'after' => '7 days ago'
        ));
    } elseif ($atts['top'] === 'alltime') {
        $args['orderby'] = 'meta_value_num';
        $args['meta_key'] = 'muggshot_total_score';
        $args['order'] = 'DESC';
    }

    $query = new WP_Query($args);
    if (!$query->have_posts()) return '<p>Inga inlägg hittades.</p>';

    ob_start();
    echo "<div class='muggshot-grid kol-{$atts['kolumner']}'>";
    while ($query->have_posts()) : $query->the_post();
        $post_id = get_the_ID();
        $image_url = get_the_post_thumbnail_url($post_id, 'medium');
        echo "<div class='muggshot-item'>";
        if ($image_url) echo "<img src='{$image_url}' alt=''>";
        echo "<div class='muggshot-item-content'>";
        if ($atts['visa_caption'] === 'ja') echo "<div class='caption'>" . get_the_title() . "</div>";
        if ($atts['visa_datum'] === 'ja') echo "<div class='datum'>" . get_the_date() . "</div>";
        if ($atts['visa_hashtags'] === 'ja') {
            $tags = wp_get_post_tags($post_id, array('fields' => 'names'));
            echo "<div class='hashtags'>#" . implode(' #', $tags) . "</div>";
        }
        echo "<div class='stars' data-post-id='{$post_id}'>";
        for ($i = 0; $i < 4; $i++) echo "<span class='star'>★</span>";
        echo "</div>";
        echo "</div></div>";
    endwhile;
    echo "</div>";
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('muggshot-lista', 'muggshot_render_list');

function muggshot_admin_menu() {
    add_menu_page('Muggshot', 'Muggshot', 'manage_options', 'muggshot', 'muggshot_admin_page', 'dashicons-camera');
}
add_action('admin_menu', 'muggshot_admin_menu');

function muggshot_admin_page() {
    echo "<div class='wrap'><h1>Muggshot Plugin</h1><p>Använd shortcodes för att visa inlägg. T.ex. <code>[muggshot-lista antal='12' top='alltime']</code></p></div>";
}
?>
