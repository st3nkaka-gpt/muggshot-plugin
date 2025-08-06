<div class="wrap">
<h1>Muggshot Inställningar</h1>
<form method="post" action="options.php">
    <?php settings_fields('muggshot_settings'); ?>
    <?php do_settings_sections('muggshot_settings'); ?>
    <label for="muggshot_hashtags">Hashtags (kommaseparerade):</label>
    <input type="text" name="muggshot_hashtags" id="muggshot_hashtags" value="<?php echo esc_attr(get_option('muggshot_hashtags')); ?>" size="50"/>
    <?php submit_button(); ?>
</form>
</div>
