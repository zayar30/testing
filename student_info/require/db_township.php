<?php
    $sql     = "SELECT id,name FROM `township`";
    $result  = $mysql->query("$sql");
    $db_township = [];
    if($result->num_rows>0){
        while($row        =   mysqli_fetch_assoc($result)){
            $id           =   (int)($row["id"]);
            $town_name         =   htmlspecialchars($row["name"]);
            $data["id"]   =   $id;
            $data["town_name"] =   $town_name;
            array_push($db_township,$data);
        }
    }
?>