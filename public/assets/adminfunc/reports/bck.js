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
    complete: function () {},
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
  var dataset_1 = dataset_back_up;
  delete dataset_1["policy_count"];
  delete dataset_1["product_name"];

  var new_array = [];
  const x_axis = dataset_1.map((x) => x.era);
  const product_name_arr = dataset_1.map((x) => x.product_name);
  const mapResult1 = dataset_1.map((currentValue, index, array) => {
    console.log("*********STR****************");
    console.log("currentValue", currentValue);
    console.log("index", index);
    console.log("array", array);
    //need to do amonth loop
    var pol_count_int = parseInt(currentValue.policy_count);

    var new_data_item_array = [];
    new_data_item_array.push(pol_count_int);

    if (new_array.length === 0) {
      console.log("in if empty new_array", new_array);
      new_array.push([
        (name = currentValue.product_name),
        (data = new_data_item_array),
      ]);
      console.log(
        "----PUSHED NEW new_array",
        currentValue.product_name + " " + new_data_item_array
      );
    } else if (new_array.length > 0) {
      console.log("in if notempty new_array", new_array);

      for (let itm of new_array) {
        console.log("itm", itm);
        var loop_index = 0;
        if (itm[loop_index] === currentValue.product_name) {
          console.log(itm[0], " found in itm.name");
          let current_index_value = itm[1];
          current_index_value.push(pol_count_int);
          itm[1] = current_index_value;
          console.log("----Updated", currentValue.product_name + " " + itm[1]);
        } else {
          let current_index_value = itm[1];
          var new_data_item_array_internal = [];
          new_data_item_array_internal.push(pol_count_int);

          //current_index_value.push(pol_count_int);
          //itm.data = current_index_value;
          console.log(itm[loop_index]);
          console.log(currentValue.product_name);
          console.log(
            new_array.findIndex((x) => itm[loop_index] == "Fire Domestic") > -1
          );

          var is_existing_key = new_array.findIndex(
            (x) => itm[loop_index] === currentValue.product_name
          );

          console.log("findIndex", is_existing_key);
          if (itm.some((x) => itm[loop_index] === currentValue.product_name)) {
            itm[loop_index] = [];
          }
          new_array.push([
            (name = currentValue.product_name),
            (data = new_data_item_array),
          ]);
          console.log(
            "----alt ver Updated",
            currentValue.product_name + " " + itm
          );
          console.log("----alternate UpdatE triggered new_array", new_array);
        }
        loop_index++;
      }
    }

    console.log("*********END******************");
    return {
      product: currentValue.product_name,
      value: currentValue.policy_count,
    };
    //    return currentValue.map((currentValuex) => [
    //         currentValuex.product_name,
    //        currentValuex.policy_count
    //    ]);
  });

  console.log("product_name_arr", product_name_arr);
  console.log("new_array", new_array);
  console.log("mapResult1", mapResult1);

  Highcharts.chart("container", {
    chart: {
      type: "column",
    },
    title: {
      text: "Monthly Average Rainfall",
    },
    subtitle: {
      text: "Source: WorldClimate.com",
    },
    xAxis: {
      categories: x_axis,
      crosshair: true,
    },
    yAxis: {
      min: 0,
      title: {
        text: "Rainfall (mm)",
      },
    },
    tooltip: {
      headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
      pointFormat:
        '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
        '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
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
    series: [
      {
        name: "Tokyo",
        data: [49.9, 71.5, 106.4, 129.2, 144.0, 176.0],
      },
      {
        name: "New York",
        data: [83.6, 78.8, 98.5, 93.4, 106.0],
      },
      {
        name: "London",
        data: [48.9, 38.8, 39.3, 41.4, 47.0, 48.3],
      },
      {
        name: "Berlin",
        data: [42.4, 33.2, 34.5, 39.7, 52.6, 75.5],
      },
    ],
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
    url: "/get-policy-uptake-chart-data",
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
        var chart_data_data = e.chart_data;
        console.log(chart_data_data);

        era = [];
        y_axis = [];
        products = [];

        let chart_data = {
          x_axis: era,
          y_axis: y_axis,
          series_title_text: "User Count",
          title_text: "Client Data",
          sub_title_text: "Source: Shelliz",
          chart_data_data: chart_data_data,
        };
        //console.log(chart_data);
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
