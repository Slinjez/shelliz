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


get_lead_profile_details(record_id);
get_lead_product_details(record_id);
get_policy_frequency();
function get_lead_profile_details(record_id) {
    var b = {
        'token': record_id,
    };
    $.ajax({
        type: "post",
        url: "/admin-get-lead-profile-details",
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
                $('.client-name').html(client_data.user_name);
                
                $('#client-full-name').html(client_data.user_name);
                $('#client-phone').html(client_data.phone);
                $('#client-email').html(client_data.email_address);

                let bank_rows = '';
                

            } else {
                //$('#service-count').html(e.service_count);
            }
        },
        complete: function () {
            //pass
        }
    });

}
function get_lead_product_details(record_id) {
    var b = {
        'token': record_id,
    };
    $.ajax({
        type: "post",
        url: "/admin-get-lead-product-details",
        data: b,
        dataType: "json",
        beforeSend: function () {

            let title = "Please wait";
            let message = "Loading data"
            let tostr_type = "info";
            call_toast(title, message, tostr_type);
            $('.product-name').html('<i class="fa fa-spinner fa-spin"></i> Loading...');
            $('#product_type_name').html('<i class="fa fa-spinner fa-spin"></i> Loading...');
            $('#product_desc').html('<i class="fa fa-spinner fa-spin"></i> Loading...');

            //$('#loc-country').html('<i class="fa fa-spinner fa-spin"></i> Loading...');
        },
        success: function (e) {

            if (e.status == 'ok') {

                var prod_data = e.data;

                $('.putprodPicHere').attr('src',prod_data.icon_path);

                $('.product-name').html(prod_data.product_name);
                $('#product_type_name').html(prod_data.product_type_name);
                $('#product_desc').html(prod_data.description);

                let bank_rows = '';
                

            } else {
                //$('#service-count').html(e.service_count);
            }
        },
        complete: function () {
            //pass
        }
    });

}
function get_policy_frequency() {
    var b = {
        'token': localStorage.token,
    };
    $.ajax({
        type: "post",
        url: "/get-policy-frequency",
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
                    s += '<option value="' + val.record_id + '">' + val.frequency_name + "</option>";
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

//update-launch
//$('#update-client-modal').modal('show')

$('#update-launch').click(function (c) {
    $('#update-client-modal').modal('toggle')
})


/**
 * beneficiary
 */
 let max_beneficiary = 4;
 let current_beneficiary_count = 1;
 
 $("#addRow").click(function() {
     current_beneficiary_count++;
     if (current_beneficiary_count > max_beneficiary) {
         Lobibox.notify('default', {
             title: 'Not allowed.',
             msg: 'You have reached max allowed beneficiaries.',
             pauseDelayOnHover: true,
             continueDelayOnInactiveTab: false,
             position: 'center top',
             showClass: 'fadeInDown',
             hideClass: 'fadeOutDown',
             width: 600,
         });
         return false;
     }
     console.log('add row clicked');
     // var html = '';
     // html += '<div id="inputFormRow">';
     // html += '<div class="input-group mb-3">';
     // html += '<input type="text" name="title[]" class="form-control m-input" placeholder="Enter title" autocomplete="off">';
     // html += '<div class="input-group-append">';
     // html += '<button id="removeRow" type="button" class="btn btn-danger">Remove</button>';
     // html += '</div>';
     // html += '</div>';
     let html = '';
     html += '<div class="input-group inputFormRow mb-3 auto-div">\n' +
         '																	<div class="col-md-12 row">\n' +
         '																		<div class="col-md-6 col-sm-12">\n' +
         '																			<label class="form-label">Beneficiary Name<small></small></label><input type="text" name="beneficiary_name[]" class="form-control m-input" placeholder="beneficiary" autocomplete="off">\n' +
         '																		</div>\n' +
         '																		<div class="col-md-6  col-sm-12">\n' +
         '																			<label class="form-label">Beneficiary Relationship<small></small></label><input type="text" name="relationship[]" class="form-control m-input" placeholder="Relationship" autocomplete="off">\n' +
         '																		</div>\n' +
         
         '																	</div>\n' +
         '																	<div class="col-md-12 row">\n' +
         '																		<div class="input-group-append col-md-12">	\n' +
         '                                                                            <button type="button" class="btn btn-block btn-danger removeRow">Remove beneficiary</button>\n' +
         '																		</div>\n' +
         '																	</div>\n' +
         '																</div>';
 
     $('#newRow').append(html);
 });
 
 // remove row
 $(document).on('click', '.removeRow', function() {
     current_beneficiary_count--;
     console.log('remove row clicked');
     $(this).closest('.inputFormRow').remove();
 });
 
 /**
  * beneficiary end 
  */

$("#create-policy-form").submit(function (c) {
    var status_select = $("#status-select").val();
    var description = $("#description-field").val();
    var cov_start_date = $("#cov-start-date").val();
    var cov_end_date = $("#cov-end-date").val();
    //console.log('status_select',status_select);

    let can_submit = true;

    if (status_select=='') {
        let title = "Invalid Entry";
        let message = "Kindly enter a strong password."
        let tostr_type = "warning";
        $('#status-select').addClass('is-invalid state-invalid');
        call_toast(title, message, tostr_type)
        can_submit = false;
    }
    if (description=='') {
        let title = "Invalid Entry";
        let message = "Kindly enter a brief description."
        let tostr_type = "warning";
        $('#description-field').addClass('is-invalid state-invalid');
        call_toast(title, message, tostr_type)
        can_submit = false;
    }
    if (cov_start_date=='') {
        let title = "Invalid Entry";
        let message = "Kindly select cover start date."
        let tostr_type = "warning";
        $('#cov-start-date').addClass('is-invalid state-invalid');
        call_toast(title, message, tostr_type)
        can_submit = false;
    }
    if (cov_end_date=='') {
        let title = "Invalid Entry";
        let message = "Kindly select cover end date."
        let tostr_type = "warning";
        $('#cov-end-date').addClass('is-invalid state-invalid');
        call_toast(title, message, tostr_type)
        can_submit = false;
    }

    if (!can_submit) {
        return false;
    }
    var send_data = $("#create-policy-form").serializeArray();
    send_data.push({
        name: "cov_start_date",
        value: cov_start_date
    });
    send_data.push({
        name: "cov_end_date",
        value: cov_end_date
    });
    send_data.push({
        name: "description",
        value: description
    });
    send_data.push({
        name: "status_select",
        value: status_select
    });
    send_data.push({
        name: "token",
        value: localStorage.token
    });
    send_data.push({
        name: "transaction_id",
        value: record_id
    });
   

    $.ajax({
        type: "post",
        url: "/create-policy",
        data: send_data,
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


$('#cov-start-date').datetimepicker({	
	lang:'ch',
	timepicker:false,
	format:'Y-m-d',
	formatDate:'Y-m-d',
	minDate:'-1970/01/02', // yesterday is minimum date
	maxDate:'+1970/01/02' // and tommorow is maximum date calendar
});

$('#cov-end-date').datetimepicker({	
	lang:'ch',
	timepicker:false,
	format:'Y-m-d',
	formatDate:'Y-m-d',
	minDate:'+1970/01/02', // yesterday is minimum date
	// maxDate:'+1970/01/02' // and tommorow is maximum date calendar
});