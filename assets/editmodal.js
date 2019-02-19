/**
 * Created by floor12 on 22.12.2016.
 */


//page leaving prevent functions

function onPageLeaving() {
    window.onbeforeunload = function () {
        return false;
    };
}

function offPageLeaving() {
    window.onbeforeunload = function () {
    };
}

//prepearing page: make modal block

$(document).ready(function () {
    modal = '<div class="modal fade" id="modaledit-modal" role="dialog"><div class="modal-dialog modal-lg" role="document"><div class="modal-content"></div></div></div>';
    $(modal).appendTo($('body'));
});


// Enable caching of AJAX responses
$.ajaxSetup({
    cache: true
});

var latestFormRoute;

// form staff

function showForm(route, params, modalParams) {


    if (route.substring(0, 1) != '/') {
        route = '/' + route;
    }

    if (!params) {
        data = {id: 0};
    } else {
        if ($.isNumeric(params)) {
            data = {id: params};
        } else {
            data = params;
        }
    }

    latestFormRoute = route + encodeURIComponent(params);


    info('Загрузка формы...', 0);

    if (!modalParams)
        modalParams = {keyboard: false, backdrop: 'static'};

    $.ajax({
        url: route,
        data: data,
        success: function (response) {
            if ($('#modaledit-modal div.modal-dialog').hasClass('fullscreened'))
                $('#modaledit-modal div.modal-dialog').removeClass('fullscreened');
            $('#modaledit-modal div.modal-content').html('');
            $('#modaledit-modal').modal(modalParams);
            $('#modaledit-modal div.modal-content').html(response);
            $('.dropdown-toggle').dropdown();
            autosaveRestore();
            onPageLeaving();
        },
        error: function (response) {
            processError(response);
        }
    });

}

function deleteItem(route, id) {

    if (route.substring(0, 1) != '/') {
        route = '/' + route;
    }

    if (confirm('Вы уверены что хотите удалить?')) {
        $.ajax({
            data: {id: id},
            method: 'DELETE',
            url: route,
            success: function (response) {
                cancelModalEditSilent();
                $.pjax.reload({container: '#items'});
                info(response, 1);
                $('.dropdown-toggle').dropdown();
            },
            error: function (response) {
                processError(response);
            }
        });
    }
    return false;
}


function hideFormModal() {
    autosaveClean();
    $('#modaledit-modal').modal('hide');
    $('body').removeClass('modal-open'); //bugfix
    $('.modal-backdrop').fadeOut(150, function () {
        $('.modal-backdrop').remove();
    }); //bugfix
    $('#modaledit-modal div.modal-content').html('');
    $('.dropdown-toggle').dropdown();
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

function editModalFullscreen() {
    if ($('#modaledit-modal div.modal-dialog').hasClass('fullscreened'))
        $('#modaledit-modal div.modal-dialog').removeClass('fullscreened');
    else
        $('#modaledit-modal div.modal-dialog').addClass('fullscreened');
}

function editModalFullscreenOpen() {
    if (!$('#modaledit-modal div.modal-dialog').hasClass('fullscreened'))
        $('#modaledit-modal div.modal-dialog').addClass('fullscreened');
}

function editModalFullscreenClose() {
    if ($('#modaledit-modal div.modal-dialog').hasClass('fullscreened'))
        $('#modaledit-modal div.modal-dialog').removeClass('fullscreened');
}

$(document).on('click', 'a.modaledit-disable', function () {
    cancelModalEdit();
    return false;
});

$(document).on('click', 'a.modaledit-disable-silent', function () {
    cancelModalEditSilent();
    return false;
});

$(document).on('submit', 'form.modaledit-form', function () {
    form = $(this);
    data = new FormData(this);
    info('Отправка данных...', 0);

    $.ajax({
        url: $(this).attr('action'),
        method: $(this).attr('method'),
        enctype: 'multipart/form-data',
        processData: false,
        contentType: false,
        data: data,
        success: function (response) {
            $('#modaledit-modal div.modal-content').html('');
            $('#modaledit-modal div.modal-content').html(response);
            autosaveClean();
            offPageLeaving();
        },
        error: function (response) {
            processError(response);
        }
    });

    return false;
});

function processError(response) {
    if (typeof (response.responseJSON) === 'object') {
        info(response.status + ': ' + response.responseJSON.message, 2);
        return true;
    }

    if (response.responseText.length > 5) {

        if (response.responseText.length < 40) {
            info(response.responseText, 2);
            return true;
        }

        if (response.responseText.length > 40) {
            matches = response.responseText.match(/with message (.+)/);

            if (!matches) {
                matches = response.responseText.match(/\): (.+)/);
            }

            if (matches) {
                info(response.status + ': ' + matches[1].replace('&#039;', ''), 2);
                return true;
            }
        }

    }
    info(response.status + ': ' + response.statusText, 2);
}


function autosave() {
    if ($('form.modaledit-form').length == 0)
        return;
    var data = $('form.modaledit-form').serialize();
    localStorage.setItem(latestFormRoute, data);
}

function autosaveRestore() {
    data = localStorage.getItem(latestFormRoute);
    if (data !== null) {
        if (confirm('Восстановить предыдущее значение формы?')) {
            $.each(data.split('&'), function (index, elem) {
                var vals = elem.split('=');
                var field = $("[name='" + decodeURIComponent(vals[0]) + "']");
                var value = decodeURIComponent(vals[1]);
                if (field.next('.note-editor').length > 0) {
                    setTimeout(function () {
                        field.summernote('reset');
                        field.summernote('pasteHTML', value);
                    }, 100)

                } else
                    field.val(value);
            });
        }
    }
}

function autosaveClean() {
    localStorage.removeItem(latestFormRoute);
}

setInterval(function () {
    autosave();
}, 3000);