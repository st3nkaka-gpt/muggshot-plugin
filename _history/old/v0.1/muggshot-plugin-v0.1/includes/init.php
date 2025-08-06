<?php
// Initieringskod för Muggshot-pluginet
add_action('admin_menu', function () {
    add_menu_page('Muggshot', 'Muggshot', 'manage_options', 'muggshot', function () {
        echo '<div class="wrap"><h1>Muggshot</h1><p>Inställningar och hantering av Instagram-inlägg.</p></div>';
    });
});
?>
