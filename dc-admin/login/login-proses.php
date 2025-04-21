<?PHP session_start();
require_once'../../sw-library/sw-config.php'; 
include_once'../../sw-library/sw-function.php';
	$salt 			= '$%DSuTyr47542@#&*!=QxR094{a911}+';
	$ip_login 		= $_SERVER['REMOTE_ADDR'];
	$created_login	= date('Y-m-d H:i:s');
	$iB 			= getBrowser();
	$browser 		= $iB['name'].' '.$iB['version'];

switch (@$_GET['action']){
case 'login':

		if (isset($_GET['username'])){
			$username 	= htmlentities($_GET['username']);
			$password 	= hash('sha256',$salt.$_GET['password']);
			$session	= md5(rand(1000,9999).rand(19078,9999).date('ymdhisss'));

			$update = mysqli_query($connection,"UPDATE user set created_login='$created_login',session='-' where password='$password'") or die (mysqli_error($connection));

		$query_login = "SELECT * FROM user WHERE username='$username' AND password='$password'";
			$result_login = $connection->query($query_login);
			$login_num = $result_login->num_rows;
			$row 	= $result_login->fetch_assoc();

			$SESSION_USER		= 	$row['session'];
			$SESSION_ID 		=	strip_tags($row['user_id']);
			$fullname			=	$row['fullname'];
			$username			=	strip_tags($row['username']);
				
		if($login_num == '0'){
			  echo '{"response":{"error": "0"}}';
			} 
		else {
			echo '{"response":{"error": "1"}}';
		///session
			$_SESSION['SESSION_USER']		= $SESSION_USER;
			$_SESSION['SESSION_ID']			= $SESSION_ID;
		}}



/* ------------- FORGOT ---------------*/
break;
case 'forgot':
include('../../sw-library/PHPMailer/PHPMailerAutoload.php');
  $pass="1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdehdsjfhewiuhfsdfuehr";
  $panjang_pass='8';
  $len=strlen($pass); 
  $start=$len-$panjang_pass; $xx=rand('0',$start); 
  $yy=str_shuffle($pass);

$error = array();

  if (empty($_POST['email'])) {
      $error[] = 'tidak boleh kosong';
    } else {
      $email= strip_tags($_POST['email']);
  }


  $passwordbaru = substr($yy, $xx, $panjang_pass);
  $password 	= mysqli_real_escape_string($connection,hash('sha256',$salt.$passwordbaru));

 if (empty($error)) {
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
		  $query="SELECT user_id,fullname,email from user where email='$email'";
		  $result= $connection->query($query) or die($connection->error.__LINE__);
		  if($result ->num_rows >0){
		    $row = $result->fetch_assoc();

		    // Konfigurasi SMTP
		    $mail = new PHPMailer;
		    $mail->isSMTP();
		    $mail->Host = $gmail_host;
		    $mail->Username = $gmail_username; // Email Pengirim
		    $mail->Password = $gmail_password; // Isikan dengan Password email pengirim
		    $mail->Port = $gmail_port;
		    $mail->SMTPAuth = true;
		    $mail->SMTPSecure = 'ssl';
		    //$mail->SMTPDebug = 2; // Aktifkan untuk melakukan debugging

		    $mail->setFrom($gmail_username, $site_name);  //Email Pengirim
		    $mail->addAddress($row['email'], $row['fullname']); // Email Penerima

		    $mail->isHTML(true); // Aktifkan jika isi emailnya berupa html
		   // Subjek email
		    $mail->Subject = 'Resset password Baru | '.$site_name.'';

		    $mailContent = '<h1>'.$site_name.'</h1><br>
		        <h3>Halo, '.$row['fullname'].'</h3><br>
		        <p>Kamu baru saja mengirim permintaan reset password akun '.$site_name.'.<br>
		        <b>Password Baru Anda : '.$passwordbaru.'</b><br><br><br>Harap simpan baik-baik akun Anda.<br><br>
		        Hormat Kami,<br>'.$site_name.'<br>Email otomatis, Mohon tidak membalas email ini</p>';
		    
		    $mail->Body = $mailContent;

		    $update ="UPDATE user SET password='$password' WHERE user_id='$row[user_id]'";
		    if($connection->query($update) === false) { 
		        die($connection->error.__LINE__); 
		        echo'Penyetelan password baru gagal, silahkan nanti coba kembali!';
		    } else{
		        echo'success';
		        if($mail->send()){
                  //echo 'Pesan telah terkirim';
                }else{
                  echo 'Mailer Error: ' . $mail->ErrorInfo;
                }
		        
		    }}
		    else   {
		       echo'Untuk Email "'.$email.'" belum terdaftar, silahkan cek kembali!';
		    }}

		    else {
		     echo'Email yang Anda masukkan salah!';
		    }}

		    else{           
		        echo'Bidang inputan masih ada yang kosong..!';
		    }

break;
}
