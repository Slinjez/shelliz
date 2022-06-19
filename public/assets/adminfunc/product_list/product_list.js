// $('#result-table').DataTable({
//     language: {
//         responsive:"true",
//         searchPlaceholder: 'Search...',
//         sSearch: '',
//         lengthMenu: '_MENU_',
//     }
// });
var icons = Quill.import('ui/icons');
icons['bold'] = '<i class="fa fa-bold" aria-hidden="true"><\/i>';
icons['italic'] = '<i class="fa fa-italic" aria-hidden="true"><\/i>';
icons['underline'] = '<i class="fa fa-underline" aria-hidden="true"><\/i>';
icons['strike'] = '<i class="fa fa-strikethrough" aria-hidden="true"><\/i>';
icons['list']['ordered'] = '<i class="fa fa-list-ol" aria-hidden="true"><\/i>';
icons['list']['bullet'] = '<i class="fa fa-list-ul" aria-hidden="true"><\/i>';
icons['link'] = '<i class="fa fa-link" aria-hidden="true"><\/i>';
icons['image'] = '<i class="fa fa-image" aria-hidden="true"><\/i>';
icons['video'] = '<i class="fa fa-film" aria-hidden="true"><\/i>';
icons['code-block'] = '<i class="fa fa-code" aria-hidden="true"><\/i>';
var toolbarOptions = [
    [{
        'header': [1, 2, 3, 4, 5, 6, false]
    }],
    ['bold', 'italic', 'underline', 'strike'],
    [{
        'list': 'ordered'
    }, {
        'list': 'bullet'
    }],
    ['link', 'image', 'video']
];
var quill = new Quill('#description', {
    modules: {
        toolbar: toolbarOptions
    },
    theme: 'snow'
});
get_policy_types();
function get_policy_types() {
    var b = {
        'token': localStorage.token,
    };
    $.ajax({
        type: "post",
        url: "/get-policy-type-list",
        data: b,
        dataType: "json",
        beforeSend: function () {
            //pass
        },
        success: function (e) {

            if (e.status == 'ok') {

                var resp_data = e.data;
                var country_list_data = resp_data.country_list;


                let s = '';
                $.each(country_list_data, function (key, val) {
                    s += '<option value="' + val.record_id + '">' + val.product_type_name + "</option>";
                });
                $('#status-select').append(s);
                $('#status-select').select2({
                    selectOnClose: !0
                });

            } else {
                //$('#service-count').html(e.service_count);
            }
        },
        complete: function () {
            //pass
        }
    });

}

// $("#prod-formx").submit(function (c) {
//     var product_name = $("#product-name").val();
//     var product_type = $("#status-select").val();
//     var description = $("#description").val();

//     let can_submit = true;

//     if (product_name == '') {
//         let title = "Invalid Entry";
//         let message = "Kindly enter product name"
//         let tostr_type = "warning";
//         $('#product-name').addClass('is-invalid state-invalid');
//         call_toast(title, message, tostr_type)
//         can_submit = false;
//     }
//     if (product_type == '') {
//         let title = "Invalid Entry";
//         let message = "Kindly enter product type"
//         let tostr_type = "warning";
//         $('#status-select').addClass('is-invalid state-invalid');
//         call_toast(title, message, tostr_type)
//         can_submit = false;
//     }
//     if (description == '') {
//         let title = "Invalid Entry";
//         let message = "Kindly enter product description"
//         let tostr_type = "warning";
//         $('#description').addClass('is-invalid state-invalid');
//         call_toast(title, message, tostr_type)
//         can_submit = false;
//     }

//     if (!can_submit) {
//         return false;
//     }

//     var b = {
//         "product_name": product_name,
//         "product_type": product_type,
//         "description": description,
//         'token': localStorage.token,
//     };

//     $.ajax({
//         type: "post",
//         url: "/save-product",
//         data: b,
//         dataType: "json",
//         beforeSend: function () {
//             $(".ajaxloader-modal").html('<div class="alert alert-info" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button><i class="fa fa-spinner fa-spin"></i> Please wait...</div>');
//             $(".ajaxloader-modal").css("visibility", "visible");
//             $(".removeMessages-modal").html("");
//             $(".removeMessages-modal").css("visibility", "hidden");
//             $('#form-submitter-modal').addClass('btn-loading');
//             $('.bot-load-modal').removeClass('hide-me');
//         },
//         success: function (e) {
//             if (e.status == 'ok') {
//                 $('.bot-load-modal').addClass('hide-me');
//                 $(".ajaxloader-modal").html("");
//                 $(".ajaxloader-modal").css("visibility", "hidden");
//                 $(".removeMessages-modal").css("visibility", "visible");
//                 $(".removeMessages-modal").html('<div class="alert alert-success" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button><i class="far fa-check-circle"></i> ' + e.messages + '</div>');

//                 get_profile_details();

//                 let title = "Done";
//                 let message = "Updated"
//                 let tostr_type = "info";
//                 call_toast(title, message, tostr_type)
//             } else {
//                 $('.bot-load-modal').addClass('hide-me');
//                 $(".ajaxloader-modal").html("");
//                 $(".ajaxloader-modal").css("visibility", "hidden");
//                 $(".removeMessages-modal").css("visibility", "visible");
//                 $(".removeMessages-modal").html('<div class="alert alert-warning" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button><i class="fas fa-exclamation-triangle"></i> ' + e.messages + '</div>')
//             }
//         },
//         complete: function () {
//             $('.bot-load-modal').addClass('hide-me');
//             $(".ajaxloader-modal").html("");
//             $(".ajaxloader-modal").css("visibility", "hidden");
//             $(".removeMessages-modal").css("visibility", "visible");
//             $('#form-submitter-modal').removeClass('btn-loading');
//         }
//     });
//     c.preventDefault()
// })


(Dropzone.options.myAwesomeDropzone = {
    paramName: "eventfiles",
    maxFilesize: 2,
    parallelUploads: 8,
    maxFiles: 1,
    autoProcessQueue: false,
    acceptedFiles: "image/*",
    addRemoveLinks: true,
    dictFileTooBig: "File is to big ({{filesize}}mb). Max allowed file size is {{maxFilesize}}mb",
    dictInvalidFileType: "Invalid File Type",
    dictCancelUpload: "Cancel",
    dictRemoveFile: "Remove this",
    dictMaxFilesExceeded: "Only {{maxFiles}} files are allowed",
    dictDefaultMessage: "Drop files here to upload. You can also just click here.",
    url: "/save-product",
    uploadMultiple: true,
    autoDiscover: false,
    accept: function (e, a) {
        "uda.jpg" == e.name ? a("Nah, you just didn't.") : a();
    },
    init: function () {
        var e = this;
        $("#form-submitter-modal").click(function (a) {
            if ((a.preventDefault(), a.stopPropagation(), e.getQueuedFiles().length > 0)) e.processQueue();
            else save_file_empties();
            e.processQueue();
        }),
            this.on("sending", function (e, a, s) {
                $(".removeRegMessages").html(""),
                    $(".ajaxloader-prof-pic").html('<div class="alert alert-info" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button><i class="fa fa-spinner fa-spin"></i> Please wait...</div>');
                $(".ajaxloader-prof-pic").css("visibility", "visible");
                $(".removeMessages-prof-pic").html("");
                $(".removeMessages-prof-pic").css("visibility", "hidden");
                $('#form-submitter-update-prof-pic').addClass('btn-loading');
                $('.bot-load-prof-pic').removeClass('hide-me');


                var update_record_id = $("#update_record_id").val();
                var product_name = $("#product-name").val();
                var product_type = $("#status-select").val();
                var description = quill.getText();
                

                let can_submit = true;
                if (update_record_id == '') {
                    update_record_id = 0;
                }
                if (product_name == '') {
                    can_submit = false;
                }
                if (product_type == '') {
                    can_submit = false;
                }
                if (description == '') {
                    can_submit = false;
                }
                console.log('can_submit',can_submit);
                if (!can_submit) {
                    return false;
                }


                var t = $("#prod-form").serializeArray();
                t.push({
                    name: "product_name",
                    value: product_name
                });
                t.push({
                    name: "product_type",
                    value: product_type
                });
                t.push({
                    name: "description",
                    value: description
                });
                t.push({
                    name: "token",
                    value: localStorage.token
                });
                t.push({
                    name: "update_record_id",
                    value: update_record_id
                });


                $.each(t, function (e, a) {
                    s.append(a.name, a.value);
                });
            }),
            this.on("success", function (e, a) {
                if (a.status == "ok") {
                    $('.bot-load-prof-pic').addClass('hide-me');
                    $(".ajaxloader-prof-pic").html("");
                    $(".ajaxloader-prof-pic").css("visibility", "hidden");
                    $(".removeMessages-prof-pic").css("visibility", "visible");
                    $(".removeMessages-prof-pic").html('<div class="alert alert-success" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button><i class="far fa-check-circle"></i> ' + a.messages + '</div>');
                    get_my_products();
                    
                    $('#new-product').modal('hide');
                    let title = "Done";
                    let message = a.messages;
                    let tostr_type = "success";    

                    call_toast(title, message, tostr_type);
                } else {
                    let title = "Invalid Entry";
                    let message = a.messages;
                    let tostr_type = "warning";
                    call_toast(title, message, tostr_type);
                    $('.bot-load-prof-pic').addClass('hide-me');
                    $(".ajaxloader-prof-pic").html("");
                    $(".ajaxloader-prof-pic").css("visibility", "hidden");
                    $(".removeMessages-prof-pic").css("visibility", "visible");
                    $(".removeMessages-prof-pic").html('<div class="alert alert-warning" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button><i class="fas fa-exclamation-triangle"></i> ' + a.messages + '</div>')
                }


                Dropzone.forElement('#my-awesome-dropzone').removeAllFiles(true)
            });
            this.on("error", function (e, a) {
            Dropzone.forElement('#my-awesome-dropzone').removeAllFiles(true);

            $('.bot-load-prof-pic').addClass('hide-me');
            $(".ajaxloader-prof-pic").html("");
            $(".ajaxloader-prof-pic").css("visibility", "hidden");
            $(".removeMessages-prof-pic").css("visibility", "visible");
            $(".removeMessages-prof-pic").html('<div class="alert alert-warning" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button><i class="fas fa-exclamation-triangle"></i> ' + a.messages + '</div>')
        });
    },
});


function save_file_empties(c) {

    //var e = $("#prod-form").serializeArray();

    var product_name = $("#product-name").val();
    var product_type = $("#status-select").val();
    var description = quill.getText();
    var update_record_id = $("#update_record_id").val();


    let can_submit = true;

    if (update_record_id == '') {
        update_record_id = 0;
    }
    if (product_name == '') {
        can_submit = false;
    }
    if (product_type == '') {
        can_submit = false;
    }
    if (description == '') {
        can_submit = false;
    }

    console.log('can_submit2',can_submit);
    if (!can_submit) {
        return false;
    }


    var e = $("#prod-form").serializeArray();
    e.push({
        name: "update_record_id",
        value: update_record_id
    });
    e.push({
        name: "product_name",
        value: product_name
    });
    e.push({
        name: "product_type",
        value: product_type
    });
    e.push({
        name: "description",
        value: description
    });
    e.push({
        name: "token",
        value: localStorage.token
    });

    $.ajax({
        type: "post",
        url: "/save-product",
        data: e,
        dataType: "json",
        beforeSend: function () {
            $(".ajaxloader-prof-pic").html('<div class="alert alert-info" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button><i class="fa fa-spinner fa-spin"></i> Please wait...</div>');
            $(".ajaxloader-prof-pic").css("visibility", "visible");
            $(".removeMessages-prof-pic").html("");
            $(".removeMessages-prof-pic").css("visibility", "hidden");
            $('#form-submitter-update-prof-pic').addClass('btn-loading');
            $('.bot-load-prof-pic').removeClass('hide-me');
        },
        success: function (e) {
            if (e.status == 'ok') {
                
                let title = "Done";
                let message = e.messages;
                let tostr_type = "success";
                call_toast(title, message, tostr_type);
                get_my_products();
                $('#new-product').modal('hide');
                $('.bot-load-prof-pic').addClass('hide-me');
                $(".ajaxloader-prof-pic").html("");
                $(".ajaxloader-prof-pic").css("visibility", "hidden");
                $(".removeMessages-prof-pic").css("visibility", "visible");
                $(".removeMessages-prof-pic").html('<div class="alert alert-success" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button><i class="far fa-check-circle"></i> ' + e.messages + '</div>');
            } else {
                
                let title = "Invalid Entry";
                let message = e.messages;
                let tostr_type = "warning";
                call_toast(title, message, tostr_type);
                $('.bot-load-prof-pic').addClass('hide-me');
                $(".ajaxloader-prof-pic").html("");
                $(".ajaxloader-prof-pic").css("visibility", "hidden");
                $(".removeMessages-prof-pic").css("visibility", "visible");
                $(".removeMessages-prof-pic").html('<div class="alert alert-warning" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button><i class="fas fa-exclamation-triangle"></i> ' + e.messages + '</div>')
            }
        },
        complete: function () {
            $('.bot-load-prof-pic').addClass('hide-me');
            $(".ajaxloader-prof-pic").html("");
            $(".ajaxloader-prof-pic").css("visibility", "hidden");
            $(".removeMessages-prof-pic").css("visibility", "visible");
            $('#form-submitter-update-prof-pic').removeClass('btn-loading');
        }
    });

}


get_my_products();
function get_my_products(param = null) {
    $('#result-table').DataTable().destroy();
    var requiredfunction = {
        'token': localStorage.token,
    };
    $("#result-table").DataTable({
        order: [
            [1, 'asc']
        ],
        // rowGroup: {
        //     dataSrc: 0
        // },
        "ajax": {
            "data": requiredfunction,
            "url": "/admin-fetch-products",
        },
        autoWidth: !1,
        responsive: 1,
        lengthMenu: [
            [8, 16, 88, -1],
            ["8 Rows", "16 Rows", "88 Rows", "All Items"]
        ],
        language: {
            responsive: "true",
            searchPlaceholder: 'Search...',
            sSearch: '',
            lengthMenu: '_MENU_',
        },
        sDom: '<"dataTables__top"flB<"dataTables_actions">>rt<"dataTables__bottom"ip><"clear">',
        // buttons: [
        //     {
        //         //extend: 'csv',
        //         exportOptions: {
        //             modifier: {
        //                 search: 'none'
        //             }
        //         }
        //     }
        // ],
        initComplete: function () {
            $(".dataTables_actions").html('<i class="zwicon-more-h" data-toggle="dropdown" />' +
                '<div class="dropdown-menu dropdown-menu-right">' +
                '<a club-Items-action="print" class="dropdown-item">Print</a>' +
                '<a club-Items-action="fullscreen" class="dropdown-item">Fullscreen</a>' +
                '<div class="dropdown-divider" />' +
                '<div class="dropdown-header border-bottom-0 pt-0"><small>Download as</small></div>' +
                '<a club-Items-action="csv" class="dropdown-item">CSV (.csv)</a></div>')
        }
    }),
        $body = $("body");
    $body.on("click", "[club-Items-action]", function (e) {
        e.preventDefault();
        var t = $(this).attr("club-Items-action");
        if ("excel" === t && $("#club-Items_wrapper").find(".buttons-excel").click(), "csv" === t && $("#club-Items_wrapper").find(".buttons-csv").click(), "print" === t && $("#club-Items_wrapper").find(".buttons-print").click(), "fullscreen" === t) {
            var a = $(this).closest(".card");
            a.hasClass("card--fullscreen") ? (a.removeClass("card--fullscreen"), $body.removeClass("club-Items-toggled")) : (a.addClass("card--fullscreen"), $body.addClass("club-Items-toggled"))
        }
    });
    //}
}

$(document).on("click", ".action-button-status-toggle", function () {
    let attr_id = $(this).attr('attr-id');
    let attr_act = $(this).attr('attr-act');
    toggle_client_status(attr_id, attr_act);
});
$(document).on("click", ".action-preview", function () {
    let attr_id = $(this).attr('attr-id');
    let attr_act = $(this).attr('attr-act');
    get_product_details(attr_id);
});
$(document).on("click", ".action-edit", function () {
    let attr_id = $(this).attr('attr-id');
    let attr_act = $(this).attr('attr-act');
    get_product_details_edit(attr_id);
});

function get_product_details_edit(attr_id) {
    var b = {
        'attr_id': attr_id,
        'token': localStorage.token,
    };
    $.ajax({
        type: "post",
        url: "/get-product-details-by-id",
        data: b,
        dataType: "json",

        success: function (e) {
            if (e.status == 'ok') {
                console.log(e);

                var client_data = e.data;
                var client_product_data_l1 = client_data.results_prod;
                var client_product_data = client_product_data_l1.prod_data;

                $.each(client_product_data, function (key, val) {
                    console.log(val.product_name);
                    let extra_options = val.prod_params;
                    let status_span = extra_options.unit_ui_display;
                    let dropdown = extra_options.dropdown;
                    $('#update_record_id').val(val.record_id);
                    $('#product-name').val(val.product_name);
                    $('#product-name').val(val.product_name);
                    $('#vw-product_type_name').html(val.product_type_name);
                    //$('#description').html(val.description);
                    quill.setText(val.description);
                    //quill.clipboard.dangerouslyPasteHTML(html:'', source: String = 'api');
                    //quill.clipboard.dangerouslyPasteHTML(val.description, source: String = 'api');
                    $('.prod-img').attr('src',val.icon_path);
                });

                $('#new-product').modal('show');
            }
        },
        complete: function () {

        }
    });
}

function get_product_details(attr_id) {
    var b = {
        'attr_id': attr_id,
        'token': localStorage.token,
    };
    $.ajax({
        type: "post",
        url: "/get-product-details-by-id",
        data: b,
        dataType: "json",

        success: function (e) {
            if (e.status == 'ok') {
                console.log(e);

                var client_data = e.data;
                var client_product_data_l1 = client_data.results_prod;
                var client_product_data = client_product_data_l1.prod_data;

                $.each(client_product_data, function (key, val) {
                    console.log(val.product_name);
                    let extra_options = val.prod_params;
                    let status_span = extra_options.unit_ui_display;
                    let dropdown = extra_options.dropdown;
                    $('#bank-id-up').html(val.record_id);
                    $('#vw-prod-name').html(val.product_name);
                    $('#vw-product_type_name').html(val.product_type_name);
                    $('#vw-prod-description').html(val.description);
                    $('#prod-img').attr('src',val.icon_path);
                });

                $('#product-preview').modal('show');
            }
        },
        complete: function () {

        }
    });
}

function toggle_client_status(attr_id, attr_act) {
    var b = {
        "record_id": attr_id,
        "click_act": attr_act,
        'token': localStorage.token,
    };
    $.ajax({
        type: "post",
        url: "/client-toggle-product-status",
        data: b,
        dataType: "json",

        success: function (e) {
            if (e.status == 'ok') {
                get_my_products();
                let title = "Done";
                let message = "Updated"
                let tostr_type = "info";
                call_toast(title, message, tostr_type)
            }
        },
        complete: function () {

        }
    });
}
