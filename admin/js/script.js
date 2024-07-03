(function ($) {
    'use strict';

    $(document).ready(function () {
        console.log('Custom URL Router admin script loaded 5000');
        
        // Переключение между вкладками
        $('.custom-url-router-menu-item a').on('click', function (e) {
            e.preventDefault();
            var target = $(this).attr('href');

            $('.custom-url-router-menu-item').removeClass('active');
            $(this).parent().addClass('active');

            $('.custom-url-router-section').hide();
            $(target).show();
        });

        // Показываем первый раздел по умолчанию
        $('.custom-url-router-menu-item:first-child a').click();

        // Обработка добавления редиректа
        var redirectCount = $('.redirect-row').length;

        $('button[name="add_redirect"]').on('click', function (e) {
            e.preventDefault();
            var newIndex = redirectCount++;

            $.post(ajaxurl, {
                action: 'add_redirect_field',
                index: newIndex,
                _wpnonce: customUrlRouterData.nonce // Предполагается, что nonce передается через localize_script
            }, function (response) {
                if (response.success) {
                    $('#custom-url-router-redirects').append(response.data);
                }
            });
        });

        // Обработка удаления редиректа
        $(document).on('click', '.remove-redirect', function (e) {
            e.preventDefault();
            $(this).closest('.redirect-row').remove();
        });
    });
})(jQuery);