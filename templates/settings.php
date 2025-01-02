<?php
// templates/settings.php
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <form method="post" action="options.php">
        <?php
        settings_fields('wpatk_settings');
        do_settings_sections('wpatk_settings');
        ?>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="wpatk_hide_admin_bar">Admin Bar</label>
                </th>
                <td>
                    <label>
                        <input type="checkbox" 
                               id="wpatk_hide_admin_bar" 
                               name="wpatk_hide_admin_bar" 
                               value="1" 
                               <?php checked(get_option('wpatk_hide_admin_bar', true)); ?>>
                        <?php _e('Hide WordPress Admin Bar', 'wp-admin-toolkit'); ?>
                    </label>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="wpatk_theme_color">Theme Color</label>
                </th>
                <td>
                    <input type="color" 
                           id="wpatk_theme_color" 
                           name="wpatk_theme_color" 
                           value="<?php echo esc_attr(get_option('wpatk_theme_color', '#ffffff')); ?>">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="wpatk_favicon_url">Custom Favicon URL</label>
                </th>
                <td>
                    <input type="url" 
                           id="wpatk_favicon_url" 
                           name="wpatk_favicon_url" 
                           class="regular-text"
                           value="<?php echo esc_url(get_option('wpatk_favicon_url')); ?>">
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
</div>