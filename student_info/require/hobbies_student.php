<?php
    $sql     = "SELECT T02.name as hobbies,
                T02.id as hobbies_id
                FROM `student_hobby` T01
                LEFT JOIN `hobbies` T02 
                ON T01.hobby_id = T02.id
                WHERE student_id = '". $id ."'";
    $result_hobby = $mysql->query("$sql");
   
?>