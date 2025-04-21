<?php
session_start();
if(empty($_SESSION['SESSION_USER']) && empty($_SESSION['SESSION_ID'])){
    header('location:../../login/');
 exit;}
else {
require_once'../../../sw-library/sw-config.php';
require_once'../../login/login_session.php';
include('../../../sw-library/sw-function.php'); 
include('../../../sw-library/PHPMailer/PHPMailerAutoload.php');
$salt       = '$%DSuTyr47542@#&*!=QxR094{a911}+';
switch (@$_GET['action']){

case 'add':
  $error = array();
  
  if (empty($_POST['username'])) {
        $error[] = 'tidak boleh kosong';
    } else {
    $username = mysqli_real_escape_string($connection, $_POST['username']);
  }

  if (empty($_POST['password'])) {
        $error[] = 'tidak boleh kosong';
      } else {

        $password_kirim = mysqli_real_escape_string($connection,$_POST['password']);
        $password = mysqli_real_escape_string($connection,hash('sha256',$salt.$_POST['password']));
  }

  if (empty($_POST['fullname'])) {
        $error[] = 'tidak boleh kosong';
      } else {
        $fullname = mysqli_real_escape_string($connection, $_POST['fullname']);
  }

  if (empty($_POST['email'])) {
        $error[] = 'tidak boleh kosong';
      } else {
        $email = mysqli_real_escape_string($connection, $_POST['email']);
  }

  if (empty($_POST['level'])) {
        $error[] = 'tidak boleh kosong';
      } else {
        $level = mysqli_real_escape_string($connection, $_POST['level']);
  }



  if (empty($error)) { 
  if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $query="SELECT email from user where email='$email'";
    $result= $connection->query($query) or die($connection->error.__LINE__);
    if(!$result ->num_rows >0){
    $add= "INSERT INTO user(username,
                          password,
                          fullname,
                          email,
                          registered,
                          created_login,
                          last_login,
                          session,
                          ip,
                          browser,
                          level) values('$username',
                          '$password',
                          '$fullname',
                          '$email',
                          '$date $time',
                          '$date $time',
                          '$date $time',
                          '0',
                          '$ip',
                          '$browser',
                          '$level')";

      if($connection->query($add) === false) { 
        die($connection->error.__LINE__); 
        echo'Data tidak berhasil disimpan!';
    } else{
        echo'success';
    }}

    else{
        echo'Sepertinya Email "'.$email.'" sudah terdaftar!';
    }}

    else   {
        echo'Email yang anda masukkan salah!';
      }
    }

    else{           
        echo'Bidang inputan masih ada yang kosong!';
    }

break;
/* ------------------------------
    Update
---------------------------------*/
case 'update':
 $error = array();
   if (empty($_POST['id'])) {
      $error[] = 'ID tidak boleh kosong';
    } else {
      $id = mysqli_real_escape_string($connection, $_POST['id']);
  }

 if (empty($_POST['username'])) {
        $error[] = 'tidak boleh kosong';
    } else {
    $username = mysqli_real_escape_string($connection, $_POST['username']);
  }

  if (empty($_POST['password'])) {
        $password ='';
      } else {
        $password_kirim = mysqli_real_escape_string($connection,$_POST['password']);
        $password = mysqli_real_escape_string($connection,hash('sha256',$salt.$_POST['password']));
  }

  if (empty($_POST['fullname'])) {
        $error[] = 'tidak boleh kosong';
      } else {
        $fullname = mysqli_real_escape_string($connection, $_POST['fullname']);
  }

  if (empty($_POST['email'])) {
        $error[] = 'tidak boleh kosong';
      } else {
        $email = mysqli_real_escape_string($connection, $_POST['email']);
  }


  if (empty($_POST['level'])) {
        $error[] = 'tidak boleh kosong';
      } else {
        $level = mysqli_real_escape_string($connection, $_POST['level']);
  }


 if($password == ''){
  if (empty($error)) { 
    $update="UPDATE user SET username='$username',
                    fullname='$fullname',
                    email='$email',
                    level='$level' WHERE user_id='$id'";  
    if($connection->query($update) === false) { 
        die($connection->error.__LINE__); 
        echo'Data tidak berhasil disimpan!';
    } else{
        echo'success';
    }}
    else{           
        echo'Bidang inputan tidak boleh ada yang kosong..!';
    }
  }
  else{
      // Update Password
    // Konfigurasi SMTP
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->Host = $gmail_host;
    $mail->Username = $email; // Email Pengirim
    $mail->Password = $gmail_password; // Isikan dengan Password email pengirim
    $mail->Port = $gmail_port;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'ssl';
    //$mail->SMTPDebug = 2; // Aktifkan untuk melakukan debugging

    $mail->setFrom($email, $site_name);  //Email Pengirim
    $mail->addAddress($email, $username); // Email Penerima

    $mail->isHTML(true); // Aktifkan jika isi emailnya berupa html
   // Subjek email
    $mail->Subject = 'Ubah password baru | '.$site_name.'';

    $mailContent = '<h1>'.$site_name.'</h1><br>
        <h3>Halo, '.$username.'</h3><br>
        <p>Kamu baru saja mengubah password baru.<br>
        <b>Password Baru Anda : '.$password_kirim.'</b><br><br><br>Harap simpan baik-baik akun Anda.<br><br>
        Hormat Kami,<br>'.$site_name.'<br>Email otomatis, Mohon tidak membalas email ini</p>';
    
    $mail->Body = $mailContent;

      $update="UPDATE user SET username='$username',
                    fullname='$fullname',
                    email='$email',
                    password='$password',
                    level='$level' WHERE user_id='$id'"; 
      if($connection->query($update) === false) { 
        die($connection->error.__LINE__); 
        echo'Data tidak berhasil disimpan!';
      }else{
          echo'success';
                 if($mail->send()){
                  //echo 'Pesan telah terkirim';
                }else{
                  echo 'Mailer Error: ' . $mail->ErrorInfo;
                } 
  }
}


/* --------------- Delete ------------*/
break;
case 'delete':
  $id       = mysqli_real_escape_string($connection,epm_decode($_POST['id']));
  $deleted  = "DELETE FROM user WHERE user_id='$id'";
    if($connection->query($deleted) === true) {
        echo'success';
      } else { 
        //tidak berhasil
        echo'Data tidak berhasil dihapus.!';
        die($connection->error.__LINE__);
  }
break;

}

}
