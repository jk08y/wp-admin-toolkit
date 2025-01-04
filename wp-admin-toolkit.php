<?php
/**
 * Plugin Name: WP Admin Toolkit
 * Plugin URI: https://github.com/jk08y/wp-admin-toolkit
 * Description: A comprehensive toolkit for customizing WordPress admin experience with advanced features
 * Version: 2.0.1
 * Author: Kimutai Joel
 * Author URI: https://github.com/jk08y
 * License: GPL v2 or later
 * Text Domain: wp-admin-toolkit
 * Requires at least: 5.0
 * Requires PHP: 7.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class WP_Admin_Toolkit {
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Core settings
        add_action('admin_menu', [$this, 'add_settings_page']);
        add_action('admin_init', [$this, 'register_settings']);
        
        // Theme customization
        add_action('wp_head', [$this, 'add_meta_colors']);
        add_action('wp_head', [$this, 'add_custom_favicon']);
        add_action('admin_head', [$this, 'add_custom_favicon']);
        add_action('login_head', [$this, 'add_custom_favicon']);
        add_action('admin_head', [$this, 'add_admin_custom_css']);
        
        // Login customization
        add_filter('login_headerurl', [$this, 'custom_login_logo_url']);
        add_action('login_enqueue_scripts', [$this, 'custom_login_styles']);
        
        // Admin bar customization
        if (get_option('wpatk_hide_admin_bar', true)) {
            add_filter('show_admin_bar', '__return_false');
        }
        
        // Dashboard widgets
        add_action('wp_dashboard_setup', [$this, 'customize_dashboard']);
        
        // Admin footer customization
        add_filter('admin_footer_text', [$this, 'custom_admin_footer']);
        
        // Email customization
        add_filter('wp_mail_from', [$this, 'custom_email_from']);
        add_filter('wp_mail_from_name', [$this, 'custom_email_from_name']);
        
        // Security features
        add_action('init', [$this, 'remove_version_info']);
        add_filter('login_errors', [$this, 'custom_login_error']);
        
        // Admin menu customization
        add_action('admin_menu', [$this, 'customize_admin_menu'], 999);
        
        // Load text domain
        add_action('plugins_loaded', [$this, 'load_textdomain']);
    }

    public function load_textdomain() {
        load_plugin_textdomain('wp-admin-toolkit', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }

    public function register_settings() {
        // Existing settings
        register_setting('wpatk_settings', 'wpatk_hide_admin_bar');
        register_setting('wpatk_settings', 'wpatk_theme_color');
        register_setting('wpatk_settings', 'wpatk_favicon_url');
        
        // New settings
        register_setting('wpatk_settings', 'wpatk_login_logo_url');
        register_setting('wpatk_settings', 'wpatk_login_background');
        register_setting('wpatk_settings', 'wpatk_custom_footer_text');
        register_setting('wpatk_settings', 'wpatk_email_from');
        register_setting('wpatk_settings', 'wpatk_email_from_name');
        register_setting('wpatk_settings', 'wpatk_hidden_menu_items', ['type' => 'array']);
        register_setting('wpatk_settings', 'wpatk_custom_css');
        register_setting('wpatk_settings', 'wpatk_disable_dashboard_widgets', ['type' => 'array']);
        register_setting('wpatk_settings', 'wpatk_enable_security_features');
    }

    public function add_settings_page() {
        add_menu_page(
            __('Admin Toolkit', 'wp-admin-toolkit'),
            __('Admin Toolkit', 'wp-admin-toolkit'),
            'manage_options',
            'wp-admin-toolkit',
            [$this, 'render_settings_page'],
            'dashicons-admin-tools'
        );
    }

    public function render_settings_page() {
        require_once plugin_dir_path(__FILE__) . 'templates/settings.php';
    }

    public function add_meta_colors() {
        $theme_color = get_option('wpatk_theme_color', '#ffffff');
        echo sprintf(
            '<meta name="theme-color" content="%s">
            <meta name="msapplication-navbutton-color" content="%s">
            <meta name="apple-mobile-web-app-status-bar-style" content="%s">',
            esc_attr($theme_color),
            esc_attr($theme_color),
            esc_attr($theme_color)
        );
    }

    public function add_custom_favicon() {
        $favicon_url = get_option('wpatk_favicon_url');
        if (!empty($favicon_url)) {
            echo '<link rel="shortcut icon" href="' . esc_url($favicon_url) . '" />';
        }
    }

    public function custom_login_logo_url($url) {
        $custom_url = get_option('wpatk_login_logo_url');
        return !empty($custom_url) ? esc_url($custom_url) : $url;
    }

    public function custom_login_styles() {
        $logo_url = get_option('wpatk_login_logo_url');
        $background = get_option('wpatk_login_background');
        $theme_color = get_option('wpatk_theme_color', '#ffffff');
        
        if (!empty($logo_url) || !empty($background) || !empty($theme_color)) {
            echo '<style type="text/css">';
            if (!empty($logo_url)) {
                echo '.login h1 a { background-image: url(' . esc_url($logo_url) . ') !important; }';
            }
            if (!empty($background)) {
                echo 'body.login { background-image: url(' . esc_url($background) . ') !important; background-size: cover; }';
            }
            if (!empty($theme_color)) {
                echo '.wp-core-ui .button-primary { background: ' . esc_attr($theme_color) . ' !important; border-color: ' . esc_attr($theme_color) . ' !important; }';
            }
            echo '</style>';
        }
    }

    public function customize_dashboard() {
        $disabled_widgets = get_option('wpatk_disable_dashboard_widgets', []);
        
        if (in_array('welcome', $disabled_widgets)) {
            remove_action('welcome_panel', 'wp_welcome_panel');
        }
        if (in_array('quick_press', $disabled_widgets)) {
            remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
        }
        if (in_array('recent_drafts', $disabled_widgets)) {
            remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side');
        }
        if (in_array('primary', $disabled_widgets)) {
            remove_meta_box('dashboard_primary', 'dashboard', 'side');
        }
        if (in_array('right_now', $disabled_widgets)) {
            remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
        }
    }

    public function custom_admin_footer($footer_text) {
        $custom_text = get_option('wpatk_custom_footer_text');
        return !empty($custom_text) ? wp_kses_post($custom_text) : $footer_text;
    }

    public function custom_email_from($email) {
        $custom_email = get_option('wpatk_email_from');
        return !empty($custom_email) ? $custom_email : $email;
    }

    public function custom_email_from_name($name) {
        $custom_name = get_option('wpatk_email_from_name');
        return !empty($custom_name) ? $custom_name : $name;
    }

    public function remove_version_info() {
        if (get_option('wpatk_enable_security_features', false)) {
            remove_action('wp_head', 'wp_generator');
            add_filter('the_generator', '__return_empty_string');
        }
    }

    public function custom_login_error($error) {
        if (get_option('wpatk_enable_security_features', false)) {
            return __('Invalid login credentials.', 'wp-admin-toolkit');
        }
        return $error;
    }

    public function customize_admin_menu() {
        $hidden_items = get_option('wpatk_hidden_menu_items', []);
        foreach ($hidden_items as $item) {
            remove_menu_page($item);
        }
    }

    public function add_admin_custom_css() {
        $custom_css = get_option('wpatk_custom_css');
        if (!empty($custom_css)) {
            echo '<style type="text/css">' . esc_html($custom_css) . '</style>';
        }
    }
}

// Initialize plugin
function wp_admin_toolkit_init() {
    return WP_Admin_Toolkit::get_instance();
}
add_action('plugins_loaded', 'wp_admin_toolkit_init');

// Activation hook
register_activation_hook(__FILE__, function() {
    // Default options
    add_option('wpatk_hide_admin_bar', true);
    add_option('wpatk_theme_color', '#ffffff');
    add_option('wpatk_enable_security_features', false);
    add_option('wpatk_disable_dashboard_widgets', []);
    add_option('wpatk_hidden_menu_items', []);
});

// Deactivation hook
register_deactivation_hook(__FILE__, function() {
    // Remove all plugin options
    $options = [
        'wpatk_hide_admin_bar',
        'wpatk_theme_color',
        'wpatk_favicon_url',
        'wpatk_login_logo_url',
        'wpatk_login_background',
        'wpatk_custom_footer_text',
        'wpatk_email_from',
        'wpatk_email_from_name',
        'wpatk_hidden_menu_items',
        'wpatk_custom_css',
        'wpatk_disable_dashboard_widgets',
        'wpatk_enable_security_features'
    ];
    
    foreach ($options as $option) {
        delete_option($option);
    }
});
