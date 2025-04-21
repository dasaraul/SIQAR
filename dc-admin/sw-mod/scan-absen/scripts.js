$(document).ready(function() {
   


    function loading(){
        $(".loading").show();
        $(".loading").delay(1500).fadeOut(500);
    }


/* ------------------------------------------------
    // SCAN ABSENSI
---------------------------------------------------*/
/*function webcameqrcode(){
    $(".webcame-qrcode").hide();
    $(".webcame-qrcode").delay(4000).fadeIn();
}

    var result;
    $(document).ready(function getLocation() {
        result = document.getElementById("latitude");
       // 
        if(navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
        } else {
            swal({title: 'Oops!', text:'Maaf, browser Anda tidak mendukung geolokasi HTML5.', icon: 'error', timer: 3000,});
        }
    });
    
    // Define callback function for successful attempt
    function successCallback(position) {
       result.innerHTML =""+ position.coords.latitude + ","+position.coords.longitude + "";
    }

    // Define callback function for failed attempt
    function errorCallback(error) {
        if(error.code == 1) {
            swal({title: 'Oops!', text:'Anda telah memutuskan untuk tidak membagikan posisi Anda, tetapi tidak apa-apa. Kami tidak akan meminta Anda lagi.', icon: 'error', timer: 3000,});
        } else if(error.code == 2) {
            swal({title: 'Oops!', text:'Jaringan tidak aktif atau layanan penentuan posisi tidak dapat dijangkau.', icon: 'error', timer: 3000,});
        } else if(error.code == 3) {
            swal({title: 'Oops!', text:'Waktu percobaan habis sebelum bisa mendapatkan data lokasi.', icon: 'error', timer: 3000,});
        } else {
            swal({title: 'Oops!', text:'Waktu percobaan habis sebelum bisa mendapatkan data lokasi.', icon: 'error', timer: 3000,});
        }
    }
    
    var arg = {
        resultFunction: function(result){
        if(result.code=='3'){
            console.log(result.code);
        }else{
            loading();
            document.getElementById("my_audio").play();
            var latitude = $('.latitude').html();
            var dataString = 'qrcode='+result.code+'&latitude='+latitude;
            $.ajax({
                type: "POST",
                url: "sw-mod/scan-absen/proses.php?action=absent",
                data: dataString,
                success: function (data) {
                    var results = data.split("/");
                    $results = results[0];
                    $results2 = results[1];
                    if ($results=='success') {
                         swal({title: 'Berhasil!', text:$results2, icon: 'success', timer: 2000,});
                         webcameqrcode();
                         loadData();
                    } else {
                        swal({title: 'Oops!', text:data, icon: 'error',timer: 2000,});
                        webcameqrcode();
                    }
                    console.log(result.code);

                }
            });
        }

        }
    };
    // proses scanning qr code dari kamera
    var decoder = $("canvas").WebCodeCamJQuery(arg).data().plugin_WebCodeCamJQuery;

    // menampilkan dan memilih kamera yang tersedia
    decoder.buildSelectMenu("select");
    decoder.play();
    $('select').on('change', function(){
        decoder.stop().play();
    });*/

    var result;
    $(document).ready(function getLocation() {
        result = document.getElementById("latitude");
       // 
        if(navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
        } else {
            swal({title: 'Oops!', text:'Maaf, browser Anda tidak mendukung geolokasi HTML5.', icon: 'error', timer: 3000,});
        }
    });
    
    // Define callback function for successful attempt
    function successCallback(position) {
       result.innerHTML =""+ position.coords.latitude + ","+position.coords.longitude + "";
    }

    // Define callback function for failed attempt
    function errorCallback(error) {
        if(error.code == 1) {
            swal({title: 'Oops!', text:'Anda telah memutuskan untuk tidak membagikan posisi Anda, tetapi tidak apa-apa. Kami tidak akan meminta Anda lagi.', icon: 'error', timer: 3000,});
        } else if(error.code == 2) {
            swal({title: 'Oops!', text:'Jaringan tidak aktif atau layanan penentuan posisi tidak dapat dijangkau.', icon: 'error', timer: 3000,});
        } else if(error.code == 3) {
            swal({title: 'Oops!', text:'Waktu percobaan habis sebelum bisa mendapatkan data lokasi.', icon: 'error', timer: 3000,});
        } else {
            swal({title: 'Oops!', text:'Waktu percobaan habis sebelum bisa mendapatkan data lokasi.', icon: 'error', timer: 3000,});
        }
    }
    var html5QrcodeScanner = new Html5QrcodeScanner(
    "reader", { fps: 10, qrbox:280,facingMode: "environment"});

    function onScanSuccess(decodedText, decodedResult) {
        // Handle on success condition with the decoded text or result.
        //console.log(`Scan result: ${decodedText}`, decodedResult);
            document.getElementById("my_audio").play();
            html5QrcodeScanner.clear();
            var latitude = $('.latitude').html();
            var dataString = 'qrcode='+decodedText+'&latitude='+latitude;
            $.ajax({
                type: "POST",
                url: "sw-mod/scan-absen/proses.php?action=absent",
                data: dataString,
                success: function (data) {
                    var results = data.split("/");
                    $results = results[0];
                    $results2 = results[1];
                    if ($results=='success') {
                         swal({title: 'Berhasil!', text:$results2, icon: 'success', timer: 2000,});
                            html5QrcodeScanner.render(onScanSuccess);
                    } else {
                        swal({title: 'Oops!', text:data, icon: 'error', timer: 2000,});
                        html5QrcodeScanner.render(onScanSuccess);
                    }
                }
            });
         }
    html5QrcodeScanner.render(onScanSuccess);



loadData();
function loadData() {
    var id = $('.id').val();
    $.ajax({
        url: 'sw-mod/scan-absen/proses.php?action=data',
        type: 'POST',
        success: function(data) {
          $('.loaddata').html(data);
        }
    });
}


});