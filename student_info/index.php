<?php
    require('common/common_usage.php');
    $success        =  false;
    $success_msg    =  "";
    $read_sql       = "SELECT 
                        T01.Student_ID,
                        T01.stu_name,T01.age,
                        T01.grade,
                        T01.joined_date,
                        T01.gender,
                        T01.upload_file,
                        T02.name as township_name
                        from  `student_info` as `T01` 
                        LEFT JOIN  `township` as `T02` 
                        ON T01.township_id = T02.id 
                        WHERE deleted_at is NULL 
                        ORDER BY Student_ID DESC";
    $read_res       = $mysql->query ($read_sql);
?>
<?php
    include('template/require_header.php');?>
<body >

<h1>Student Table</h1>
    <?php
        if(isset($_GET["insert"])){
            $success     = true;
            $success_msg = "Insert success";
        }else if (isset($_GET["update"])){
            $success     = true;
            $success_msg = "Update success";
        }else if (isset($_GET["delete"])){
            $success     = true;
            $success_msg = "Delete success";
        }
            if($success == true){

    ?>
<p style="color:green;"><?php echo $success_msg;?></p>
    <?php } ?>

<div>
<a href="<?php echo $base_url; ?>create_update.php" style="background:green ;color:black ;text-decoration:none;padding:10px; ">create</a> 
</div><br/>
<table id="customers">
  <tr>
    <th>Image</th>
    <th>ID</th>
    <th>Name</th>
    <th>Age</th>
    <th>Grade</th>
    <th>Joined_Date</th>
    <th>Gender</th>
    <th>Township</th>
    <th>Hobbies</th>
    <th>Action</th>

  </tr>
  <?php
            
    while($row      =   mysqli_fetch_assoc($read_res)){
        $name       =   htmlspecialchars($row["stu_name"]);
        $age        =   (int)($row["age"]);
        $grade      =   htmlspecialchars( $row["grade"]) ;
        $id         =   $row["Student_ID"];
        $date       =   htmlspecialchars($row["joined_date"]);
        $township   =   htmlspecialchars($row["township_name"]);
        $gender     =   (int)$row["gender"];
        $upload_file =  $row["upload_file"];
        $edit_url   =   $base_url."edit.php?id=".$id;
        $delete_url =   $base_url."delete.php?id=".$id;

    ?>
    <tr>
        <td>
            <?php
                if($upload_file == ''){
            ?>
                <div style = "width:30px;height:50px;overflow:hidden; ">
                    <img src = "<?php $base_url?>/img/noimage.png" style ="width:100%">
                </div>
            <?php
                }else{
            ?>        
                    <div style = "width:30px;height:50px;overflow:hidden; ">
                        <img src = "<?php echo $base_url.getfullImage($upload_file,$id); ?>" style ="width:100%">
                    </div>
            <?php        
                }
            ?>
        </td>
        <td><?php echo $id; ?></td>
        <td><?php echo $name; ?></td>
        <td><?php echo $age; ?></td>
        <td><?php echo $grade; ?></td>
        <td><?php echo $date; ?></td>
        <td><?php echo $common_gender["$gender"]; ?></td>
        <td><?php echo $township; ?></td>
        <td>
            <?php 
                require('require/hobbies_student.php'); 
                $hobbies_string = "";
                    while ($hobby_row = mysqli_fetch_assoc($result_hobby)) {
                        $hobbies = $hobby_row["hobbies"];
                        $hobbies_string .= $hobbies . ",";
                    }
                    $hobbies_string = rtrim($hobbies_string, ',');
                    echo $hobbies_string;
            ?>
        </td>
        <td><a href="<?php echo $edit_url; ?>">Edit</a>
        <a href="<?php echo $delete_url; ?>">Delete</a></td>
        


    </tr>
  <?php } ?>
  
</table>
<?php
    include('template/require_footer.php');
?>



