<?php
    require('common/common_usage.php');
    include('require/db_township.php');
    include('require/db_hobbies.php');

    $error          = false;
    $error_massage  = "";
    $form           = true;
    $hobbies[]      = "";
    $township_id    = "";

  if(isset($_POST["submit"])&& $_POST["form-sub"] == 1){
        $upload_process = false;
        $id     =   (int)($_POST["id"]);
        $id     =   $mysql->real_escape_string($id);
        $name   =   $mysql->real_escape_string($_POST["name"]);
        $age    =   $mysql->real_escape_string($_POST["age"]);
        $grade  =   $mysql->real_escape_string($_POST["grade"]);
        $date   =   $mysql->real_escape_string($_POST["date"]);
        $townships = $mysql->real_escape_string($_POST["township"]);
        $gender =   (int)($_POST["gender"]);
        $process_error  = false;
    if($name == ""){
      $process_error = true;
      $error         = true;
      $error_massage .= "Fill name <br />";
    }
    if($age == ""){
      $process_error = true;
      $error         == true;
      $error_massage .= "Fill age <br />";
    }
    if($grade == ""){
      $process_error = true;
      $error         = true;
      $error_massage .= "Fill grade <br />";
    }
    if($date == ""){
      $process_error = true;
      $error         = true;
      $error_massage .= "Fill date <br />";
    }
    if($townships == ""){
      $process_error = true;
      $error         = true;
      $error_massage .= "Fill township <br />";
    }
    if(!isset($_POST["hobbies"])){
      $process_error = true;
      $error         = true;
      $error_massage .= "Fill hobbies <br />";
    }  else{
      $hobbies = $_POST["hobbies"];
    }
    if(isset($_POST['file'])){
      $tmp_name  = $_FILES["file"]["tmp_name"];
      $file_name = $_FILES["file"]["name"];
      $file = $_FILES["file"]; 
      $allow_extension = array("jpg","jpeg","gif","png","img");
      $explode = explode(".", $file_name);
      $extension = strtolower(end($explode));
      if(in_array($extension, $allow_extension)){
        if(getimagesize($tmp_name)){
          $uniq_name = uniqid() . "_" . date("Y-m-d", time()) . "." . $extension;
          $upload_process = true;
        
        }else{
              $error = true;
              $error_massage = "Upload Valid image <br />";
          }
      }else{
          $error = true;
          $error_massage = "Upload Valid image extension <br />";
        }
    }
    if($upload_process == true){
      $file_path = "upload/". $id."/";
      if(!file_exists($file_path)){
                mkdir($file_path, 0777, true);
      }
      $destination = $file_path . $uniq_name;
      if(move_uploaded_file($tmp_name, $destination)){
          //do nothing//
      }
    }
    $name_check_sql = "SELECT Student_ID FROM `student_info` where stu_name ='".$name."' AND Student_ID != '".$id."' AND deleted_at IS NULL ";
    $result_check   = $mysql->query($name_check_sql);
    if($result_check->num_rows>0){
      $process_error = true;
      $error         = true;
      $error_massage = "This student name(".$name.") is already exits.";
    }
    if($process_error == false){
      if($upload_process == true){
        
        $update_sql = "UPDATE `student_info` as `T01` LEFT JOIN township as `T02`
                        ON T01.township_id = T02.id SET 
                       T01.stu_name         = '".$name."',
                       T01.age              = '".$age."',
                       T01.grade            = '".$grade."',
                       T01.joined_date      = '".formatDateString($date)."',
                       T01.gender           = '".$gender."',
                       T01.upload_file      = '".$uniq_name."', 
                       T01.township_id      =  T02.id
                       WHERE T01.Student_ID = '".$id."'"  ;
        $result     = $mysql->query($update_sql);
      }else{
        $update_sql = "UPDATE `student_info` as `T01` LEFT JOIN township as `T02`
                        ON T01.township_id = T02.id SET 
                       T01.stu_name         = '".$name."',
                       T01.age              = '".$age."',
                       T01.grade            = '".$grade."',
                       T01.joined_date      = '".formatDateString($date)."',
                       T01.gender           = '".$gender."',
                       T01.township_id      =  T02.id
                       WHERE T01.Student_ID = '".$id."'"  ;
        $result     = $mysql->query($update_sql);
      }

        
        
      if($result){
        $delete_hobby_sql = "DELETE FROM `student_hobby` WHERE student_id = '".$id."'";
        $mysql->query("$delete_hobby_sql");
        foreach($hobbies as $value=>$hobbies_id){
          $ins_hobby_upd_sql = "INSERT INTO `student_hobby`(student_id,hobby_id) VALUES ('".$id."','". $hobbies_id ."')";
          $mysql->query("$ins_hobby_upd_sql");
         
        
        }
        $loca=$base_url."index.php?update";
        header("Refresh: 0;url=$loca");
        exit();
      }
    }  
  }else{
      $id             = (int)($_GET["id"]);
      $sql            = "SELECT T01.*,
                      T02.id as township_id,
                      T02.name as township_name
                      FROM student_info T01
                      LEFT JOIN township T02
                      ON T01.township_id = T02.id
                      WHERE Student_ID = ' " . $id ." '  ";
      $result         = $mysql->query($sql);

      if($result->num_rows<=0){
        $form          = false;
        $error_massage = "Something Wrong. Please Contact Adminstrator.";
      } else{
        while($row      =   mysqli_fetch_assoc($result)){
          $name       =   htmlspecialchars($row["stu_name"]);
          $age        =   (int)($row["age"]);
          $grade      =   htmlspecialchars( $row["grade"]) ;
          $unf_date   =   htmlspecialchars($row["joined_date"]);
          $date       =   formatmdY($unf_date);
          $gender     =   (int)($row["gender"]);
          $townships   =   htmlspecialchars($row["township_name"]);
          $township_id =   (int)($row["township_id"]);
          $upload_file =   $row['upload_file'];
          require("require/hobbies_student.php");
          if($result_hobby->num_rows>0){
            foreach($result_hobby as $hobby_row){
              $hobbies [] = (int)($hobby_row['hobbies_id']);
              
            }
          }
        }
      }          
    }
?>
  <?php
    include('template/require_header.php');
  ?>
<h3>Edit Form</h3>

<div>
  <?php
    if($error == true){
  ?> 
    <p style="color:red"><?php echo $error_massage; ?></p>
  <?php
    }
    if($form == true){  ?>
 
  
  <form action="<?php echo $base_url; ?>edit.php" method="POST" enctype = "multipart/form-data">

  <?php
      if($upload_file == ''){
  ?>
      <div style = "width:30px;height:50px;overflow:hidden; " id = "preview_img_wrapper">
          <img src = "<?php $base_url?>/img/noimage.png" style ="width:100%" id = "preview_img" >
      </div>
  <?php
      }else{
  ?>        
          <div style = "width:30px;height:50px;overflow:hidden; " id = "preview_img_wrapper">
              <img src = "<?php echo $base_url.getfullImage($upload_file,$id); ?>" style ="width:100%" id = "preview_img">
          </div>
  <?php        
      }
  ?>
    <label for = "file"> Upload file </label><br /> <br />
    <input type = "file" id = "file" name = "file" value="" onchange = "changePhoto(event)">

    <label for="student_name">Student Name</label>
    <input type="text" id="student_name" name="name" placeholder="Enter student name.." value="<?php echo $name; ?>">

    <label for="student_age">Student Age</label>
    <input type="number" id="student_age" name="age" placeholder="Enter student age.." value="<?php echo $age; ?>">

    <label for="student_grade">Student Grade</label>
    <input type="text" id="student_grade" name="grade" placeholder="Enter student grade.." value="<?php echo $grade; ?>">
    
    <label for="student_grade">Register Date</label>
    <input type="text" id="student_register_date" name="date"  value="<?php echo $date; ?>">
    
    <label for="gender">Gender</label> <br />
    <label for="male">Male</label>
    <input type="radio" id="male" name="gender"  value="0" <?php if($gender == 0){ echo 'checked'; }?>>
    <label for="female">Female</label>
    <input type="radio" id="female" name="gender"  value="1" <?php if($gender == 1){ echo 'checked'; }?>><br/>

    <label for="township">Township</label>
    <select name="township">
      <option value="">Select Township</option>
      <?php  
        foreach($db_township as $key => $township){
          ?>
        <option value="<?php echo $township["id"];?>" <?php if ($township["id"] == $township_id) { echo 'selected'; } ?>>
          <?php echo $township["town_name"];?>
        </option>
    
      <?php
        }
      ?>
      
    </select>

    <label for="hobbies">Hobbies</label><br/><br/>
        <?php
          foreach($db_hobbies as $key => $hobby){
        ?>
      <input type="checkbox" id="hobby-<?php echo $hobby["id"]; ?>" value="<?php echo $hobby["id"]; ?>" <?php if(in_array($hobby["id"],$hobbies)) { echo "checked"; } ?> name="hobbies[]">
      <label for="hobby-<?php echo $hobby["id"]; ?>"><?php echo $hobby["hobby_name"]; ?></label>

    <?php } ?>

    <input type="submit" value="Submit" name="submit">
    <input type="hidden" value="1" name="form-sub">
    <input type="hidden" value="<?php echo $id; ?>" name="id">


  </form>
  <?php } ?>
  
 
</div>
<script>
      $( function() {
        $( "#student_register_date" ).datepicker({
          maxDate: 0
        });
           
      } );
  </script>
  <script>
    function changePhoto(event){
      const file = event.target.files[0];
      const previewImage = document.getElementById('preview_img');
      const wrapper      = document.getElementById('preview_img_wrapper');
      if(file.type == 'image/jpeg '|| file.type == 'image/jpg' || file.type == 'image/png' || file.type == 'image/gif '){
        wrapper.style.display = 'block';
        const reader = new FileReader();
        reader.onload = function (event) {
        previewImage.src = event.target.result;
        };
        reader.readAsDataURL(file);
      }else{
        alert("Wrong Photo");
        return false;
      }
    }
  </script>
<?php
    include('template/require_footer.php');
?>
