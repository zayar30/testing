<?php
 $mysql = mysqli_connect('localhost','root','',"student");
 if($mysql->connect_error){
    die("connect failed" . $mysql->connect_error);

 }
?>