<?php
/*
Plugin Name: Muggshot Instagram Plugin
Description: Visar publika Instagram-inlägg baserat på hashtags för användning på Muggshot.se.
Version: 0.4.20
Author: Thomas Palmqvist
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: muggshot-plugin
*/

/*
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; version 2 of the License.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
*/

// Enkel shortcode för test
function muggshot_shortcode($atts) {
    $a = shortcode_atts([
        'hashtag' => 'muggshotse',
        'antal' => 12,
        'kolumner' => 3,
    ], $atts);

    return "<div>Muggshot-plugin aktivt. Hashtag: #{$a['hashtag']}</div>";
}
add_shortcode('muggshot', 'muggshot_shortcode');
