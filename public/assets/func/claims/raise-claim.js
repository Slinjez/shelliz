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


$('#incident-date').datetimepicker({	
	lang:'ch',
	timepicker:false,
	format:'Y-m-d',
	formatDate:'Y-m-d',
	//minDate:'+1970/01/02', // yesterday is minimum date
	// maxDate:'+1970/01/02' // and tommorow is maximum date calendar
});

(Dropzone.options.myAwesomeDropzone = {
    paramName: "eventfiles",
    maxFilesize: 2,
    parallelUploads: 8,
    maxFiles: 10,
    autoProcessQueue: !1,
    acceptedFiles: "image/*",
    addRemoveLinks: true,
    dictFileTooBig: "File is to big ({{filesize}}mb). Max allowed file size is {{maxFilesize}}mb",
    dictInvalidFileType: "Invalid File Type",
    dictCancelUpload: "Cancel",
    dictRemoveFile: "Remove this",
    dictMaxFilesExceeded: "Only {{maxFiles}} files are allowed",
    dictDefaultMessage: "Drop files here to upload. You can also just click here.",
    url: "/save-claim",
    uploadMultiple: true,
    autoDiscover: false,
    accept: function (e, a) {
        "uda.jpg" == e.name ? a("Nah, you just didn't.") : a();
    },
    init: function () {
        var e = this;
        $("#form-submitter").click(function (a) {
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


                var t = $("#claim-form").serializeArray();

                var incident_date = $("#incident-date").val();
                var claim_desc = $("#claim-desc").val();
                
                t.push({
                    name: "policy_id",
                    value: record_id
                });
                t.push({
                    name: "incident_date",
                    value: incident_date
                });
                t.push({
                    name: "claim_desc",
                    value: claim_desc
                });

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

    var e = $("#claim-form").serializeArray();
    var incident_date = $("#incident-date").val();
    var claim_desc = $("#claim-desc").val();

    e.push({
        name: "policy_id",
        value: record_id
    });
    e.push({
        name: "incident_date",
        value: incident_date
    });
    e.push({
        name: "claim_desc",
        value: claim_desc
    });

    e.push({
        name: "token",
        value: localStorage.token
    });

    $.ajax({
        type: "post",
        url: "/save-claim",
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
