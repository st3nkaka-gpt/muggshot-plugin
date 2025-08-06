<?php
function muggshot_fetch_mock_data() {
    $json_path = plugin_dir_path(__FILE__) . '../mock_instagram_data.json';
    if (!file_exists($json_path)) return;

    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    $json = file_get_contents($json_path);
    $posts = json_decode($json, true);

    foreach ($posts as $item) {
        $existing = get_posts([
            'post_type' => 'instapost',
            'meta_key' => 'insta_id',
            'meta_value' => $item['id'],
            'posts_per_page' => 1
        ]);

        if ($existing) continue;

        $post_id = wp_insert_post([
            'post_title' => wp_strip_all_tags(substr($item['caption'], 0, 50)),
            'post_content' => $item['caption'],
            'post_status' => 'publish',
            'post_type' => 'instapost',
            'post_date' => date('Y-m-d H:i:s', strtotime($item['timestamp']))
        ]);

        if (!is_wp_error($post_id)) {
            update_post_meta($post_id, 'insta_id', $item['id']);

            $image_url = $item['image_url'];
            $tmp = download_url($image_url);
            if (is_wp_error($tmp)) continue;

            $desc = "Instagrambild";
            $file_array = [
                'name' => basename($image_url),
                'tmp_name' => $tmp
            ];

            $media_id = media_handle_sideload($file_array, $post_id, $desc);
            if (!is_wp_error($media_id)) {
                set_post_thumbnail($post_id, $media_id);
            }

            if (!empty($item['hashtags'])) {
                wp_set_post_terms($post_id, $item['hashtags'], 'instatag');
            }
        }
    }
}
add_action('init', 'muggshot_fetch_mock_data');
?>
