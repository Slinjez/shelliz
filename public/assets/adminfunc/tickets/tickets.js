


get_my_tickets();
get_ticket_types();
function get_ticket_types() {
    var b = {
        'token': localStorage.token,
    };
    $.ajax({
        type: "post",
        url: "/get-ticket-type-list",
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
                    s += '<option value="' + val.record_id + '">' + val.ticket_type_description + "</option>";
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

function get_my_tickets(param = null) {
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
            "url": "/admin-fetch-all-tickets",
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


$(document).on("click", ".call-back-trigger", function () {
    let attr_id = $(this).attr('attr-id');
    $('#call-back-product-id').val(attr_id);
    //
    $('#request-callback').modal('show');
    //get_bank_details(attr_id);
});

$("#ticket-form").submit(function (c) {
    console.log('ticket save clicked');
    
    
    var ticket_type = $('#status-select').val();
    var ticket_subject = $('#ticket-subject').val();
    var message = $('#message').val();
    
    let can_submit = true;

    if (ticket_type == '') {
        let title = "Invalid Entry";
        let message = "Kindly select ticket type."
        let tostr_type = "warning";
        $('#status-select').addClass('is-invalid state-invalid');
        call_toast(title, message, tostr_type)
        can_submit = false;
    }
    if (ticket_subject == '') {
        let title = "Invalid Entry";
        let message = "Kindly enter ticket subject."
        let tostr_type = "warning";
        $('#ticket-subject').addClass('is-invalid state-invalid');
        call_toast(title, message, tostr_type)
        can_submit = false;
    }
    if (message == '') {
        let title = "Invalid Entry";
        let message = "Kindly enter ticket message."
        let tostr_type = "warning";
        $('#message').addClass('is-invalid state-invalid');
        call_toast(title, message, tostr_type)
        can_submit = false;
    }
    
    if (!can_submit) {
        return false;
    }

    var b = {
        "ticket_type": ticket_type,
        "ticket_subject": ticket_subject,
        "message": message,
        'token': localStorage.token,
    };

    $.ajax({
        type: "post",
        url: "/save-ticket",
        data: b,
        dataType: "json",
        beforeSend: function () {
            $(".ajaxloader-modal").html('<div class="alert alert-info" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button><i class="fa fa-spinner fa-spin"></i> Please wait...</div>');
            $(".ajaxloader-modal").css("visibility", "visible");
            $(".removeMessages-modal").html("");
            $(".removeMessages-modal").css("visibility", "hidden");
            $('#form-submitter-modal').addClass('btn-loading');
            $('.bot-load-modal').removeClass('hide-me');
        },
        success: function (e) {
            if (e.status == 'ok') {
                $('.bot-load-modal').addClass('hide-me');
                $(".ajaxloader-modal").html("");
                $(".ajaxloader-modal").css("visibility", "hidden");
                $(".removeMessages-modal").css("visibility", "visible");
                $(".removeMessages-modal").html('<div class="alert alert-success" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button><i class="far fa-check-circle"></i> ' + e.messages + '</div>');
                $('#form-submitter-modal').removeClass('btn-loading');
                get_my_tickets();
                $('#new-ticket').modal('hide');

                let title = "Done";
                let message = "Updated"
                let tostr_type = "info";
                call_toast(title, message, tostr_type)
            } else {
                $('.bot-load-modal').addClass('hide-me');
                $(".ajaxloader-modal").html("");
                $(".ajaxloader-modal").css("visibility", "hidden");
                $(".removeMessages-modal").css("visibility", "visible");
                $(".removeMessages-modal").html('<div class="alert alert-warning" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button><i class="fas fa-exclamation-triangle"></i> ' + e.messages + '</div>')
            }
        },
        complete: function () {
            $('.bot-load-modal').addClass('hide-me');
            $(".ajaxloader-modal").html("");
            $(".ajaxloader-modal").css("visibility", "hidden");
            $(".removeMessages-modal").css("visibility", "visible");
            $('#form-submitter-modal').removeClass('btn-loading');
            $('#form-submitter-modal').removeClass('btn-loading');
        }
    });
    c.preventDefault()
})