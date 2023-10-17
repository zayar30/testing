<?php
session_start();
if(isset($_SESSION['id']) || isset($_SESSION['username'])){
  $check_user_sql = "SELECT id FROM `user` WHERE id ='" .$_SESSION['id']."'";
  $result = $mysql->query($check_user_sql);
  $num_row = $result->num_rows;
  if($num_row <= 0){
      $loca=$base_url."login.php";
      header("Refresh:0;url=$loca");
      exit();
  }  
}else{
    $loca=$base_url."login.php";
    header("Refresh: 0;url=$loca");
    exit();
  
}
?>