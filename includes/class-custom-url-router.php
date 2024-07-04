<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
class IC_CustomURLRouter
{
    /**
     * Массив кастомных маршрутов.
     * Ключ - желаемый URL, значение - соответствующий WordPress slug.
     *
     * @var array
     */
    private $custom_routes = [
        'parent/slugRegistry' => 'parent/slugregistry',
        'parent/eduStandart' => 'parent/edustandart',
    ];
    /**
     * Массив редиректов.
     * Ключ - исходный URL, значение - целевой URL.
     *
     * @var array
     */
    private $redirects = [
        'parent/' => 'parent/slugRegistry/',
        '0704-2/' => 'parent/slugRegistry/',
    ];
    /**
     * Массив слагов страниц, где нужно подключить Tailwind и Alpine.
     *
     * @var array
     */
    private $tailwind_alpine_slugs = [
        'parent/slugRegistry',
        'parent/edustandart',
    ];
    /**
     * Инициализация плагина.
     * Подключает все необходимые хуки.
     *
     * @return void
     */
    public function init()
    {
        add_action('init', [$this, 'register_routes']);
        add_filter('request', [$this, 'handle_custom_routes']);
        add_action('template_redirect', [$this, 'redirect_incorrect_case']);
        add_action('template_redirect', [$this, 'handle_redirects']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_tailwind_alpine']);
    }
    /**
     * Регистрирует правила перезаписи для кастомных маршрутов.
     *
     * @return void
     */
    public function register_routes()
    {
        foreach ($this->custom_routes as $custom_route => $wp_route) {
            add_rewrite_rule(
                '^' . $custom_route . '/?$',
                'index.php?pagename=' . $wp_route . '&custom_route=' . $custom_route,
                'top'
            );
        }
    }
    /**
     * Обрабатывает кастомные маршруты в запросе.
     * 
     * @param array $query_vars Переменные запроса WordPress
     * @return array Модифицированные переменные запроса
     */
    public function handle_custom_routes($query_vars)
    {
        if (isset($query_vars['custom_route'])) {
            $query_vars['pagename'] = $this->custom_routes[$query_vars['custom_route']];
            error_log('Modified query vars: ' . print_r($query_vars, true));
        }
        return $query_vars;
    }
    /**
     * Выполняет редирект на URL с правильным регистром.
     *
     * @return void
     */
    public function redirect_incorrect_case()
    {
        global $wp;
        $current_url = trailingslashit(home_url($wp->request));
        foreach ($this->custom_routes as $custom_route => $wp_route) {
            $correct_url = trailingslashit(home_url($custom_route));
            if (strtolower($current_url) === strtolower($correct_url) && $current_url !== $correct_url) {
                wp_redirect($correct_url, 301);
                exit;
            }
        }
    }
    /**
     * Обрабатывает редиректы на основе массива $redirects
     *
     * @return void
     */
    public function handle_redirects()
    {
        global $wp;
        $current_url = trailingslashit($wp->request);
        foreach ($this->redirects as $from => $to) {
            if ($current_url === $from) {
                wp_redirect(home_url($to), 301);
                exit;
            }
        }
    }
    /**
     * Подключает Tailwind CSS и Alpine.js на указанных страницах
     */
    public function enqueue_tailwind_alpine()
    {
        global $wp;
        $current_slug = trailingslashit($wp->request);

        if (in_array(rtrim($current_slug, '/'), $this->tailwind_alpine_slugs)) {
            wp_enqueue_style('tailwindcss', 'https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css', [], null);
            wp_enqueue_script('alpinejs', 'https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js', [], null, true);
        }
    }
    /**
     * Активация плагина.
     * Сбрасывает правила перезаписи WordPress.
     *
     * @return void
     */
    public static function activate()
    {
        $default_options = array(
            'use_cdn' => true
        );
        add_option('custom_url_router_settings', $default_options);

        $instance = new self();
        $instance->register_routes();
        flush_rewrite_rules();
    }
    /**
     * Деактивация плагина.
     * Сбрасывает правила перезаписи WordPress.
     *
     * @return void
     */
    public static function deactivate()
    {
        flush_rewrite_rules();
    }

    public function get_redirects()
    {
        return $this->redirects;
    }
}
