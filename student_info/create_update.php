<?php
    require('common/common_usage.php');
    $error          = false;
    $error_massage  = "";
    $name           = "";
    $age            = "";
    $grade          = "";
    $date           = "";
    $townships      = "";
    $hobbies[]      = "";
    $process_error  = false;
    $upload_process = false;
   
    include('require/db_township.php');
    include('require/db_hobbies.php');
  if(isset($_POST["submit"]) && $_POST['form-sub'] == 1 ){
    $name   = mysqli_real_escape_string($mysql, $_POST["name"]);
    $age    = mysqli_real_escape_string($mysql, $_POST["age"]);
    $grade  = mysqli_real_escape_string($mysql, $_POST["grade"]);
    $date   = mysqli_real_escape_string($mysql, $_POST["date"]);
    $gender = (int)"";
    if($_POST["gender"] == 0){
      $gender = 0;
     }else{
      $gender = 1;
     }
    $townships = $mysql->real_escape_string($_POST["township"]);
    $tmp_name  = $_FILES["file"]["tmp_name"];
    $file_name = $_FILES["file"]["name"];
    $file = $_FILES["file"];
    if($file_name == ""){
      $process_error = true;
      $error         = true;
      $error_massage = "Upload file pls <br/>";
    }else{  
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
   
    $name_check_sql = "SELECT Student_ID FROM `student_info` where stu_name ='".$name."' AND deleted_at IS NULL ";
    $result_check   = $mysql->query($name_check_sql);
    if($result_check->num_rows>0){
      $process_error = true;
      $error         = true;
      $error_massage = "This student name(".$name.") is already exits.";
    }
    if($process_error == false){
      $date       =   formatDateString($_POST["date"]);
      if($upload_process == true){
        $create_sql =   "INSERT INTO `student_info` 
                          (stu_name,
                          age,
                          grade,
                          township_id,
                          joined_date,
                          upload_file,
                          gender)
                          VALUES
                          ('".$name."',
                          '".$age ."',
                          '".$grade."',
                          '".$townships."',
                          '".$date ."',
                          '".$uniq_name ."',
                          '".$gender ."')";
        $result_create  = $mysql->query($create_sql);
      }  
      
      if($result_create){
        $loca        = $base_url."index.php?insert";
        $insert_id   = $mysql->insert_id;
        foreach($hobbies as $hobby_id){
          $hobby_ins_sql = "INSERT INTO `student_hobby` 
                            (student_id,hobby_id)
                            VALUES
                            (' " . $insert_id . " ',' " . $hobby_id . " ')";
          $mysql->query($hobby_ins_sql);
        }
      }
      if($upload_process == true){
        $file_path = "upload/". $insert_id."/";
        if(!file_exists($file_path)){
                  mkdir($file_path, 0777, true);
        }
        $destination = $file_path . $uniq_name;
        if(move_uploaded_file($tmp_name, $destination)){
            //do nothing//
        }
      }
      $loca=$base_url."index.php?insert";
      header("Refresh: 0;url=$loca");
      exit();
    }
    

  }
?>
  <?php
    include('template/require_header.php');
  ?>

<body>

<h3>Student Form</h3>

<div>
  <?php
    if($error == true){
  ?>
    <p style="color:red"><?php echo $error_massage; ?></p>
  <?php
    }
  ?>
  
  <form action="<?php echo $base_url; ?>create_update.php" method="POST" enctype = "multipart/form-data">

  <div style = "width:30px;height:50px;overflow:hidden;display:none;" id="preview_img_wrapper">
      <img src = "" style ="width:100%" id = "preview_img">
  </div>

    <label for = "file"> Upload file </label><br /> <br />
    <input type = "file" id = "file" name = "file" value="" onchange="changePhoto(event)">

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
    <input type="radio" id="male" name="gender"  value="0" checked="checked">
    <label for="female">Female</label>
    <input type="radio" id="female" name="gender"  value="1"> <br /> <br />

    <label for="township">Township</label>
    <select name="township">
      <option value="">Select Township</option>
      <?php  
        foreach($db_township as $key => $township){
      ?>
        <option value="<?php echo $township["id"];?>" <?php if ($township["id"] == $townships) { echo 'selected'; } ?>>
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


  </form>
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
</div>
<?php
    include('template/require_footer.php');
?>