// $(document).ready(function () {
//     var $redirectTable = $('.wp-list-table');
//     var redirectCount = $redirectTable.find('tbody tr').length;

//     $('#add-redirect').on('click', function (e) {
//         e.preventDefault();
//         var newIndex = redirectCount++;

//         var newRow = '<tr>' +
//             '<td><input type="text" name="' + customUrlRouterData.optionName + '[redirects][' + newIndex + '][from]" class="regular-text"></td>' +
//             '<td><input type="text" name="' + customUrlRouterData.optionName + '[redirects][' + newIndex + '][to]" class="regular-text"></td>' +
//             '<td><button type="button" class="button-secondary remove-redirect" data-index="' + newIndex + '">Удалить</button></td>' +
//             '</tr>';

//         $redirectTable.find('tbody').append(newRow);
//     });

//     $(document).on('click', '.remove-redirect', function (e) {
//         e.preventDefault();
//         $(this).closest('tr').remove();
//     });
// });

submitNewRedirect() {
    // Валидация
    if (!this.newRedirect.from || !this.newRedirect.to) {
        this.message = { type: 'error', text: 'Оба поля должны быть заполнены' };
        return;
    }

    // Подготовка данных
    const data = new FormData();
    data.append('action', 'add_new_redirect');
    data.append('from', this.newRedirect.from);
    data.append('to', this.newRedirect.to);
    data.append('nonce', customUrlRouterData.nonce); // Предполагается, что nonce передан из PHP

    // Установка состояния загрузки
    this.isLoading = true;
    this.message = '';

    // AJAX запрос
    fetch(ajaxurl, {
        method: 'POST',
        credentials: 'same-origin',
        body: data
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            this.message = { type: 'success', text: 'Редирект успешно добавлен' };
            // Очистка формы
            this.newRedirect = { from: '', to: '' };
            // Здесь можно добавить код для обновления списка редиректов
        } else {
            this.message = { type: 'error', text: data.data || 'Произошла ошибка при добавлении редиректа' };
        }
    })
    .catch(error => {
        console.error('Error:', error);
        this.message = { type: 'error', text: 'Произошла ошибка при отправке запроса' };
    })
    .finally(() => {
        this.isLoading = false;
    });
}