// $('#result-table').DataTable({
//     language: {
//         responsive:"true",
//         searchPlaceholder: 'Search...',
//         sSearch: '',
//         lengthMenu: '_MENU_',
//     }
// });
get_my_clients();

function get_my_clients(param = null) {
    $("#result-table").DataTable().destroy();
    var requiredfunction = {
        token: localStorage.token,
    };
    $("#result-table").DataTable({
        order: [[1, "asc"]],
        // rowGroup: {
        //     dataSrc: 0
        // },
        ajax: {
            data: requiredfunction,
            url: "/admin-fetch-clients",
        },
        autoWidth: !1,
        responsive: 1,
        lengthMenu: [
            [8, 16, 88, -1],
            ["8 Rows", "16 Rows", "88 Rows", "All Items"],
        ],
        language: {
            responsive: "true",
            searchPlaceholder: "Search...",
            sSearch: "",
            lengthMenu: "_MENU_",
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
            $(".dataTables_actions").html(
                '<i class="zwicon-more-h" data-toggle="dropdown" />' +
                '<div class="dropdown-menu dropdown-menu-right">' +
                '<a club-Items-action="print" class="dropdown-item">Print</a>' +
                '<a club-Items-action="fullscreen" class="dropdown-item">Fullscreen</a>' +
                '<div class="dropdown-divider" />' +
                '<div class="dropdown-header border-bottom-0 pt-0"><small>Download as</small></div>' +
                '<a club-Items-action="csv" class="dropdown-item">CSV (.csv)</a></div>'
            );
        },
    }),
        ($body = $("body"));
    $body.on("click", "[club-Items-action]", function (e) {
        e.preventDefault();
        var t = $(this).attr("club-Items-action");
        if (
            ("excel" === t && $("#club-Items_wrapper").find(".buttons-excel").click(),
                "csv" === t && $("#club-Items_wrapper").find(".buttons-csv").click(),
                "print" === t && $("#club-Items_wrapper").find(".buttons-print").click(),
                "fullscreen" === t)
        ) {
            var a = $(this).closest(".card");
            a.hasClass("card--fullscreen")
                ? (a.removeClass("card--fullscreen"),
                    $body.removeClass("club-Items-toggled"))
                : (a.addClass("card--fullscreen"),
                    $body.addClass("club-Items-toggled"));
        }
    });
    //}
}

$(document).on("click", ".action-button-status-toggle", function () {
    let attr_id = $(this).attr("attr-id");
    let attr_act = $(this).attr("attr-act");
    toggle_client_status(attr_id, attr_act);
});

function toggle_client_status(attr_id, attr_act) {
    var b = {
        record_id: attr_id,
        click_act: attr_act,
        token: localStorage.token,
    };
    $.ajax({
        type: "post",
        url: "/client-toggle-client-status",
        data: b,
        dataType: "json",

        success: function (e) {
            if (e.status == "ok") {
                get_my_clients();
                let title = "Done";
                let message = "Updated";
                let tostr_type = "info";
                call_toast(title, message, tostr_type);
            }
        },
        complete: function () { },
    });
}

/**CHARTING */
moment().defaultFormat = "YYYY-MM-DD HH:mm";
$("#daterange-btn-chart").daterangepicker(
    {
        ranges: {
            Today: [moment(), moment()],
            Yesterday: [moment().subtract(1, "days"), moment().subtract(1, "days")],
            "Last 7 Days": [moment().subtract(6, "days"), moment()],
            "Last 30 Days": [moment().subtract(29, "days"), moment()],
            "Last 6 Months": [moment().subtract(182, "days"), moment()],
            "This Month": [moment().startOf("month"), moment().endOf("month")],
            "Last Month": [
                moment().subtract(1, "month").startOf("month"),
                moment().subtract(1, "month").endOf("month"),
            ],
        },
        startDate: moment().subtract(182, "days").format("MM/DD/YYYY"),
        endDate: moment().format("MM/DD/YYYY"),
        format: "YYYY-MM-DD HH:mm:ss",
        autoApply: true,

        //'format': 'YYYY-MM-DD H:mm',
        // locale: {
        //   format: "YYYY-MM-DD",
        // },
    },
    function (start, end) {
        $("#daterange-btn-chart span").html(
            start.format("MMMM D, YYYY") + " - " + end.format("MMMM D, YYYY")
        );
        // $("#daterange-btn-chart span").html(
        //   start.format("YYYY-MM-DD") + " - " + end.format("YYYY-MM-DD")
        // );
    }
);

$("#daterange-btn-chart").on("apply.daterangepicker", function (ev, picker) {
    console.log("--------Date change evt--------");
    console.log(picker.startDate.format("YYYY-MM-DD"));
    console.log(picker.endDate.format("YYYY-MM-DD"));
    infantize_chart();
});

infantize_chart();

function charter(chart_data) {
    // let chart_data = {
    //     x_axis: x_axis,
    //     y_axis: y_axis,
    //     series_title_text:'User Count',
    //     title_text:'Client Data',
    //     sub_title_text:'Source: Shelliz',
    // };
    Highcharts.chart('container', {
        chart: {
            type: 'area'
        },
        accessibility: {
            description: chart_data.sub_title_text
        },
        title: {
            text: chart_data.title_text
        },
        subtitle: {
            text: chart_data.sub_title_text
        },
        // xAxis: {
        //     allowDecimals: false,
        //     labels: {
        //         formatter: function () {
        //             return this.value; // clean, unformatted number for year
        //         }
        //     },
        //     accessibility: {
        //         rangeDescription: 'Range: 1940 to 2017.'
        //     }
        // },
        xAxis: {
            categories: chart_data.x_axis,
            crosshair: true
          },
          
        yAxis: {
            title: {
                text: chart_data.series_title_text
            },
            // labels: {
            //     formatter: function () {
            //         return this.value / 1000 + 'k';
            //     }
            // }
        },
        tooltip: {
            pointFormat: 'Shelliz {categories} had <b>{point.y:,.0f}</b> new members'
        },
        plotOptions: {
            area: {
                //pointStart: 1940,
                marker: {
                    enabled: false,
                    symbol: 'circle',
                    radius: 2,
                    states: {
                        hover: {
                            enabled: true
                        }
                    }
                }
            }
        },
        series: [{
            name: chart_data.series_title_text,
            data: chart_data.y_axis
        }]
    });
}

function infantize_chart() {
    var start_date = $("#daterange-btn-chart").data("daterangepicker").startDate
        ._d;
    var end_date = $("#daterange-btn-chart").data("daterangepicker").endDate._d;

    let start_date_1 = moment(start_date).format("YYYY-MM-DD HH:mm:ss");
    let end_date_2 = moment(end_date).format("YYYY-MM-DD HH:mm:ss");
    var b = {
        start_date: start_date_1,
        end_date: end_date_2,
        token: localStorage.token,
    };
    $.ajax({
        type: "post",
        url: "/get-client-chart-data",
        data: b,
        dataType: "json",
        beforeSend: function () {
            $("#char-load-status").removeClass("hide-me");
            $(".ajaxLoginloader").html(
                '<p class="modal-title"><i class="bx bx-loader bx-spin"></i> Please wait...</p>'
            );
            $(".ajaxLoginloader").css("visibility", "visible");
            $(".removeLoginMessages").html("");
            $(".removeLoginMessages").css("visibility", "hidden");
            $(".clientlogin-btn")
                .prop("disabled", true)
                .html('<i class="bx bx-loader bx-spin"></i>');
        },
        success: function (e) {
            if (e.status == "ok") {
                //CALL CHARTER
                console.log(e);
                var chart_data_data = e.chart_data;
                //var chart_data_data = resp_data.chart_data;
                console.log(chart_data_data);
                x_axis = [];
                y_axis = [];
                $.each(chart_data_data, function (key, val) {
                    let strbt1 =val.month_resp + " " + val.year_resp;
                    console.log(strbt1);
                    x_axis.push(strbt1);                    
                });

                $.each(chart_data_data, function (key, val) {
                    console.log(val.stat_count);
                    y_axis.push(parseInt(val.stat_count));
                });

                let chart_data = {
                    "x_axis": x_axis,
                    "y_axis": y_axis,
                    "series_title_text":'User Count',
                    "title_text":'Client Data',
                    "sub_title_text":'Source: Shelliz',
                };
                console.log(chart_data);
                charter(chart_data);

                $("#char-load-status").addClass("hide-me");
            } else {
                $(".ajaxLoginloader").html("");
                $(".ajaxLoginloader").css("visibility", "hidden");
                $(".removeLoginMessages").html(
                    '<div class="alert alert-warning alert-dismissible" role="alert"><a class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></a><strong> <span class="fa-fa-cancel"></span> </strong>' +
                    e.messages +
                    "</div>"
                );
            }
        },
        complete: function () {
            $(".ajaxLoginloader").html("");
            $(".ajaxLoginloader").css("visibility", "hidden");
            $(".removeLoginMessages").css("visibility", "visible");
            $(".clientlogin-btn").prop("disabled", false).html("Log In");
            $(".clientlogin-btn-ico")
                .prop("disabled", false)
                .html('<i class="lni lni-arrow-right"></i>');
        },
    });
}
