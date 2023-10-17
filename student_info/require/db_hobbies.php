<?php
    $sql     = "SELECT id,name FROM `hobbies`";
    $result  = $mysql->query("$sql");
    $db_hobbies = [];
    if($result->num_rows>0){
        while($row        =   mysqli_fetch_assoc($result)){
            $id           =   (int)($row["id"]);
            $hobby_name         =   htmlspecialchars($row["name"]);
            $data["id"]   =   $id;
            $data["hobby_name"] =   $hobby_name;
            array_push($db_hobbies,$data);
        }
    }
?>