<?php 
if ($mod ==''){
    header('location:../404');
    echo'kosong';
}else{
    include_once 'sw-mod/sw-header.php';
    require_once'./sw-library/qr_code/qrlib.php'; 
if(!isset($_COOKIE['COOKIES_MEMBER'])){
            setcookie('COOKIES_MEMBER', '', 0, '/');
            setcookie('COOKIES_COOKIES', '', 0, '/');
            // Login tidak ditemukan
            setcookie("COOKIES_MEMBER", "", time()-$expired_cookie);
            setcookie("COOKIES_COOKIES", "", time()-$expired_cookie);
            session_destroy();
            header("location:./");
    }else{

      $codeContents = $row_user['employees_code'];
      $tempdir = './sw-content/employees-code-qr/';
      #parameter inputan
      $isi_teks = $codeContents;
      $namafile = ''.seo_title($row_user['employees_code']).'.jpg';
      if(file_exists('./sw-content/employees-code-qr/'.$namafile.'')){
          $namafile = $namafile;
        }else{
          $quality = 'L'; //ada 4 pilihan, L (Low), M(Medium), Q(Good), H(High)
          $ukuran = 5; //batasan 1 paling kecil, 10 paling besar
          $padding = 1;
          QRCode::png($isi_teks,$tempdir.$namafile,$quality,$ukuran,$padding);
        }

      $query_theme  ="SELECT photo FROM business_card WHERE active='1' LIMIT 1";
      $result_theme = $connection->query($query_theme);
      $row_theme    = $result_theme->fetch_assoc();

      $query ="SELECT position_name FROM position WHERE position_id='$row_user[position_id]'";
      $result= $connection->query($query);
      $row =  $result->fetch_assoc();
  echo'
  <!-- App Capsule -->
    <div id="appCapsule">
        <!-- Wallet Card -->
        <div class="section wallet-card-section pt-1 mb-4">
            <div class="wallet-card">
                <div class="text-center">
                    <!-- * ID Card -->
                    <div class="id-card">';
                    if($result_theme->num_rows > 0){
                      echo'
                      <div class="body-id-card text-center" id="divToPrint" style="background:url(./sw-content/id-card/'.$row_theme['photo'].') no-repeat center; background-size:100%;">';}
                    else{
                      echo'
                      <div class="body-id-card text-center" id="divToPrint" style="background:url(./sw-mod/sw-assets/img/bg-id-card.jpg) no-repeat center;background-size:100%;">';}
                      echo'
                        <div class="logo">
                          <!--<img src="'.$base_url.'sw-content/'.$site_logo.'" alt="logo">-->
                        </div>
                            <div class="avatar">';
                              if($row_user['photo'] ==''){
                                echo'<img src="'.$base_url.'sw-content/avatar.jpg" alt="image" class="imaged w70">';
                                }else{
                                echo'<img src="./sw-content/karyawan/'.$row_user['photo'].'" alt="'.$row_user['employees_name'].'" class="imaged w70">';
                              }
                            echo'
                            </div>
                            <h3>'.$row_user['employees_name'].'</h3>
                            <p>'.$row['position_name'].'</p>
                          
              
                            <div class="barcode">
                                <img class="img-responsive text-center" src="'.$tempdir.''.$namafile.'" alt="QR CODE">
                            </div>
                            <p><span>NIP</span>: '.$row_user['employees_nip'].'</p>
        
                        </div>
                    </div>';?>
                    <hr>
                    <a href="#" class="btn btn-success btn-lg btn-Convert-Html2Image"><ion-icon name="save-outline"></ion-icon> Simpan ID Card</a>
                    <div id="previewImage" class="d-none"></div>
                </div>
            </div>
        </div>
        <!-- Wallet Card --> 
    </div>
    <!-- * App Capsule -->
    <?php
  }
  include_once 'sw-mod/sw-footer.php';
} ?>