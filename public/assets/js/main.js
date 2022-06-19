

function call_toast(title,message,tostr_type){
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": true,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
      }
      if(tostr_type=='success'){
        toastr.success(message, title);
      }
      if(tostr_type=='error'){
        toastr.error(message, title);
      }
      if(tostr_type=='info'){
        toastr.info(message, title);
      }
      if(tostr_type=='warning'){
        toastr.warning(message, title);
      }
      // if(tostr_type=='notice'){
      //   toastr.notice(message, title);
      // }   
}

let app_user_name = localStorage.getItem('username');
$(document).ready(function() {
    $(".portal-user-name", this).html(app_user_name);
})
if (localStorage.profpic != null) {
  $('.putMyPicHere').attr("src", localStorage.profpic);
}

$(".terminate-session").on('click', function(event) {
  $.ajax({
      type: "post",
      url: "/logout",
      dataType: "json",

      success: function(e) {
          if (e.status == 'ok') {
              localStorage.clear();
              location.reload();
          } else {
              localStorage.clear();
              location.reload();
          }
      },
  });
  event.preventDefault()
});

