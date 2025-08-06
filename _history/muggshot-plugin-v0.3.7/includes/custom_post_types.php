<?php
function muggshot_register_post_types() {
    register_post_type('instapost', [
        'labels' => ['name' => 'InstaPoster'],
        'public' => true,
        'has_archive' => true,
        'supports' => ['title', 'editor', 'thumbnail']
    ]);

    register_post_type('instapost_archive', [
        'labels' => ['name' => 'InstaArkiv'],
        'public' => false,
        'has_archive' => false,
        'supports' => ['title', 'editor', 'thumbnail']
    ]);
}
add_action('init', 'muggshot_register_post_types');
?>
