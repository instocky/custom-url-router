<?php
/*
Plugin Name: Custom URL Router
Description: Handles custom URL routes with case sensitivity
Version: 0.0.701.1125
Author: IC
*/

if (!class_exists('IC_CustomURLRouter')) {
    class IC_CustomURLRouter
    {
        /**
         * Массив кастомных маршрутов.
         * Ключ - желаемый URL, значение - соответствующий WordPress slug.
         *
         * @var array
         */
        private static $custom_routes = [
            'parent/slugRegistry' => 'parent/slugregistry',
            'parent/eduStandart' => 'parent/edustandart',
        ];

        /**
         * Массив редиректов.
         * Ключ - исходный URL, значение - целевой URL.
         *
         * @var array
         */
        private static $redirects = [
            'parent/' => 'parent/slugRegistry/',
            // Добавьте здесь другие редиректы при необходимости
        ];

        /**
         * Массив слагов страниц, где нужно подключить Tailwind и Alpine.
         *
         * @var array
         */
        private static $tailwind_alpine_slugs = [
            'parent/slugRegistry',
            'parent/edustandart',
            // Добавьте другие слаги по необходимости
        ];

        /**
         * Инициализация плагина.
         * Подключает все необходимые хуки.
         *
         * @return void
         */
        public static function init()
        {
            add_action('init', [self::class, 'register_routes']);
            add_filter('request', [self::class, 'handle_custom_routes']);
            add_action('template_redirect', [self::class, 'redirect_incorrect_case']);
            add_action('template_redirect', [self::class, 'handle_redirects']);
            add_action('wp_enqueue_scripts', [self::class, 'enqueue_tailwind_alpine']);
        }

        /**
         * Регистрирует правила перезаписи для кастомных маршрутов.
         *
         * @return void
         */
        public static function register_routes()
        {
            foreach (self::$custom_routes as $custom_route => $wp_route) {
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
        public static function handle_custom_routes($query_vars)
        {
            if (isset($query_vars['custom_route'])) {
                $query_vars['pagename'] = self::$custom_routes[$query_vars['custom_route']];
            }
            return $query_vars;
        }

        /**
         * Выполняет редирект на URL с правильным регистром.
         *
         * @return void
         */
        public static function redirect_incorrect_case()
        {
            global $wp;
            $current_url = trailingslashit(home_url($wp->request));

            foreach (self::$custom_routes as $custom_route => $wp_route) {
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
        public static function handle_redirects()
        {
            global $wp;
            $current_url = trailingslashit($wp->request);

            foreach (self::$redirects as $from => $to) {
                if ($current_url === $from) {
                    wp_redirect(home_url($to), 301);
                    exit;
                }
            }
        }

        /**
         * Подключает Tailwind CSS и Alpine.js на указанных страницах
         */
        public static function enqueue_tailwind_alpine()
        {
            global $wp;
            $current_slug = trailingslashit($wp->request);

            if (in_array(rtrim($current_slug, '/'), self::$tailwind_alpine_slugs)) {
                wp_enqueue_style('tailwindcss', 'https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css', [], null);
                wp_enqueue_script('alpinejs', 'https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js', [], null, true);
            }
        }

        /**
         * Сбрасывает правила перезаписи WordPress.
         * Вызывается при активации плагина.
         *
         * @return void
         */
        public static function flush_rules()
        {
            self::register_routes();
            flush_rewrite_rules();
        }
    }

    IC_CustomURLRouter::init();
    register_activation_hook(__FILE__, [IC_CustomURLRouter::class, 'flush_rules']);
    register_deactivation_hook(__FILE__, 'flush_rewrite_rules');
}
