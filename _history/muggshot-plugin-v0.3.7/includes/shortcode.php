<?php
function muggshot_shortcode($atts) {
    $atts = shortcode_atts(['antal' => 5], $atts);
    wp_enqueue_style('muggshot-grid-style', plugin_dir_url(__FILE__) . '../assets/css/muggshot-grid.css');

    $query = new WP_Query([
        'post_type' => 'instapost',
        'posts_per_page' => intval($atts['antal'])
    ]);

    ob_start();
    echo '<div class="muggshot-grid">';
    while ($query->have_posts()) {
        $query->the_post();
        echo '<div class="muggshot-item">';
        if (has_post_thumbnail()) {
            the_post_thumbnail('medium');
        }
        echo '<h3>' . get_the_title() . '</h3>';
        echo '<p>' . get_the_content() . '</p>';
        echo '</div>';
    }
    echo '</div>';
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('muggshot-lista', 'muggshot_shortcode');
?>
