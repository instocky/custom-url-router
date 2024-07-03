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
        add_action('wp_ajax_add_redirect_field', [$this, 'add_redirect_field']);
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
        register_setting($this->option_name, $this->option_name);

        // Content Management Section
        add_settings_section(
            'custom_url_router_content',
            '',
            null,
            'custom-url-router-content'
        );

        add_settings_field(
            'custom_content_types',
            'Custom Content Types',
            [$this, 'render_switch_field'],
            'custom-url-router-content',
            'custom_url_router_content',
            ['label_for' => 'custom_content_types', 'description' => 'Enable custom content types management.']
        );

        // Admin Interface Section
        add_settings_section(
            'custom_url_router_admin',
            '',
            null,
            'custom-url-router-admin'
        );

        add_settings_field(
            'admin_customization',
            'Admin Customization',
            [$this, 'render_switch_field'],
            'custom-url-router-admin',
            'custom_url_router_admin',
            ['label_for' => 'admin_customization', 'description' => 'Enable admin interface customization.']
        );

        add_settings_field(
            'redirects',
            'Redirects',
            [$this, 'render_redirects_field'],
            'custom-url-router-redirects',
            'custom_url_router_redirects'
        );

        // Redirects Section
        add_settings_section(
            'custom_url_router_redirects',
            '',
            null,
            'custom-url-router-redirects'
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
            return;
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


        wp_enqueue_style('custom-url-router-admin', plugin_dir_url(__FILE__) . 'css/style.css', [], '1.0.0');
        wp_enqueue_script('custom-url-router-admin', plugin_dir_url(__FILE__) . 'js/script.js', ['jquery'], '1.0.0', true);
    }

    public function render_switch_field($args)
    {
        $options = get_option($this->option_name);
        $field_id = $args['label_for'];
        $checked = isset($options[$field_id]) ? $options[$field_id] : false;
?>
        <label class="switch">
            <input type="checkbox" id="<?php echo esc_attr($field_id); ?>" name="<?php echo esc_attr($this->option_name . '[' . $field_id . ']'); ?>" <?php checked($checked, true); ?>>
            <span class="slider"></span>
        </label>
        <p class="description"><?php echo esc_html($args['description']); ?></p>
<?php
    }

    public function render_redirects_field()
    {
        $options = get_option($this->option_name);
        $redirects = isset($options['redirects']) ? $options['redirects'] : [];

        $args = [
            'option_name' => $this->option_name,
            'redirects' => $redirects
        ];

        require_once plugin_dir_path(__FILE__) . 'views/redirects-field.php';
    }

    public function add_redirect_field()
    {
        check_ajax_referer('add_redirect_field');

        if (!current_user_can('manage_options')) {
            wp_die(-1);
        }

        $index = isset($_POST['index']) ? intval($_POST['index']) : 0;
        $redirect = ['from' => '', 'to' => ''];

        $args = [
            'option_name' => $this->option_name,
            'index' => $index,
            'redirect' => $redirect
        ];

        ob_start();
        require_once plugin_dir_path(__FILE__) . 'views/single-redirect.php';
        $html = ob_get_clean();

        wp_send_json_success($html);
    }
}
