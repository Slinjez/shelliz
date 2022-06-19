
get_dash_overview();
get_dash_details();
get_my_policies();
function get_dash_details() {
    var b = {
        'token': localStorage.token,
    };
    $.ajax({
        type: "post",
        url: "/get-dashboard-details",
        data: b,
        dataType: "json",
        beforeSend: function () {
            $('#tenure-string').html('<i class="fa fa-spinner fa-spin"></i> Loading...');
            $('#start-date').html('<i class="fa fa-spinner fa-spin"></i> Loading...');
        },
        success: function (e) {

            if (e.status == 'ok') {

                let client_data = e.data;
                let tenure_string = client_data.tenure_string;
                let member_since = client_data.member_since;
                let first_name = client_data.first_name;

                $('#tenure-string').html(tenure_string);
                $('#start-date').html(member_since);
                $('#first-name').html(first_name);


                // let bank_rows = '';
                // $.each(client_banking_data, function (key, val) {
                //     let extra_options = val.bank_params;
                //     let status_span = extra_options.unit_ui_display;
                //     let dropdown = extra_options.dropdown;
                //     bank_rows += '<tr>'
                //         + '<th scope="row">' + val.bank_name + '</th>'
                //         + '<td>' + val.bank_branch + '</td>'
                //         + '<td>' + val.account_number + '</td>'
                //         + '<td>' + status_span + '</td>'
                //         + '<td>' + dropdown + '</td>'
                //         + '</tr>';

                // });
                // $('#bank-table-body').html(bank_rows);

            } else {
                //$('#service-count').html(e.service_count);
            }
        },
        complete: function () {
            //pass
        }
    });

}

function get_my_policies(param = null) {
    $('#result-table').DataTable().destroy();
    var requiredfunction = {
        //'user-id-c':record_id,
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
            "url": "/client-fetch-client-policies",
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

function get_dash_overview() {
    var b = {
        'token': localStorage.token,
    };
    $.ajax({
        type: "post",
        url: "/get-dashboard-overview",
        data: b,
        dataType: "json",
        beforeSend: function () {
            $('#policy-count').html('<i class="fa fa-spinner fa-spin"></i> Loading...');
            $('#beneficiary-count').html('<i class="fa fa-spinner fa-spin"></i> Loading...');
            $('#ticket-count').html('<i class="fa fa-spinner fa-spin"></i> Loading...');

        },
        success: function (e) {

            if (e.status == 'ok') {


                $('#policy-count').html(e.policy_count);
                $('#beneficiary-count').html(e.beneficiary_count);
                $('#ticket-count').html(e.ticket_count);


                // let bank_rows = '';
                // $.each(client_banking_data, function (key, val) {
                //     let extra_options = val.bank_params;
                //     let status_span = extra_options.unit_ui_display;
                //     let dropdown = extra_options.dropdown;
                //     bank_rows += '<tr>'
                //         + '<th scope="row">' + val.bank_name + '</th>'
                //         + '<td>' + val.bank_branch + '</td>'
                //         + '<td>' + val.account_number + '</td>'
                //         + '<td>' + status_span + '</td>'
                //         + '<td>' + dropdown + '</td>'
                //         + '</tr>';

                // });
                // $('#bank-table-body').html(bank_rows);

            } else {
                //$('#service-count').html(e.service_count);
            }
        },
        complete: function () {
            //pass
        }
    });

}