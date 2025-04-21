<?php
session_start();
if(empty($_SESSION['SESSION_USER']) && empty($_SESSION['SESSION_ID'])){
    header('location:../../login/');
 exit;}
else {
require_once'../../../sw-library/sw-config.php';
require_once'../../login/login_session.php';
include('../../../sw-library/sw-function.php');

switch (@$_GET['action']){
case 'delete':
  $id           = mysqli_real_escape_string($connection,epm_decode($_POST['id']));
  $employees_id = mysqli_real_escape_string($connection,epm_decode($_POST['employees_id']));
  $query_delete  ="SELECT files,permission_date,permission_date_finish from permission WHERE employees_id='$employees_id' AND permission_id='$id'";
  $result_delete = $connection->query($query_delete);
  if($result_delete->num_rows > 0){
     $row = $result_delete->fetch_assoc();

    $start = date('Y-m-d',strtotime('-1 days',strtotime($row['permission_date'])));
    $finish = date('Y-m-d',strtotime('-1 days',strtotime($row['permission_date_finish'])));
    
    $images_delete = strip_tags($row['files']);
      $directory='../../../sw-content/izin/'.$images_delete.'';
      if(file_exists("../../../sw-content/izin/$images_delete")){
          unlink ($directory);
      }

    while ($start <= $finish) {
          $start = date('Y-m-d',strtotime('+1 days',strtotime($start)));
          $deleted_absent  = "DELETE FROM presence WHERE employees_id='$employees_id' AND presence_date='$start'";
          $connection->query($deleted_absent);
    }
    $deleted  = "DELETE FROM permission WHERE  permission_id='$id'";
    if($connection->query($deleted) === true) {
      echo'success';
    } else { 
      //tidak berhasil
      echo'Data tidak berhasil dihapus.!';
      die($connection->error.__LINE__);
    }
  }
  
break;

}

}
