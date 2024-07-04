$(document).ready(function () {
    var $redirectTable = $('.wp-list-table');
    var redirectCount = $redirectTable.find('tbody tr').length;

    $('#add-redirect').on('click', function (e) {
        e.preventDefault();
        var newIndex = redirectCount++;

        var newRow = '<tr>' +
            '<td><input type="text" name="' + customUrlRouterData.optionName + '[redirects][' + newIndex + '][from]" class="regular-text"></td>' +
            '<td><input type="text" name="' + customUrlRouterData.optionName + '[redirects][' + newIndex + '][to]" class="regular-text"></td>' +
            '<td><button type="button" class="button-secondary remove-redirect" data-index="' + newIndex + '">Удалить</button></td>' +
            '</tr>';

        $redirectTable.find('tbody').append(newRow);
    });

    $(document).on('click', '.remove-redirect', function (e) {
        e.preventDefault();
        $(this).closest('tr').remove();
    });
});