<?php
add_action('admin_menu', function () {
    add_menu_page('Muggshot', 'Muggshot', 'manage_options', 'muggshot', function () {
        include plugin_dir_path(__FILE__) . '../admin/settings.php';
    });
});

add_action('admin_init', function () {
    register_setting('muggshot_settings', 'muggshot_hashtags');
});
?>
