get_my_clients();
function get_my_clients(param = null) {
    $("#result-table").DataTable().destroy();
    var requiredfunction = {
        token: localStorage.token,
    };
    $("#result-table").DataTable({
        order: [[1, "asc"]],
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
            "Last 30 Days": [moment().subtract(30, "days"), moment()],
            "Last 3 Months": [moment().subtract(91, "days"), moment()],
            "Last 6 Months": [moment().subtract(182, "days"), moment()],
            "Last 12 Months": [moment().subtract(365, "days"), moment()],
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
    },
    function (start, end) {
        $("#daterange-btn-chart span").html(
            start.format("MMMM D, YYYY") + " - " + end.format("MMMM D, YYYY")
        );
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
    var dataset_back_up = chart_data.chart_data_data;
    var eras = chart_data.era;
    var dataset_1 = dataset_back_up;

    //console.log(eras);
    //var new_array = [];
    //const x_axis = eras.map((x) => x.era);
    const x_axis = eras;

    Highcharts.chart("container", {
        chart: {
            type: "column",
        },
        title: {
            text: chart_data.title_text,
        },
        subtitle: {
            text: chart_data.sub_title_text,
        },
        xAxis: {
            categories: x_axis,
            crosshair: true,
        },
        yAxis: {
            min: 0,
            title: {
                text: chart_data.series_legend_text,
            },
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat:
                '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f} ' + chart_data.unit + '</b></td></tr>',
            footerFormat: "</table>",
            shared: true,
            useHTML: true,
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0,
            },
        },
        series: dataset_1,
    });
}

function pie_charter(chart_data) {
    var dataset_back_up = chart_data.pie_data;
    var eras = chart_data.era;
    var dataset_1 = dataset_back_up;

    console.log(dataset_1);
    var new_array = [];
    //const x_axis = eras.map((x) => x.era);
    const x_axis = eras;

    Highcharts.chart('container-pie', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: chart_data.title_text
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                }
            }
        },
        series: [{
            name: chart_data.series_legend_text,
            colorByPoint: true,
            data: dataset_1
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
        url: "/get-product-claims-chart-data",
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
            $('.char-drawer').addClass('hide-me');
        },
        success: function (e) {
            if (e.status == "ok") {
                var chart_data_data = e.chart_data;

                era = [];
                y_axis = [];
                products = [];
                pie_data = [];

                let chart_data = {
                    x_axis: era,
                    y_axis: y_axis,
                    unit: 'Claims',
                    series_legend_text: "Claim Count",
                    title_text: "Product Claims",
                    sub_title_text: "Shows product claims per month",
                    chart_data_data: chart_data_data,
                    pie_data: e.pie_data,
                };

                $('.char-drawer').removeClass('hide-me');
                charter(chart_data);
                pie_charter(chart_data);
                $("#char-load-status").addClass("hide-me");
            } else {
                $('.char-drawer').addClass('hide-me');
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
            //$('.char-drawer').removeClass('hide-me');
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
