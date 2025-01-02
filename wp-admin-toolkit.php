<?php
/**
 * Plugin Name: WP Admin Toolkit
 * Plugin URI: https://github.com/jk08y/wp-admin-toolkit
 * Description: A comprehensive toolkit for customizing WordPress admin experience including admin bar control, meta colors, and custom favicon
 * Version: 1.0.0
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
        add_action('admin_menu', [$this, 'add_settings_page']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('wp_head', [$this, 'add_meta_colors']);
        add_action('wp_head', [$this, 'add_custom_favicon']);
        add_action('admin_head', [$this, 'add_custom_favicon']);
        
        // Hide admin bar based on settings
        if (get_option('wpatk_hide_admin_bar', true)) {
            add_filter('show_admin_bar', '__return_false');
        }
    }

    public function add_settings_page() {
        add_options_page(
            'Admin Toolkit Settings',
            'Admin Toolkit',
            'manage_options',
            'wp-admin-toolkit',
            [$this, 'render_settings_page']
        );
    }

    public function register_settings() {
        register_setting('wpatk_settings', 'wpatk_hide_admin_bar');
        register_setting('wpatk_settings', 'wpatk_theme_color');
        register_setting('wpatk_settings', 'wpatk_favicon_url');
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
}

// Initialize plugin
function wp_admin_toolkit_init() {
    return WP_Admin_Toolkit::get_instance();
}
add_action('plugins_loaded', 'wp_admin_toolkit_init');

// Activation/Deactivation hooks
register_activation_hook(__FILE__, function() {
    add_option('wpatk_hide_admin_bar', true);
    add_option('wpatk_theme_color', '#ffffff');
});

register_deactivation_hook(__FILE__, function() {
    delete_option('wpatk_hide_admin_bar');
    delete_option('wpatk_theme_color');
    delete_option('wpatk_favicon_url');
});