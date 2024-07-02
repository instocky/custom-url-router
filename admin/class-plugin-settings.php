<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class PluginSettings
{
    private $option_name = 'custom_url_router_settings';

    public function __construct()
    {
        add_action('admin_menu', [$this, 'add_settings_page']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
    }

    public function init()
    {
        // Может быть использовано для дополнительной инициализации в будущем
    }

    public function add_settings_page()
    {
        add_options_page(
            'Custom URL Router Settings', // Page title
            'Custom URL Router',          // Menu title
            'manage_options',             // Capability
            'custom-url-router',          // Menu slug
            [$this, 'render_settings_page'] // Function to render the page
        );
    }

    public function register_settings()
    {
        register_setting($this->option_name, $this->option_name, [$this, 'sanitize_settings']);

        add_settings_section(
            'custom_url_router_main',
            'Main Settings',
            [$this, 'section_callback'],
            'custom-url-router'
        );

        add_settings_field(
            'use_cdn',
            'Использовать CDN для ресурсов',
            [$this, 'use_cdn_callback'],
            'custom-url-router',
            'custom_url_router_main'
        );
    }

    public function sanitize_settings($input)
    {
        $new_input = [];
        $new_input['use_cdn'] = isset($input['use_cdn']) ? (bool) $input['use_cdn'] : false;
        return $new_input;
    }

    public function section_callback()
    {
        echo '<p>Main settings for Custom URL Router</p>';
    }

    public function use_cdn_callback()
    {
        $options = get_option($this->option_name);
        $use_cdn = isset($options['use_cdn']) ? $options['use_cdn'] : true;
        echo '<input type="checkbox" id="use_cdn" name="' . $this->option_name . '[use_cdn]" value="1" ' . checked($use_cdn, true, false) . ' />';
        echo '<label for="use_cdn">Использовать CDN для загрузки Tailwind и Alpine.js</label>';
    }

    public function render_settings_page()
    {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        if (isset($_GET['settings-updated'])) {
            add_settings_error('custom_url_router_messages', 'custom_url_router_message', 'Settings Saved', 'updated');
        }

        settings_errors('custom_url_router_messages');

        require_once plugin_dir_path(__FILE__) . 'views/settings-page.php';
    }


    public function enqueue_admin_scripts($hook)
    {
        if ('settings_page_custom-url-router' !== $hook) {
            return;
        }

        wp_enqueue_style('custom-url-router-admin', plugin_dir_url(__FILE__) . 'css/style.css', [], '1.0.0');
        wp_enqueue_script('custom-url-router-admin', plugin_dir_url(__FILE__) . 'js/script.js', ['jquery'], '1.0.0', true);
    }
}
