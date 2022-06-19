
$('#datetimepicker10').datetimepicker({
    minDate:'-1970/01/02',
	step:5,
	inline:true,
    format:'Y-m-d H:i',
});

get_product_list();


function get_product_list() {
    var b = {
        'token': localStorage.token,
    };
    $.ajax({
        type: "post",
        url: "/get-product-list",
        data: b,
        dataType: "json",
        beforeSend: function () {
            //pass
        },
        success: function (e) {

            if (e.status == 'ok') {
                let sb = '';
                var resp_data = e.data;
                var data_list_data = resp_data.data_list;


                $.each(data_list_data, function (key, val) {
                    //s += '<option value="' + val.id + '">' + val.name + "</option>";
                    let icon_path='/uploads/products/icons/16373417452012335337.jpg';
                    if(val.icon_path!==null){
                        icon_path=val.icon_path;
                    }
                    sb += "<div class=\"col-md-12 col-lg-4\">" +
                        "													<div class=\"card overflow-hidden\">" +
                        "														<img alt=\"image\" src=\"" + icon_path + "\">" +
                        "														<div class=\"card-body\">" +
                        "															<h5 class=\"card-title\">" + val.product_name + "</h5>" +
                        "															<p class=\"card-text\">" + val.description + "</p>" +
                        "														</div>" +
                        "														<div class=\"card-body\">" +
                        "														 <a attr-id=\"" + val.record_id + "\" class=\"card-link btn btn-primary call-back-trigger\" href=\"javascript:void(0)\">Request Call-Back</a>" +
                        "														</div>" +
                        "													</div>" +
                        "													</div>";


                });
                $('#placement').html(sb);

                $(".paginate").paginga({
                    itemsPerPage: 6,
                    scrollToTop: {
                        offset: 100,
                        speed: 100,
                    },

                });
                $('#preload').addClass('hide-me');
                $('#result-view').removeClass('hide-me');

            } else {
                //$('#service-count').html(e.service_count);
            }
        },
        complete: function () {
            //pass
        }
    });

}


$(document).on("click", ".call-back-trigger", function () {
    let attr_id = $(this).attr('attr-id');
    $('#call-back-product-id').val(attr_id);
    //
    $('#request-callback').modal('show');
    //get_bank_details(attr_id);
});

$("#call-back-form").submit(function (c) {
    console.log('callback save clicked');
    var call_back_product_id = $("#call-back-product-id").val();
    //var call_back_date = $('#datetimepicker10').datetimepicker('getValue');
    var call_back_date = $('#datetimepicker10').val();
    console.log('callback date:',call_back_date);
    let can_submit = true;

    if (call_back_product_id == '') {
        let title = "Invalid Entry";
        let message = "Kindly reload"
        let tostr_type = "warning";
        call_toast(title, message, tostr_type)
        can_submit = false;
    }
    if (call_back_date == '') {
        let title = "Invalid Entry";
        let message = "Kindly select date and time"
        let tostr_type = "warning";
        $('#getValue').addClass('is-invalid state-invalid');
        call_toast(title, message, tostr_type)
        can_submit = false;
    }
    
    if (!can_submit) {
        return false;
    }

    var b = {
        "call_back_product_id": call_back_product_id,
        "call_back_date": call_back_date,
        'token': localStorage.token,
    };

    $.ajax({
        type: "post",
        url: "/save-call-back",
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
            $('#form-submitter-modal').removeClass('btn-loading');
        }
    });
    c.preventDefault()
})