/**
 * Created by floor12 on 22.12.2016.
 */


//page leaving prevent functioncs

function onPageLeaving() {
    window.onbeforeunload = function () {
        return "Вы не сохранили форму."
    }
}

function offPageLeaving() {
    window.onbeforeunload = function () {
    }
}

//prepearing page: make modal block and block for notifys

$(document).ready(function () {
    infoBlock = $('<div>').attr('id', 'info-list').appendTo($('body'));

    modal = '<div class="modal fade" id="modaledit-modal" role="dialog"><div class="modal-dialog modal-lg" role="document"><div class="modal-content"></div></div></div>';
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

    if (route.substring(0, 1) != '/')
        route = '/' + route;


    if (!params)
        data = {id: 0};
    else {
        if ($.isNumeric(params))
            data = {id: params};
        else
            data = params;
    }
    info('Загрузка формы...', 0)

    $.ajax({
        url: route,
        data: data,
        success: function (response) {
            $('#modaledit-modal div.modal-content').html("");
            $('#modaledit-modal').modal({backdrop: 'static'});
            $('#modaledit-modal div.modal-content').html(response);
            onPageLeaving();
        },
        error: function (response) {
            processError(response)
        }
    });

}

function deleteItem(route, id) {

    if (route.substring(0, 1) != '/')
        route = '/' + route;

    if (confirm("Вы уверены что хотите удалить?"))
        $.ajax({
            data: {id: id},
            method: 'DELETE',
            url: route,
            success: function (response) {
                cancelModalEditSilent();
                $.pjax.reload({container: "#items"});
                info(response, 1);
            },
            error: function (response) {
                processError(response)
            }

        })
    return false;
}


function hideFormModal() {
    $('#modaledit-modal').modal('hide');
    $('body').removeClass('modal-open');                //bugfix
    $('.modal-backdrop').fadeOut(150, function () {     //bugfix
        $('.modal-backdrop').remove();                  //bugfix
    })                                                  //bugfix
    $('#modaledit-modal div.modal-content').html("");
}

function cancelModalEdit() {
    onPageLeaving();
    offPageLeaving();
    info('Отмена редактирования. Изменения не сохранены.', 0);
    hideFormModal();

}

function cancelModalEditSilent() {
    offPageLeaving();
    hideFormModal();

}

$(document).on('click', 'a.modaledit-disable', function () {
    cancelModalEdit()
    return false;
})

$(document).on('click', 'a.modaledit-disable-silent', function () {
    cancelModalEditSilent()
    return false;
})

$(document).on('submit', 'form.modaledit-form', function () {
    form = $(this);
    data = new FormData(this);
    info('Отправка данных...', 0)

    $.ajax({
        url: $(this).attr('action'),
        method: $(this).attr('method'),
        enctype: 'multipart/form-data',
        processData: false,
        contentType: false,
        data: data,
        success: function (response) {
            $('#modaledit-modal div.modal-content').html("");
            $('#modaledit-modal div.modal-content').html(response);
            offPageLeaving();
        },
        error: function (response) {
            processError(response)
        }
    });

    return false;
})

function processError(response) {
    if (typeof(response.responseJSON) === 'object') {
        info(response.status + ': ' + response.responseJSON.message, 2)
        return true;
    }

    if (response.responseText.length > 5) {

        if (response.responseText.length < 40) {
            info(response.responseText, 2);
            return true;
        }


        if (response.responseText.length > 40) {
            matches = response.responseText.match(/with message (.+)/);

            if (!matches)
                matches = response.responseText.match(/\): (.+)/);

            if (matches) {
                info(response.status + ': ' + matches[1].replace("&#039;", ""), 2);
                return true;
            }
        }

    }

    info(response.status + ': ' + response.statusText, 2);


}