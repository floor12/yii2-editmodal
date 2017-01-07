/**
 * Created by floor12 on 22.12.2016.
 */

//prepearing page: make modal block and block for notifys

$(document).ready(function () {
    infoBlock = $('<div>').attr('id', 'info-list').appendTo($('body'));

    modal = '<div class="modal fade" id="modaledit-modal" tabindex="-1" role="dialog"><div class="modal-dialog modal-lg" role="document"><div class="modal-content"></div></div></div>';
    $(modal).appendTo($('body'));
});


// Enable caching of AJAX responses
$.ajaxSetup({
    cache: true
});

// notifys
var infos = [];

function info(content, type) {
    infos.push({
        content: content,
        type: type
    })

    timeout = 1000
    glyph = 'fa-info-circle'
    info_class = 'info-object-info';
    if (type == 1) {
        info_class = 'info-object-success';
        timeout = 3000;
        glyph = 'fa-check-circle';

    }
    if (type == 2) {
        info_class = 'info-object-error';
        timeout = 3000;
        glyph = 'fa-exclamation-triangle';

    }

    icon = "<i class='fa " + glyph + "' aria-hidden='true'></i> ";

    var info = $('<div>').addClass('info-object').addClass(info_class).html(icon + content)
    info.appendTo($('#info-list'));
    setTimeout(function () {
        info.fadeOut(500);
    }, timeout);

}

// form staff

function showForm(route, params) {
    if (!params)
        data = {id: 0};
    else {
        if ($.isNumeric(params))
            data = {id: params};
        else
            data = params;
    }
    info('Загрузка формы...', 0)
    $.get('/' + route, data, function (response) {
        $('#modaledit-modal div.modal-content').html("");
        $('#modaledit-modal').modal({backdrop: 'static'});
        $('#modaledit-modal div.modal-content').html(response);
        info('Форма загружена.', 0)
    })
}

function deleteItem(route, id) {
    if (confirm("Вы уверены что хотите удалить?"))
        $.ajax({
            data: {id: id},
            method: 'DELETE',
            url: '/' + route,
            success: function (response) {
                $.pjax.reload({container: "#items"});
                info(response, 1)
            },
            error: function () {
                info('Ошибка удаления объекта.', 2)
            }
        })

}

function hideFormModal() {
    $('#modaledit-modal').modal('hide');
}

function cancelModalEdit() {
    info('Отмена редактирования. Изменения не сохранены.', 0);
    hideFormModal();

}

$(document).on('click', 'a.modaledit-disable', function () {
    cancelModalEdit()
    return false;
})

$(document).on('submit', 'form.modaledit-form', function () {
    form = $(this);
    data = form.serialize();
    info('Отправка данных...', 0)
    $.post($(this).attr('action'), data, function (response) {
        $('#modaledit-modal div.modal-content').html("");
        $('#modaledit-modal div.modal-content').html(response);
    })
    return false;
})
