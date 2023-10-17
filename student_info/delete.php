<?php
  session_start();
  require('common/common_usage.php');
    $id     = (int)($_GET["id"]);
    $id     = $mysql->real_escape_string($id);
    // $sql    = "DELETE FROM `student_info` WHERE Student_ID ='".$id."' ";

    $today  =date("y-m-d H:i:s");
    $sql    = "UPDATE `student_info` SET  deleted_at = '".$today ."' WHERE Student_ID = '".$id."' ";

    $result = $mysql->query($sql);
    $loca   = $base_url."index.php?delete";
    header("Refresh: 0;url=$loca");
    exit();
?>