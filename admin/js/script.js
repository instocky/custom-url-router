(function ($) {
    'use strict';

    $(document).ready(function () {
        console.log('Custom URL Router admin script loaded');
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
    });

})(jQuery);