window.ModalList = [];
nested = {
    links: ".edit_link, .view_link, .edit_link, .delete_link",
    init: function () {
        var self = this;
        $(self.links).on('click', self.showModal)
    },
    showModal: function () {
        var $this = $(this);
        var url = $this.attr('href');
        var title = $this.attr('title');
        var footer = '<button type="button" id="submitForm" class="btn pull-right btn-success"><i class="fa fa-save" aria-hidden="true"></i> Save</button>';
        ajaxModal(url, {}, title, footer, function () {
            $('#submitForm').on('click',function () {
                $('#commentBox .modal-body form').submit();
            });
            $('#commentBox .modal-body').find('form').on('submit',function () {
                var $form = $(this);
                var url = $form.attr('action');
                var data = $form.serializeArray();
                getRequest(url, data, function (result) {
                    if (result.result == "ok") {
                        notify('success', "Données enregistrer");
                        $('#commentBox').modal('hide');
                        $('#commentBox').remove();
                        setTimeout(location.reload.bind(location), 1000);
                    }else{
                        notify('danger', "Données non enregistrer");
                    }
                });
                return false;
            });
        }, 600, 'commentBox');


        return false;
    }
};
$(document).ready(function () {
    nested.init();
});

