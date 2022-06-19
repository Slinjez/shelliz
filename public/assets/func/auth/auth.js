let searchParams = new URLSearchParams(window.location.search);

let return_url = '/client-auth';

if (searchParams.has('return-url')) {
    return_url = searchParams.get('return-url');
}

$('.login-link').attr('href', return_url);

$('.close').click(function () {
    $(".alert-dismissible").css("display", "none");
})

$('.alerts-div').on('click', 'close', function () {
    $(".alert-dismissible").css("display", "none");
});

$("#button-addon2").on('click', function (event) {
    //console.log('Icon clicked');
    event.preventDefault();
    if ($('#loginpassword ').attr("type") == "text") {
        $('#loginpassword ').attr('type', 'password');
        $('#button-addon2 ').html("<i class='bx bx-hide'></i>");
        //$('#button-addon2 ').html( "<i class='bx bx-show-alt'></i>" );
    } else if ($('#loginpassword ').attr("type") == "password") {
        $('#loginpassword ').attr('type', 'text');
        //$('#button-addon2 ').html("<i class='bx bx-hide'></i>" );
        $('#button-addon2 ').html("<i class='bx bx-show-alt'></i>");
    }
});

$("#login-form").submit(function (c) {
    console.log('Login clicked');
    var email = $("#InputEmail1").val();
    var loginpassword = $("#loginpassword").val();

    var b = {
        "email": email,
        "password": loginpassword,
    };
    $.ajax({
        type: "post",
        url: "/loginAction",
        data: b,
        dataType: "json",
        beforeSend: function () {
            $(".ajaxLoginloader").html('<div class="alert alert-info" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button><i class="fa fa-spinner fa-spin"></i> Please wait...</div>');
            $(".ajaxLoginloader").css("visibility", "visible");
            $(".removeLoginMessages").html("");
            $(".removeLoginMessages").css("visibility", "hidden");
            $('#clientlogin').addClass('btn-loading');
            $('.bot-load').removeClass('hide-me');
        },
        success: function (e) {
            console.log(e);
            if (e.status == 'ok') {
                $(".ajaxLoginloader").html("");
                $(".ajaxLoginloader").css("visibility", "hidden");
                $(".removeLoginMessages").html('<div class="alert alert-success" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button><i class="far fa-check-circle"></i> ' + e.messages + '</div>');
                localStorage.token = e['vars'].token;
                localStorage.username = e['vars'].username;
                localStorage.profpic = e['vars'].profpic;
                var path = e['vars'].path;
                if (localStorage.profpic != null) {
                    $('.putMyPicHere').attr("src", localStorage.profpic);
                }

                location.reload();
            } else {
                $('.bot-load').addClass('hide-me');
                $(".ajaxLoginloader").html("");
                $(".ajaxLoginloader").css("visibility", "hidden");
                $(".removeLoginMessages").html('<div class="alert alert-warning" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button><i class="fas fa-exclamation-triangle"></i> ' + e.messages + '</div>')
            }
        },
        complete: function () {
            $('.bot-load').addClass('hide-me');
            $(".ajaxLoginloader").html("");
            $(".ajaxLoginloader").css("visibility", "hidden");
            $(".removeLoginMessages").css("visibility", "visible");
            $('#clientlogin').removeClass('btn-loading');
        }
    });
    c.preventDefault()
})

$("#confirm-otp").click(function (c) {
    var ver_otp = $("#ver_otp").val();
    var assocemail = $("#reg-email").val();

    var b = {
        "ver_otp": ver_otp,
        "assocemail": assocemail,
    };

    $.ajax({
        type: "post",
        url: "/activate-otp",
        data: b,
        dataType: "json",
        beforeSend: function () {
            $(".ajaxRegloader").html('<p class="modal-title"><span class="fa fa-spinner fa-spin"></span> Please wait...</p>');
            $(".ajaxRegloader").css("visibility", "visible");
            $(".removeRegMessages").html("");
            $(".removeRegMessages").css("visibility", "hidden");
        },
        success: function (e) {
            if (e.status == 'ok') {
                $(".ajaxRegloader").html("");
                $(".ajaxRegloader").css("visibility", "hidden");
                $(".removeRegMessages").css("visibility", "visible");
                $(".removeRegMessages").html('<div class="alert alert-success alert-dismissible" role="alert"><a  class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></a><strong> <span class="fa-fa-check"></span> </strong>' + e.messages + "</div>");
                $('#confirmotp').modal('hide');

            } else {
                $(".ajaxRegloader").html("");
                $(".ajaxRegloader").css("visibility", "hidden");
                $(".removeRegMessages").html('<div class="alert alert-warning alert-dismissible" role="alert"><a class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></a><strong> <span class="fa-fa-cancel"></span> </strong>' + e.messages + "</div>")
            }
        },
        complete: function () {
            $(".ajaxRegloader").html("");
            $(".ajaxRegloader").css("visibility", "hidden");
            $(".removeRegMessages").css("visibility", "visible");
        }
    });
    c.preventDefault()
})

$("#reset-ps1-form").submit(function (c) {
    var email = $("#InputEmail1").val();
    var loginpassword = $("#loginpassword").val();

    var b = {
        "email": email,
        "password": loginpassword,
    };
    console.log(b);
    $.ajax({
        type: "post",
        url: "/resetpsaction-client",
        data: b,
        dataType: "json",
        beforeSend: function () {
            $(".ajaxLoginloader").html('<div class="alert alert-info" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button><i class="fa fa-spinner fa-spin"></i> Please wait...</div>');
            $(".ajaxLoginloader").css("visibility", "visible");
            $(".removeLoginMessages").html("");
            $(".removeLoginMessages").css("visibility", "hidden");
            $(".clientResetPs1-btn").addClass('btn-loading');
        },
        success: function (e) {
            if (e.status == 'ok') {
                $(".ajaxLoginloader").html("");
                $(".ajaxLoginloader").css("visibility", "hidden");
                $(".removeLoginMessages").html('<div class="alert alert-success" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button><i class="far fa-check-circle"></i> ' + e.messages + '</div>');
                $('#reset-ps1-form').addClass('hide-me');
                $('#reset-ps2-form').removeClass('hide-me');
                //window.location.href = path;
                //location.reload();
            } else {
                $(".ajaxLoginloader").html("");
                $(".ajaxLoginloader").css("visibility", "hidden");
                $(".removeLoginMessages").html('<div class="alert alert-warning" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button><i class="fas fa-exclamation-triangle"></i> ' + e.messages + '</div>')
            }
        },
        complete: function () {
            $(".ajaxLoginloader").html("");
            $(".ajaxLoginloader").css("visibility", "hidden");
            $(".removeLoginMessages").css("visibility", "visible");
            $(".clientResetPs1-btn").removeClass('btn-loading');
        }
    });
    c.preventDefault()
})
 
$("#reset-ps2-form").submit(function (c) {
    var email = $("#reg-email").val();
    var otp = $("#otp").val();

    var b = {
        "assocemail": email,
        "ver_otp": otp,
    };
    console.log(b);
    $.ajax({
        type: "post",
        url: "/activate-otp-client",
        data: b,
        dataType: "json",
        beforeSend: function () {
            $(".ajaxLoginloader").html('<div class="alert alert-info" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button><i class="fa fa-spinner fa-spin"></i> Please wait...</div>');
            $(".ajaxLoginloader").css("visibility", "visible");
            $(".removeLoginMessages").html("");
            $(".removeLoginMessages").css("visibility", "hidden");
            $(".clientResetPs2-btn").addClass('btn-loading');
        },
        success: function (e) {
            if (e.status == 'ok') {
                $(".ajaxLoginloader").html("");
                $(".ajaxLoginloader").css("visibility", "hidden");
                $(".removeLoginMessages").html('<div class="alert alert-success" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button><i class="far fa-check-circle"></i> ' + e.messages + '</div>');
                $('#reset-ps1-form').addClass('hide-me');
                $('#reset-ps2-form').removeClass('hide-me');
                //window.location.href = path;
                //location.reload();
            } else {
                $(".ajaxLoginloader").html("");
                $(".ajaxLoginloader").css("visibility", "hidden");
                $(".removeLoginMessages").html('<div class="alert alert-warning" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button><i class="fas fa-exclamation-triangle"></i> ' + e.messages + '</div>')
            }
        },
        complete: function () {
            $(".ajaxLoginloader").html("");
            $(".ajaxLoginloader").css("visibility", "hidden");
            $(".removeLoginMessages").css("visibility", "visible");
            $(".clientResetPs2-btn").removeClass('btn-loading');
        }
    });
    c.preventDefault()
})

$("#register-form").submit(function (c) {
    $('.show-otp-popup').removeClass('hide-me');
    var username = $("#full-name").val();
    var email = $("#reg-email").val();
    var id_no = $("#id-no").val();
    var kra_pin = $("#kra-pin").val();
    var reg_ps = $("#reg-ps").val();

    let can_submit = true;
    if (!validate_user_name(username)) {
        let title = "Invalid Entry";
        let message = "Kindly enter your full name"
        let tostr_type = "warning";

        //$('.fname-in').addClass(' has-error has-addon-error  has-error is-invalid state-invalid');
        $('.fname-span, .fname-in').addClass('is-invalid state-invalid');

        call_toast(title, message, tostr_type)
        can_submit = false;
    }

    if (!validate_email(email)) {
        let title = "Invalid Entry";
        let message = "Kindly enter your email address"
        let tostr_type = "warning";

        $('.email-in, .email-span').addClass('is-invalid state-invalid');

        call_toast(title, message, tostr_type)
        can_submit = false;
    }

    if (!validate_id(id_no)) {
        let title = "Invalid Entry";
        let message = "Kindly enter your id number"
        let tostr_type = "warning";

        $('.id-in, .id-span').addClass('is-invalid state-invalid');

        call_toast(title, message, tostr_type)
        can_submit = false;
    }

    if (!validate_pin(kra_pin)) {
        let title = "Invalid Entry";
        let message = "Kindly enter your KRA PIN number"
        let tostr_type = "warning";

        $('.kra-in, .kra-span').addClass('is-invalid state-invalid');

        call_toast(title, message, tostr_type)
        can_submit = false;
    }
    
    if (!validate_password(reg_ps)) {
        let title = "Invalid Entry";
        let message = "Kindly enter a password containing at least 8 characters, 1 number, 1 upper and 1 lowercase"
        let tostr_type = "warning";

        $('.ps-in, .ps-span').addClass('is-invalid state-invalid');

        call_toast(title, message, tostr_type)
        can_submit = false;
    }

    if(!$('#tnc').is(':checked') ){
        let title = "Invalid Entry";
        let message = "Kindly check the terms and conditions"
        let tostr_type = "warning";

        $('.tnc-in').addClass('is-invalid state-invalid');

        call_toast(title, message, tostr_type)
        can_submit = false;
    }
    

    if (!can_submit) {
        return false;
    }

    var b = {
        "username": username,
        "email": email,
        "id_no": id_no,
        "kra_pin": kra_pin,
        "reg_ps": reg_ps,
    };

    $.ajax({
        type: "post",
        url: "/registerAction",
        data: b,
        dataType: "json",
        beforeSend: function () {
            $(".ajaxLoginloader").html('<div class="alert alert-info" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button><i class="fa fa-spinner fa-spin"></i> Please wait...</div>');
            $(".ajaxLoginloader").css("visibility", "visible");
            $(".removeLoginMessages").html("");
            $(".removeLoginMessages").css("visibility", "hidden");
            $('#register-butto').addClass('btn-loading');
            $('.bot-load').removeClass('hide-me');
        },
        success: function (e) {
            if (e.status == 'ok') {
                $('.otp-responses').html(e.messages);
                $(".ajaxRegloader").html("");
                $(".ajaxRegloader").css("visibility", "hidden");
                $(".removeRegMessages").css("visibility", "visible");
                $(".removeLoginMessages").html('<div class="alert alert-success" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button><i class="far fa-check-circle"></i> ' + e.messages + '</div>');
                //$('#confirmotp').modal('show'); 


                $('#register-form').addClass('hide-me');
                $('#reset-ps2-form').removeClass('hide-me');
            } else {
                $(".ajaxRegloader").html("");
                $(".ajaxRegloader").css("visibility", "hidden");
                $(".removeLoginMessages").html('<div class="alert alert-warning" role="alert"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button><i class="fas fa-exclamation-triangle"></i> ' + e.messages + '</div>');

            }
        },
        complete: function () {
            $('.bot-load').addClass('hide-me');
            $(".ajaxLoginloader").html("");
            $(".ajaxLoginloader").css("visibility", "hidden");
            $(".removeLoginMessages").css("visibility", "visible");
            $('#register-butto').removeClass('btn-loading');
        }
    });
    c.preventDefault()
})

$("input.className:text").val("");

function show_password() {
    const password = document.querySelector('#reg-ps');
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

$('input[type="number"],input[type="text"],input[type="email"],input[type="password"]').keyup(function () {
    $(this).removeClass('is-invalid state-invalid');
});

