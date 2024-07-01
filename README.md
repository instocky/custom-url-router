# Custom URL Router

## Описание
Custom URL Router - это плагин WordPress, который обеспечивает гибкую маршрутизацию URL с учетом регистра символов и позволяет подключать Tailwind CSS и Alpine.js на определенных страницах.

## Основные возможности
- Создание пользовательских URL-маршрутов с сохранением желаемого регистра символов
- Автоматическое перенаправление на URL с правильным регистром
- Гибкая система редиректов, настраиваемая через массив
- Выборочное подключение Tailwind CSS и Alpine.js на указанных страницах

## Установка
1. Загрузите папку `custom-url-router` в директорию `/wp-content/plugins/`.
2. Активируйте плагин через меню 'Плагины' в WordPress.
3. Плагин автоматически применит необходимые правила перезаписи URL.

## Использование
После активации плагин автоматически обрабатывает следующие маршруты:
- `parent/slugRegistry` -> `parent/slugregistry`
- `parent/eduStandart` -> `parent/edustandart`

Плагин также выполняет настроенные редиректы, например:
- `/parent/` -> `/parent/slugRegistry/`

Tailwind CSS и Alpine.js подключаются только на страницах, указанных в массиве `$tailwind_alpine_slugs`.

## Настройка
### Маршруты
Для добавления новых маршрутов отредактируйте массив `$custom_routes` в файле `custom-url-router.php`.

### Редиректы
Для настройки редиректов используйте массив `$redirects`.

### Подключение Tailwind и Alpine
Для указания страниц, на которых нужно подключить Tailwind CSS и Alpine.js, отредактируйте массив `$tailwind_alpine_slugs`.

```php
private static $custom_routes = [
    'desired/URL' => 'corresponding/wordpress-slug',
];
private static $redirects = [
    'from/url/' => 'to/url/',
];
private static $tailwind_alpine_slugs = [
            'parent/slugRegistry',
            'parent/edustandart',
            // Добавьте другие слаги по необходимости
        ];
```

Пример
```php
private static $redirects = [
    'parent/' => 'parent/slugRegistry/',
    'old-page/' => 'new-page/',
    'category/old-name/' => 'category/new-name/',
];
```

## Требования
- WordPress 4.7 или выше
- PHP 7.0 или выше

## Поддержка
При возникновении проблем или вопросов создайте issue в репозитории проекта.

## Лицензия
Этот плагин распространяется под лицензией GPL v2 или более поздней версии.

## Автор
IC