/**
 * Created by salah on 13/08/2018.
 */
$(document).ready(function () {

    $('input[id$=_image]').on('change', function () {
        var img = $(this).val();
        if (img != '' || img != null) {
            $(this).closest(".sonata-ba-collapsed-fields").append('<div class="form-group"><img class="img-responsive" src="/' + img + '"> </div>');
        }
    });

    $('.iframe-btn').fancybox({
        'width': 880,
        'height': '80%',
        'type': 'iframe',
        'autoScale': false
    });
    var $inputs = $('input[id$=_image]');
    var id = $inputs.attr('id');
    var html = htmlbt(id);
    $inputs.after(html);
    $inputs.trigger("change");
    tinymce.init({
        selector: 'textarea[id$=_body]',
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen ",
            "insertdatetime media table contextmenu paste moxiemanager responsivefilemanager filemanager "
        ],
        toolbar: "insertfile formatselect | bold italic underline | bullist numlist outdent indent | alignleft aligncenter alignright alignjustify | link image | forecolor backcolor table",
        autosave_ask_before_unload: false,
        relative_urls: false,
        min_height: 600,
        document_base_url: 'https://cuisinezavecdjouza.fr',
        // theme_advanced_fonts: "Arial=arial,helvetica,sans-serif;Arial Black=arial black;Courier New=courier new,courier,monospace;Times New Roman=times new roman;Georgia=georgia;Comic Sans MS=comic sans ms;Book Antiqua=book antiqua;Tahoma=tahoma;Trebuchet MS=trebuchet ms;",
        max_height: 600,
        min_height: 160,
        //link_list: Routing.generate('jsonlinks'),
        height: 180
    });

});
