 
<?php
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
        
        <div class="nav-tab-wrapper">
            <a href="#general" class="nav-tab nav-tab-active"><?php _e('General', 'wp-admin-toolkit'); ?></a>
            <a href="#login" class="nav-tab"><?php _e('Login Page', 'wp-admin-toolkit'); ?></a>
            <a href="#dashboard" class="nav-tab"><?php _e('Dashboard', 'wp-admin-toolkit'); ?></a>
            <a href="#email" class="nav-tab"><?php _e('Email', 'wp-admin-toolkit'); ?></a>
            <a href="#security" class="nav-tab"><?php _e('Security', 'wp-admin-toolkit'); ?></a>
            <a href="#custom-css" class="nav-tab"><?php _e('Custom CSS', 'wp-admin-toolkit'); ?></a>
        </div>

        <div id="general" class="tab-content active">
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="wpatk_hide_admin_bar"><?php _e('Admin Bar', 'wp-admin-toolkit'); ?></label>
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
                        <label for="wpatk_theme_color"><?php _e('Theme Color', 'wp-admin-toolkit'); ?></label>
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
                        <label for="wpatk_favicon_url"><?php _e('Custom Favicon URL', 'wp-admin-toolkit'); ?></label>
                    </th>
                    <td>
                        <input type="url" 
                               id="wpatk_favicon_url" 
                               name="wpatk_favicon_url" 
                               class="regular-text"
                               value="<?php echo esc_url(get_option('wpatk_favicon_url')); ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="wpatk_custom_footer_text"><?php _e('Custom Footer Text', 'wp-admin-toolkit'); ?></label>
                    </th>
                    <td>
                        <textarea id="wpatk_custom_footer_text" 
                                  name="wpatk_custom_footer_text" 
                                  class="large-text" 
                                  rows="3"><?php echo esc_textarea(get_option('wpatk_custom_footer_text')); ?></textarea>
                    </td>
                </tr>
            </table>
        </div>

        <div id="login" class="tab-content">
            <table class="form-table">
            <tr>
                    <th scope="row">
                        <label for="wpatk_login_logo_url"><?php _e('Custom Login Logo URL', 'wp-admin-toolkit'); ?></label>
                    </th>
                    <td>
                        <input type="url" 
                               id="wpatk_login_logo_url" 
                               name="wpatk_login_logo_url" 
                               class="regular-text"
                               value="<?php echo esc_url(get_option('wpatk_login_logo_url')); ?>">
                        <p class="description"><?php _e('Enter the URL of your custom login page logo', 'wp-admin-toolkit'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="wpatk_login_background"><?php _e('Login Background Image', 'wp-admin-toolkit'); ?></label>
                    </th>
                    <td>
                        <input type="url" 
                               id="wpatk_login_background" 
                               name="wpatk_login_background" 
                               class="regular-text"
                               value="<?php echo esc_url(get_option('wpatk_login_background')); ?>">
                        <p class="description"><?php _e('Enter the URL of your login page background image', 'wp-admin-toolkit'); ?></p>
                    </td>
                </tr>
            </table>
        </div>

        <div id="dashboard" class="tab-content">
            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e('Disable Dashboard Widgets', 'wp-admin-toolkit'); ?></th>
                    <td>
                        <?php
                        $disabled_widgets = get_option('wpatk_disable_dashboard_widgets', []);
                        $widgets = [
                            'welcome' => __('Welcome Panel', 'wp-admin-toolkit'),
                            'quick_press' => __('Quick Draft', 'wp-admin-toolkit'),
                            'recent_drafts' => __('Recent Drafts', 'wp-admin-toolkit'),
                            'primary' => __('WordPress News', 'wp-admin-toolkit'),
                            'right_now' => __('At a Glance', 'wp-admin-toolkit')
                        ];
                        foreach ($widgets as $key => $label) :
                        ?>
                        <label>
                            <input type="checkbox" 
                                   name="wpatk_disable_dashboard_widgets[]" 
                                   value="<?php echo esc_attr($key); ?>"
                                   <?php checked(in_array($key, $disabled_widgets)); ?>>
                            <?php echo esc_html($label); ?>
                        </label><br>
                        <?php endforeach; ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Hide Menu Items', 'wp-admin-toolkit'); ?></th>
                    <td>
                        <?php
                        $hidden_items = get_option('wpatk_hidden_menu_items', []);
                        $menu_items = [
                            'index.php' => __('Dashboard', 'wp-admin-toolkit'),
                            'edit.php' => __('Posts', 'wp-admin-toolkit'),
                            'upload.php' => __('Media', 'wp-admin-toolkit'),
                            'edit.php?post_type=page' => __('Pages', 'wp-admin-toolkit'),
                            'edit-comments.php' => __('Comments', 'wp-admin-toolkit'),
                            'themes.php' => __('Appearance', 'wp-admin-toolkit'),
                            'plugins.php' => __('Plugins', 'wp-admin-toolkit'),
                            'users.php' => __('Users', 'wp-admin-toolkit'),
                            'tools.php' => __('Tools', 'wp-admin-toolkit'),
                            'options-general.php' => __('Settings', 'wp-admin-toolkit')
                        ];
                        foreach ($menu_items as $key => $label) :
                        ?>
                        <label>
                            <input type="checkbox" 
                                   name="wpatk_hidden_menu_items[]" 
                                   value="<?php echo esc_attr($key); ?>"
                                   <?php checked(in_array($key, $hidden_items)); ?>>
                            <?php echo esc_html($label); ?>
                        </label><br>
                        <?php endforeach; ?>
                    </td>
                </tr>
            </table>
        </div>

        <div id="email" class="tab-content">
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="wpatk_email_from"><?php _e('From Email Address', 'wp-admin-toolkit'); ?></label>
                    </th>
                    <td>
                        <input type="email" 
                               id="wpatk_email_from" 
                               name="wpatk_email_from" 
                               class="regular-text"
                               value="<?php echo esc_attr(get_option('wpatk_email_from')); ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="wpatk_email_from_name"><?php _e('From Name', 'wp-admin-toolkit'); ?></label>
                    </th>
                    <td>
                        <input type="text" 
                               id="wpatk_email_from_name" 
                               name="wpatk_email_from_name" 
                               class="regular-text"
                               value="<?php echo esc_attr(get_option('wpatk_email_from_name')); ?>">
                    </td>
                </tr>
            </table>
        </div>

        <div id="security" class="tab-content">
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="wpatk_enable_security_features"><?php _e('Security Features', 'wp-admin-toolkit'); ?></label>
                    </th>
                    <td>
                        <label>
                            <input type="checkbox" 
                                   id="wpatk_enable_security_features" 
                                   name="wpatk_enable_security_features" 
                                   value="1"
                                   <?php checked(get_option('wpatk_enable_security_features', false)); ?>>
                            <?php _e('Enable Enhanced Security Features', 'wp-admin-toolkit'); ?>
                        </label>
                        <p class="description"><?php _e('Removes WordPress version info and customizes login error messages', 'wp-admin-toolkit'); ?></p>
                    </td>
                </tr>
            </table>
        </div>

        <div id="custom-css" class="tab-content">
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="wpatk_custom_css"><?php _e('Custom Admin CSS', 'wp-admin-toolkit'); ?></label>
                    </th>
                    <td>
                        <textarea id="wpatk_custom_css" 
                                  name="wpatk_custom_css" 
                                  class="large-text code" 
                                  rows="10"><?php echo esc_textarea(get_option('wpatk_custom_css')); ?></textarea>
                        <p class="description"><?php _e('Add custom CSS for the WordPress admin area', 'wp-admin-toolkit'); ?></p>
                    </td>
                </tr>
            </table>
        </div>

        <?php submit_button(); ?>
    </form>
</div>

<script>
jQuery(document).ready(function($) {
    // Tab functionality
    $('.nav-tab-wrapper a').click(function(e) {
        e.preventDefault();
        
        // Update active tab
        $('.nav-tab-wrapper a').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');
        
        // Show selected content
        $('.tab-content').removeClass('active');
        $($(this).attr('href')).addClass('active');
    });
});
</script>

<style>
.tab-content {
    display: none;
    padding: 20px 0;
}
.tab-content.active {
    display: block;
}
label {
    margin-right: 20px;
}
</style>
