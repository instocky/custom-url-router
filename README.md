# Custom URL Router

## Описание
Custom URL Router - это плагин WordPress, который обеспечивает гибкую маршрутизацию URL с учетом регистра символов. Плагин позволяет создавать кастомные URL-маршруты, сохраняя при этом совместимость с системой WordPress.

## Основные возможности
- Создание пользовательских URL-маршрутов с сохранением желаемого регистра символов
- Автоматическое перенаправление на URL с правильным регистром
- Гибкая система редиректов, настраиваемая через массив

## Использование
После активации плагин автоматически обрабатывает следующие маршруты:
- `parent/slugRegistry` -> `parent/slugregistry`
- `parent/eduStandart` -> `parent/edustandart`

Плагин также выполняет настроенные редиректы, например:
- `/parent/` -> `/parent/slugRegistry/`

## Настройка
Для добавления новых маршрутов отредактируйте массив `$custom_routes` в файле `custom-url-router.php`:

```php
private static $custom_routes = [
    'desired/URL' => 'corresponding/wordpress-slug',
];
private static $redirects = [
    'from/url/' => 'to/url/',
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