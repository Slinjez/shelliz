let searchParams = new URLSearchParams(window.location.search);

let record_id = '';

if (searchParams.has('param')) {
    record_id = searchParams.get('param');
} else {
    console.log('param id not set');

    let title = "Invalid Entry";
    let message = "param id not sets"
    let tostr_type = "warning";
    call_toast(title, message, tostr_type);
    //window.location.href = "/blog";
}
try {
    record_id = parseInt(record_id);
    console.log(record_id);

    if (isNaN(record_id) || record_id < 1) {
        console.log('not a good number');

        let title = "Invalid Entry";
        let message = "param id not sets"
        let tostr_type = "warning";
        call_toast(title, message, tostr_type);
        //window.location.href = "/blog";
    }

} catch (Exception) {
    //window.location.href = "/blog";
}

//get_services(record_id);


get_profile_details(record_id);
get_country_list();
get_form_details();
get_client_call_backs(record_id);
get_my_tickets(param = record_id);
get_my_policies(record_id);
function get_my_policies(param = null) {
    $('#result-table').DataTable().destroy();
    var requiredfunction = {
        'user-id-c':record_id,
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
            "url": "/admin-fetch-client-policies",
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
function get_my_tickets(param = null) {
    $('#tickets-table').DataTable().destroy();
    var requiredfunction = {
        'user-id-c':record_id,
        'token': localStorage.token,
    };
    $("#tickets-table").DataTable({
        order: [
            [1, 'asc']
        ],
        // rowGroup: {
        //     dataSrc: 0
        // },
        "ajax": {
            "data": requiredfunction,
            "url": "/admin-fetch-all-tickets-by-client",
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

function get_client_call_backs(param = null) {
    $('#cb-result-table').DataTable().destroy();
    var requiredfunction = {
        'user-id-c':record_id,
        'token': localStorage.token,
    };
    $("#cb-result-table").DataTable({
        order: [
            [1, 'asc']
        ],
        // rowGroup: {
        //     dataSrc: 0
        // },
        "ajax": {
            "data": requiredfunction,
            "url": "/admin-fetch-callback-requests-by-id",
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

function get_profile_details(record_id) {
    var b = {
        'token': record_id,
    };
    $.ajax({
        type: "post",
        url: "/admin-get-profile-details",
        data: b,
        dataType: "json",
        beforeSend: function () {
            
        let title = "Please wait";
        let message = "Loading data"
        let tostr_type = "info";
        call_toast(title, message, tostr_type);

            $('#client-full-name').html('<i class="fa fa-spinner fa-spin"></i> Loading...');
            $('#client-phone').html('<i class="fa fa-spinner fa-spin"></i> Loading...');
            $('#client-email').html('<i class="fa fa-spinner fa-spin"></i> Loading...');


            $('#portal-id-no').html('<i class="fa fa-spinner fa-spin"></i> Loading...');
            $('#portal-pin-no').html('<i class="fa fa-spinner fa-spin"></i> Loading...');
            $('#loc-address').html('<i class="fa fa-spinner fa-spin"></i> Loading...');


            $('#loc-city').html('<i class="fa fa-spinner fa-spin"></i> Loading...');
            $('#loc-country').html('<i class="fa fa-spinner fa-spin"></i> Loading...');
        },
        success: function (e) {

            if (e.status == 'ok') {

                var client_data = e.data;
                var client_extra_data = client_data.results_extra;
                var client_banking_data_l1 = client_data.results_banking;
                var client_banking_data = client_banking_data_l1.bank_data;

                if (client_extra_data.length === 0) {
                    $('#portal-id-no').html('Not Available.');
                    $('#portal-pin-no').html('Not Available.');
                    $('#loc-address').html('Not Available.');
                    $('#loc-city').html('Not Available.');
                    $('#loc-country').html(client_data.address);
                } else {
                    $('#portal-id-no').html(client_extra_data.national_id);
                    $('#portal-pin-no').html(client_extra_data.pin);
                    $('#loc-address').html(client_extra_data.address);
                    $('#loc-city').html(client_extra_data.city);
                    $('#loc-country').html(client_extra_data.country_name);
                }
                if('client_data.profile_picture'!=''){
                    $('.putMyPicHere-prf').attr('src',client_data.profile_picture);
                }
                $('.client-name').html(client_data.user_name);
                $('#client-full-name').html(client_data.user_name);
                $('#client-full-name').html(client_data.user_name);
                $('#client-phone').html(client_data.phone);
                $('#client-email').html(client_data.email_address);

                let bank_rows = '';
                $.each(client_banking_data, function (key, val) {
                    let extra_options = val.bank_params;
                    let status_span = extra_options.unit_ui_display;
                    let dropdown = extra_options.dropdown;
                    bank_rows += '<tr>'
                        + '<th scope="row">' + val.bank_name + '</th>'
                        + '<td>' + val.bank_branch + '</td>'
                        + '<td>' + val.account_number + '</td>'
                        + '<td>' + status_span + '</td>'
                        + '</tr>';

                });
                $('#bank-table-body').html(bank_rows);

            } else {
                //$('#service-count').html(e.service_count);
            }
        },
        complete: function () {
            //pass
        }
    });

}

function get_form_details() {
    var b = {
        'token': localStorage.token,
    };
    $.ajax({
        type: "post",
        url: "/get-profile-details",
        data: b,
        dataType: "json",
        beforeSend: function () {
            $('.bot-load').removeClass('hide-me');
        },
        success: function (e) {

            if (e.status == 'ok') {

                var client_data = e.data;
                var client_extra_data = client_data.results_extra;

                if (client_extra_data.length === 0) {
                    //pass
                } else {
                    $('#id-number').val(client_extra_data.national_id);
                    $('#pin').val(client_extra_data.pin);
                    $('#city').val(client_extra_data.city);
                    $('#postal-code').val(client_extra_data.postal_code);
                    $('#address').val(client_extra_data.address);
                }

                $('#full-name').val(client_data.user_name);
                $('#phone-number').val(client_data.phone);



            } else {
                //$('#service-count').html(e.service_count);
            }
        },
        complete: function () {
            $('.bot-load').addClass('hide-me');
        }
    });

}

function get_country_list() {
    var b = {
        'token': localStorage.token,
    };
    $.ajax({
        type: "post",
        url: "/get-country-list",
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
                    s += '<option value="' + val.id + '">' + val.name + "</option>";
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

$("#data-form").submit(function (c) {
    var username = $("#full-name").val();
    var phone_number = $("#phone-number").val();
    var address = $("#address").val();
    var id_number = $("#id-number").val();
    var pin = $("#pin").val();
    var city = $("#city").val();
    var postal_code = $("#postal-code").val();
    var status_select = $("#status-select").val();
    var client_bio_field = $("#client-bio-field").val();

    let can_submit = true;

    if (!validate_user_name(username)) {
        let title = "Invalid Entry";
        let message = "Kindly enter your full name"
        let tostr_type = "warning";
        $('#full-name').addClass('is-invalid state-invalid');
        call_toast(title, message, tostr_type)
        can_submit = false;
    }
    if (!validate_phone(phone_number)) {
        let title = "Invalid Entry";
        let message = "Kindly enter your phone number"
        let tostr_type = "warning";
        $('#phone-number').addClass('is-invalid state-invalid');
        call_toast(title, message, tostr_type)
        can_submit = false;
    }
    if (address == '') {
        let title = "Invalid Entry";
        let message = "Kindly enter your address"
        let tostr_type = "warning";
        $('#address').addClass('is-invalid state-invalid');
        call_toast(title, message, tostr_type)
        can_submit = false;
    }
    if (!validate_id(id_number)) {
        let title = "Invalid Entry";
        let message = "Kindly enter your id number"
        let tostr_type = "warning";
        $('#id-number').addClass('is-invalid state-invalid');
        call_toast(title, message, tostr_type)
        can_submit = false;
    }
    if (!validate_pin(pin)) {
        let title = "Invalid Entry";
        let message = "Kindly enter your KRA PIN"
        let tostr_type = "warning";
        $('#pin').addClass('is-invalid state-invalid');
        call_toast(title, message, tostr_type)
        can_submit = false;
    }
    if (city == '') {
        let title = "Invalid Entry";
        let message = "Kindly enter your city"
        let tostr_type = "warning";
        $('#city').addClass('is-invalid state-invalid');
        call_toast(title, message, tostr_type)
        can_submit = false;
    }
    if (postal_code == '') {
        let title = "Invalid Entry";
        let message = "Kindly enter your postal code"
        let tostr_type = "warning";
        $('#postal-code').addClass('is-invalid state-invalid');
        call_toast(title, message, tostr_type)
        can_submit = false;
    }
    if (status_select == '') {
        let title = "Invalid Entry";
        let message = "Kindly enter your country of residence"
        let tostr_type = "warning";
        $('#status-select').addClass('is-invalid state-invalid');
        call_toast(title, message, tostr_type)
        can_submit = false;
    }

    if (!can_submit) {
        return false;
    }

    var b = {
        "client_full_name": username,
        "client_phone": phone_number,
        "address": address,
        "id_number": id_number,
        "pin": pin,
        "city": city,
        "postal_code": postal_code,
        "country": status_select,
        'token': localStorage.token,
    };

    $.ajax({
        type: "post",
        url: "/update-bio",
        data: b,
        dataType: "json",
        beforeSend: function () {
            $(".ajaxloader").html('<div class="alert alert-info" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button><i class="fa fa-spinner fa-spin"></i> Please wait...</div>');
            $(".ajaxloader").css("visibility", "visible");
            $(".removeMessages").html("");
            $(".removeMessages").css("visibility", "hidden");
            $('#form-submitter').addClass('btn-loading');
            $('.bot-load').removeClass('hide-me');
        },
        success: function (e) {
            if (e.status == 'ok') {
                $('.bot-load').addClass('hide-me');
                $(".ajaxloader").html("");
                $(".ajaxloader").css("visibility", "hidden");
                $(".removeMessages").css("visibility", "visible");
                $(".removeMessages").html('<div class="alert alert-success" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button><i class="far fa-check-circle"></i> ' + e.messages + '</div>');

                localStorage.username = username;

                $('.portal-user-name').each(function () {
                    $(this).html(username);
                });

                let title = "Done";
                let message = "Updated"
                let tostr_type = "info";
                call_toast(title, message, tostr_type);

                call_toast(title, message, tostr_type)
            } else {
                $('.bot-load').addClass('hide-me');
                $(".ajaxloader").html("");
                $(".ajaxloader").css("visibility", "hidden");
                $(".removeMessages").css("visibility", "visible");
                $(".removeMessages").html('<div class="alert alert-warning" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button><i class="fas fa-exclamation-triangle"></i> ' + e.messages + '</div>')
            }
        },
        complete: function () {
            $('.bot-load').addClass('hide-me');
            $(".ajaxloader").html("");
            $(".ajaxloader").css("visibility", "hidden");
            $(".removeMessages").css("visibility", "visible");
            $('#form-submitter').removeClass('btn-loading');
        }
    });
    c.preventDefault()
})

$("#bank-form").submit(function (c) {
    var bank_name = $("#bank-name").val();
    var bank_branch = $("#bank-branch").val();
    var account_number = $("#account-number").val();
    console.log('new' + bank_name);
    let can_submit = true;

    if (bank_name == '') {
        let title = "Invalid Entry";
        let message = "Kindly enter bank name"
        let tostr_type = "warning";
        $('#bank-name').addClass('is-invalid state-invalid');
        call_toast(title, message, tostr_type)
        can_submit = false;
    }
    if (bank_branch == '') {
        let title = "Invalid Entry";
        let message = "Kindly enter bank branch"
        let tostr_type = "warning";
        $('#bank-branch').addClass('is-invalid state-invalid');
        call_toast(title, message, tostr_type)
        can_submit = false;
    }
    if (account_number == '') {
        let title = "Invalid Entry";
        let message = "Kindly enter your account number"
        let tostr_type = "warning";
        $('#account-number').addClass('is-invalid state-invalid');
        call_toast(title, message, tostr_type)
        can_submit = false;
    }

    if (!can_submit) {
        return false;
    }

    var b = {
        "bank_name": bank_name,
        "bank_branch": bank_branch,
        "account_number": account_number,
        'token': localStorage.token,
    };

    $.ajax({
        type: "post",
        url: "/save-bank",
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

                get_profile_details();

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
        }
    });
    c.preventDefault()
})

$("#bank-form-update").submit(function (c) {
    var bank_id_up = $("#bank-id-up").val();
    var bank_name_up = $("#bank-name-up").val();
    var bank_branch_up = $("#bank-branch-up").val();
    var account_number_up = $("#account-number-up").val();

    console.log('update' + bank_name_up);

    let can_submit = true;

    if (bank_id_up == '') {
        let title = "Invalid Entry";
        let message = "Invalid attr_id kindly reload page and try again."
        let tostr_type = "warning";
        $('#bank-name').addClass('is-invalid state-invalid');
        call_toast(title, message, tostr_type)
        can_submit = false;
    }
    if (bank_name_up == '') {
        let title = "Invalid Entry";
        let message = "Kindly enter bank name"
        let tostr_type = "warning";
        $('#bank-name').addClass('is-invalid state-invalid');
        call_toast(title, message, tostr_type)
        can_submit = false;
    }
    if (bank_branch_up == '') {
        let title = "Invalid Entry";
        let message = "Kindly enter bank branch"
        let tostr_type = "warning";
        $('#bank-branch').addClass('is-invalid state-invalid');
        call_toast(title, message, tostr_type)
        can_submit = false;
    }
    if (account_number_up == '') {
        let title = "Invalid Entry";
        let message = "Kindly enter your account number"
        let tostr_type = "warning";
        $('#account-number').addClass('is-invalid state-invalid');
        call_toast(title, message, tostr_type)
        can_submit = false;
    }

    if (!can_submit) {
        return false;
    }

    var b = {
        "bank_id_up": bank_id_up,
        "bank_name": bank_name_up,
        "bank_branch": bank_branch_up,
        "account_number": account_number_up,
        'token': localStorage.token,
    };

    $.ajax({
        type: "post",
        url: "/update-bank",
        data: b,
        dataType: "json",
        beforeSend: function () {
            $(".ajaxloader-modal").html('<div class="alert alert-info" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button><i class="fa fa-spinner fa-spin"></i> Please wait...</div>');
            $(".ajaxloader-modal").css("visibility", "visible");
            $(".removeMessages-modal").html("");
            $(".removeMessages-modal").css("visibility", "hidden");
            $('#form-submitter-update-modal').addClass('btn-loading');
            $('.bot-load-modal').removeClass('hide-me');
        },
        success: function (e) {
            if (e.status == 'ok') {
                $('.bot-load-modal').addClass('hide-me');
                $(".ajaxloader-modal").html("");
                $(".ajaxloader-modal").css("visibility", "hidden");
                $(".removeMessages-modal").css("visibility", "visible");
                $(".removeMessages-modal").html('<div class="alert alert-success" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button><i class="far fa-check-circle"></i> ' + e.messages + '</div>');

                get_profile_details();

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
            $('#form-submitter-update-modal').removeClass('btn-loading');
        }
    });
    c.preventDefault()
})

/**PS */
$("#update-ps-form").submit(function (c) {
    var oldpassword = $("#oldpassword").val();
    var loginpassword = $("#loginpassword").val();

    let can_submit = true;

    if (!validate_password(loginpassword)) {
        let title = "Invalid Entry";
        let message = "Kindly enter a strong password."
        let tostr_type = "warning";
        $('#loginpassword').addClass('is-invalid state-invalid');
        call_toast(title, message, tostr_type)
        can_submit = false;
    }
    if (oldpassword == '') {
        let title = "Invalid Entry";
        let message = "Kindly enter the current password"
        let tostr_type = "warning";
        $('#oldpassword').addClass('is-invalid state-invalid');
        call_toast(title, message, tostr_type)
        can_submit = false;
    }


    if (!can_submit) {
        return false;
    }

    var b = {
        "old_password": oldpassword,
        "new_password": loginpassword,
        'token': localStorage.token,
    };

    $.ajax({
        type: "post",
        url: "/update-auth-client-password",
        data: b,
        dataType: "json",
        beforeSend: function () {
            $(".ajaxloader-ps").html('<div class="alert alert-info" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button><i class="fa fa-spinner fa-spin"></i> Please wait...</div>');
            $(".ajaxloader-ps").css("visibility", "visible");
            $(".removeMessages-ps").html("");
            $(".removeMessages-ps").css("visibility", "hidden");
            $('#form-submitter-update-ps').addClass('btn-loading');
            $('.bot-load-ps').removeClass('hide-me');
        },
        success: function (e) {
            if (e.status == 'ok') {
                $('.bot-load-ps').addClass('hide-me');
                $(".ajaxloader-ps").html("");
                $(".ajaxloader-ps").css("visibility", "hidden");
                $(".removeMessages-ps").css("visibility", "visible");
                $(".removeMessages-ps").html('<div class="alert alert-success" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button><i class="far fa-check-circle"></i> ' + e.messages + '</div>');

                get_profile_details();

                let title = "Done";
                let message = "Updated"
                let tostr_type = "info";
                call_toast(title, message, tostr_type)
            } else {
                $('.bot-load-ps').addClass('hide-me');
                $(".ajaxloader-ps").html("");
                $(".ajaxloader-ps").css("visibility", "hidden");
                $(".removeMessages-ps").css("visibility", "visible");
                $(".removeMessages-ps").html('<div class="alert alert-warning" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button><i class="fas fa-exclamation-triangle"></i> ' + e.messages + '</div>')
            }
        },
        complete: function () {
            $('.bot-load-ps').addClass('hide-me');
            $(".ajaxloader-ps").html("");
            $(".ajaxloader-ps").css("visibility", "hidden");
            $(".removeMessages-ps").css("visibility", "visible");
            $('#form-submitter-update-ps').removeClass('btn-loading');
        }
    });
    c.preventDefault()
})

//act click
$(document).on("click", ".action-button-veup", function () {
    let attr_id = $(this).attr('attr-id');
    let attr_act = $(this).attr('attr-act');
    get_bank_details(attr_id);
});
$(document).on("click", ".action-button-status-toggle", function () {
    let attr_id = $(this).attr('attr-id');
    let attr_act = $(this).attr('attr-act');
    toggle_bank_status(attr_id, attr_act);
});

function get_bank_details(attr_id) {
    var b = {
        'attr_id': attr_id,
        'token': localStorage.token,
    };
    $.ajax({
        type: "post",
        url: "/get-bank-details-by-id",
        data: b,
        dataType: "json",

        success: function (e) {
            if (e.status == 'ok') {
                console.log(e);

                var client_data = e.data;
                var client_banking_data_l1 = client_data.results_banking;
                var client_banking_data = client_banking_data_l1.bank_data;

                $.each(client_banking_data, function (key, val) {
                    console.log(val);
                    let extra_options = val.bank_params;
                    let status_span = extra_options.unit_ui_display;
                    //let dropdown = extra_options.dropdown;
                    $('#bank-id-up').val(val.record_id);
                    $('#bank-name-up').val(val.bank_name);
                    $('#bank-branch-up').val(val.bank_branch);
                    $('#account-number-up').val(val.account_number);
                    $('#title-bank-name').html(val.bank_name + ' ' + status_span);
                });

                $('#bank-preview').modal('show');
            }
        },
        complete: function () {

        }
    });
}

function toggle_bank_status(attr_id, attr_act) {
    var b = {
        "record_id": attr_id,
        "click_act": attr_act,
        'token': localStorage.token,
    };
    $.ajax({
        type: "post",
        url: "/client-toggle-bank-status",
        data: b,
        dataType: "json",

        success: function (e) {
            if (e.status == 'ok') {
                get_profile_details();
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

function show_password_old() {
    const password = document.querySelector('#oldpassword');
    (password.type === "password") ? ($('.ps-span').html('<i class="far fa-eye fa-2x-e"></i>')) : ($('.ps-span').html('<i class="far fa-eye-slash fa-2x-e"></i>'));
    password.type = (password.type === "password") ? ("text") : ("password");
    password.focus();
}

function show_password_new() {
    const password = document.querySelector('#loginpassword');
    (password.type === "password") ? ($('.ps-span').html('<i class="far fa-eye fa-2x-e"></i>')) : ($('.ps-span').html('<i class="far fa-eye-slash fa-2x-e"></i>'));
    password.type = (password.type === "password") ? ("text") : ("password");
    password.focus();
}

function validate_user_name(e) {
    return /^[A-Za-z][a-z]*(([,.] |[ '-])[A-Za-z][a-z]*)*(\.?)( [IVXLCDM]+)?$/.test(String(e).toLowerCase());
}
function validate_email(e) {
    return /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(String(e).toLowerCase());
}
function validate_id(e) {
    return /^[0-9]{7,8}$/.test(String(e).toLowerCase());
}
function validate_pin(e) {
    return /^[A-Z|a-z]\d{9}[A-Z|a-z]/.test(String(e).toLowerCase());
}
function validate_password(e) {
    return /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/.test(String(e));
}
function validate_phone(e) {
    return /^(\+254|0)[1-9]\d{8,9}$/.test(String(e));
}

$('input[type="number"],input[type="text"],input[type="email"],input[type="password"]').keyup(function () {
    $(this).removeClass('is-invalid state-invalid');
});

$(document).on('hide.bs.modal', '#new-bank', function () {
    $(".ajaxloader-modal").html("");
    $(".ajaxloader-modal").css("visibility", "hidden");
    $(".removeMessages-modal").html("");
    $(".removeMessages-modal").css("visibility", "hidden");
});
$(document).on('hide.bs.modal', '#bank-preview', function () {
    $(".ajaxloader-modal").html("");
    $(".ajaxloader-modal").css("visibility", "hidden");
    $(".removeMessages-modal").html("");
    $(".removeMessages-modal").css("visibility", "hidden");
});


(Dropzone.options.myAwesomeDropzone = {
    paramName: "eventfiles",
    maxFilesize: 2,
    parallelUploads: 8,
    maxFiles: 1,
    autoProcessQueue: !1,
    acceptedFiles: "image/*",
    addRemoveLinks: true,
    dictFileTooBig: "File is to big ({{filesize}}mb). Max allowed file size is {{maxFilesize}}mb",
    dictInvalidFileType: "Invalid File Type",
    dictCancelUpload: "Cancel",
    dictRemoveFile: "Remove this",
    dictMaxFilesExceeded: "Only {{maxFiles}} files are allowed",
    dictDefaultMessage: "Drop files here to upload. You can also just click here.",
    url: "/update-auth-client-profile-picture",
    uploadMultiple: true,
    autoDiscover: false,
    accept: function (e, a) {
        "uda.jpg" == e.name ? a("Nah, you just didn't.") : a();
    },
    init: function () {
        var e = this;
        $("#new-event").click(function (a) {
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


                var t = $("#prof-pic-form").serializeArray();
                t.push({
                    name: "token",
                    value: localStorage.token
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

                    localStorage.profpic = a.file_path;
                    if (localStorage.profpic != null) {
                        $('.putMyPicHere').attr("src", a.file_path);
                    }
                } else {
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

    var e = $("#prof-pic-form").serializeArray();

    e.push({
        name: "token",
        value: localStorage.token
    });

    $.ajax({
        type: "post",
        url: "/update-auth-client-profile-picture",
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
                $('.bot-load-prof-pic').addClass('hide-me');
                $(".ajaxloader-prof-pic").html("");
                $(".ajaxloader-prof-pic").css("visibility", "hidden");
                $(".removeMessages-prof-pic").css("visibility", "visible");
                $(".removeMessages-prof-pic").html('<div class="alert alert-success" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button><i class="far fa-check-circle"></i> ' + e.messages + '</div>');
            } else {
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