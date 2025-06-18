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
    modal = '<div class="modal fade" id="modaledit-modal" role="dialog"><div class="modal-dialog" role="document"><div class="modal-content"></div></div></div>';
    $(modal).appendTo($('body'));
});


// Enable caching of AJAX responses
$.ajaxSetup({
    cache: true
});

var latestFormRoute;

// form staff

function showForm(route, params, modalParams, silent = false, closePrevent = true, autosave = false, sizeClass = 'modal-lg') {


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

    if (silent !== true)
        info('Loading...', 0);

    if (!modalParams)
        modalParams = {keyboard: false, backdrop: 'static'};

    const modalClass = 'modal-dialog';

    $.ajax({
        url: route,
        data: data,
        success: function (response) {
            $('#modaledit-modal > div').attr('class', '');
            $('#modaledit-modal > div').addClass(modalClass).addClass(sizeClass);
            $('#modaledit-modal div.modal-content').html('');
            $('#modaledit-modal').modal(modalParams);
            $('#modaledit-modal div.modal-content').html(response);
            $('.dropdown-toggle').dropdown();
            if (autosave === true) {
                autosaveRestore();
            }
            if (closePrevent === true)
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

    if (confirm('Do you want to delete it?')) {
        $.ajax({
            data: {id: id},
            method: 'DELETE',
            url: route,
            dataType: 'json',
            success: function (response) {
                cancelModalEditSilent();
                $.pjax.reload({container: response.container});
                info(response.message, 1);
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
    info('Editing is canceled.', 0);
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

$(document).on('click', '.modaledit-disable', function () {
    cancelModalEdit();
    return false;
});

$(document).on('click', '.modaledit-disable-silent', function () {
    cancelModalEditSilent();
    return false;
});

$(document).on('submit', 'form.modaledit-form', function (event) {
    console.log('1');
    event.preventDefault();

    const formElement = this;
    const form = $(formElement);
    const question = form.data('question');

    if (question && !confirm(question)) {
        return false;
    }

    setTimeout(function () {
        const data = new FormData(formElement);
        info('Sending data...', 0);
        form.find('button[type="submit"]').attr('disabled', 'true');

        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            data: data,
            success: function (response) {
                form.find('button[type="submit"]').removeAttr('disabled');
                console.log(response)
                autosaveClean();
                offPageLeaving();
                if (response.substring(0, 8) == '<script>' && response.substring(response.length - 9) == '</script>') {
                    var script = response.substring(8, response.length - 9);
                    console.log(script);
                    eval(script);
                    return;
                }
                $('#modaledit-modal div.modal-content').html(response);

            },
            error: function (response, textStatus, xhr) {
                form.find('button[type="submit"]').removeAttr('disabled');
                if (xhr.status > 399) {
                    processError(response);
                }
            }
        });

        return false;
    }, 500);
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
        if (confirm('Restore previous form state?')) {
            $.each(data.split('&'), function (index, elem) {
                var vals = elem.split('=');
                var field = $("[name='" + decodeURIComponent(vals[0]) + "']");
                console.log(field);
                var value = decodeURIComponent(vals[1]);
                if (field.next('.note-editor').length > 0) {
                    console.log(value);
                    setTimeout(function () {
                        field.summernote('reset');
                        field.summernote('pasteHTML', value);
                    }, 400)

                } else if (field.parents('.floor12-files-widget-block').length == 0)
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


function modalFixer() {
    let modal = $('#modaledit-modal');
    if (modal.length > 0 && modal.hasClass('in') && !$('body').hasClass('modal-open'))
        $('body').addClass('modal-open');
}

setInterval(function () {
    modalFixer();
}, 1000)
