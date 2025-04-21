function loading() {
	$("#stat").html('<div class="alert alert-info"><i>Authenticating..</i></div>');
}
$(document).ready(function () {
	$("#login").click(function () { login(); });
});

function login() {
	if ($("#username").val() == "" || $("#password").val() == "") {

		$("#stat").fadeTo('slow', '1.99');
		$("#stat").fadeIn('slow', function () { $("#stat").html('<div class="alert alert-warning">Username/Password belum lengkap !</div>'); })
		return false;
	}
	else {
		loading();
		var username = $("#username").val();
		var password = $("#password").val();
		$.getJSON("../login/login-proses.php?action=login",{ username: username, password: password }, function (json) {
			if (json.response.error == "0")	// jika login gagal
			{

				$("#stat").fadeTo('slow', '1.99');
				$("#stat").fadeIn('slow', function () { $("#stat").html('<div class="alert alert-danger">Periksa username & Password anda.!</div>'); });
			}
			else	// Login sukses
			{
				$("#stat").fadeOut('slow', function () {
					window.location.replace("../");
					//window.location = url_admin;
				});
			}
		});
		return false;
	}
};


/* ----------- Add ------------*/
$('.forgot').submit(function (e) {
        e.preventDefault();
        $.ajax({
            url:"../login/login-proses.php?action=forgot",
            type: "POST",
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            beforeSend: function () { 
              //loading();
            },
            success: function (data) {
                if (data == 'success') {
                    swal({title: 'Berhasil!', text: 'Password berhasil diresset ulang!', icon: 'success', timer: 1500,});
                } else {
                    swal({title: 'Oops!', text: data, icon: 'error', timer: 1500,});
                }

            },
            complete: function () {
                
            },
        });

  });