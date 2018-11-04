/**
 * Created by salah on 03/11/18.
 */
function getRequest(url, _data, success_function, params, extraParams) {
    params = params || {};
    var showloader = typeof(params.showloader) != "undefined" ? params.showloader : true;
    if (showloader) {
        showLoader(true);
    }
    var parameters = {
        async: true,
        url: url,
        type: params.type || "POST",
        data: _data,
        success: function (data) {
            if (data === "is_not_logged") {
                successDialog("ICE", Translator.trans("Session expired", {}, 'javascript')+"! "+Translator.trans("Please login again", {}, 'javascript')+".", BootstrapDialog.TYPE_WARNING, undefined, function () {
                    window.location.href = "login";
                });
            } else {
                if (typeof (success_function) == "function") {
                    success_function(data);
                }
            }
        },
        error: function (xhr) {
            errorDialog(Translator.trans("Error contacting server", {}, 'javascript'), Translator.trans("Error encountered", {}, 'javascript')+' : ' + xhr.responseText);
        },
        complete: function () {
            if (showloader) {
                showLoader(false);
            }
        }
    };
    if (typeof(extraParams) == "object") {
        $.extend(parameters, extraParams);
    }
    $.ajax(parameters);
}
function showLoader(etat) {
    window.loaderCount = window.loaderCount || 0;
    window.loaderCount += etat ? 1 : -1;
    if (window.loaderCount < 0) {
        window.loaderCount = 0;
    }
    $('#page-loader').toggle(window.loaderCount > 0);
}
function ajaxModal(url, data, title, footer, shown_callback, width, id, close_bn_title, hidden_callback, no_footer) {
    var $newmodal = $('#modalDialog');
    if (id) {
        window.ModalList.push(id);
        $newmodal = $('#modalDialog').clone().removeAttr("id");
        $newmodal.attr("id", id);
        $newmodal.appendTo("body");
    } else {
        window.ModalList.push("modalDialog");
    }
    close_bn_title = close_bn_title || "Close";
    var modalWidth = width || 1000;
    $('.modal-title', $newmodal).html(title);
    if (!no_footer) {
        $('.modal-footer', $newmodal).html('<button type="button" class="btn pull-right btn-up-close" data-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i> ' + close_bn_title + '</button>');
        $('.modal-footer', $newmodal).append(footer);
    } else {
        $('.modal-footer', $newmodal).html("");
    }
    $('.modal-dialog', $newmodal).width(modalWidth);
    getRequest(url, data, function (result) {
        $('.modal-body', $newmodal).html(result);
        $('.form-actions').remove()
        /* modal dragging and resizing */
        $newmodal.modal('show').draggable({handle: ".modal-header"});
        //
        if ($('.sonata-ba-list-field-select', $newmodal).length) {
            $('.sonata-ba-list-field-select', $newmodal).remove();
        }
        $newmodal.on('hidden.bs.modal', function () {
            if (id) {
                removeElementFromModalList($(this).attr('id'));
                //window.ModalList.remove();
                $(this).remove();
            }
            if (typeof hidden_callback == "function") {
                hidden_callback();
            }
        }).on('shown.bs.modal', function () {
            /* modal dragging and resizing */
            var $content = $(".modal-content", $newmodal);
            var minHeight = $content.height();
            $content.resizable({minWidth: modalWidth, minHeight: minHeight + 2});

        });
        /*$newmodal.on('loaded.bs.modal', function () {
         shown_callback();
         });*/
        if (shown_callback) {
            shown_callback();
        }
        $.each($('#' + id + " .fa-info-circle"), function (k, el) {
            $(el).closest('a').show();
        });
    });

}

function notify(type, message) {
    $.notify(message, type);

}