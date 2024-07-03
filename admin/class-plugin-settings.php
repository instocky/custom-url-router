<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class PluginSettings
{
    /**
     * Имя опции для хранения настроек плагина.
     *
     * @var string
     */
    private $option_name = 'custom_url_router_settings';

    /**
     * Конструктор класса.
     * Инициализирует хуки для добавления страницы настроек и регистрации настроек.
     */
    public function __construct()
    {
        add_action('admin_menu', [$this, 'add_settings_page']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
    }

    /**
     * Инициализация дополнительных параметров плагина.
     * В настоящее время не используется, но может быть полезен для будущих расширений.
     */
    public function init()
    {
        // Может быть использовано для дополнительной инициализации в будущем
    }

    /**
     * Добавляет страницу настроек в меню WordPress.
     */
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

    /**
     * Регистрирует настройки плагина.
     * Создает секцию настроек и добавляет поля для настройки.
     */
    public function register_settings()
    {
        register_setting($this->option_name, $this->option_name, [$this, 'sanitize_settings']);

        add_settings_section(
            'custom_url_router_main',
            'Main Settings',
            [$this, 'section_callback'],
            'custom-url-router'
        );
    }

    /**
     * Санитизирует введенные пользователем настройки.
     *
     * @param array $input Массив введенных пользователем настроек.
     * @return array Санитизированный массив настроек.
     */
    public function sanitize_settings($input)
    {
        return $input;
    }

    /**
     * Выводит описание для секции настроек.
     */
    public function section_callback()
    {
        echo '<p>Main settings for Custom URL Router</p>';
    }

    /**
     * Отображает страницу настроек плагина.
     * Проверяет права доступа и выводит форму с настройками.
     */
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

    /**
     * Подключает стили и скрипты для страницы настроек в админ-панели.
     *
     * @param string $hook Текущий хук страницы админ-панели.
     */
    public function enqueue_admin_scripts($hook)
    {
        if ('settings_page_custom-url-router' !== $hook) {
            return;
        }

        // Подключаем Tailwind CSS из CDN
        wp_enqueue_style('tailwindcss', 'https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css', [], '2.2.19');

        // Подключаем Alpine.js из CDN
        wp_enqueue_script('alpinejs', 'https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js', [], '3.0.0', true);


        // wp_enqueue_style('custom-url-router-admin', plugin_dir_url(__FILE__) . 'css/style.css', [], '1.0.0');
        // wp_enqueue_script('custom-url-router-admin', plugin_dir_url(__FILE__) . 'js/script.js', ['jquery'], '1.0.0', true);
    }
}
