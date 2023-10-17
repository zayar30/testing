<?php
session_start();
require('common/common-url.php');
require('common/db.php');
$error = false;
$error_massage = '';
$username = '';
if (isset($_POST['submit']) && $_POST['form_sub'] == 1) {
    $username     = $mysql->real_escape_string($_POST['username']);
    $password     = $_POST['password'];
    $encrypt_pass = md5(md5($password).$sha_key); 
    $sql          = "SELECT * FROM user WHERE username = '$username'";
    $result       = $mysql->query($sql);
    $res_row      = $result->num_rows;
    if ($res_row >= 1) {
      while($row = $result->fetch_assoc()){
        $id       = $row['id'];
        $db_pass  = $row['password'];
        if($db_pass == $encrypt_pass){
          $_SESSION['username'] = $username;
          $_SESSION['id']       = $id;
          $loca = $base_url."index.php";
          header("Refresh: 0;url=$loca");
          exit();
        }else{
          $error = true;
          $error_massage = "password is wrong!";
        }
      }
    } else {
        $error = true;
        $error_massage = "username doesn't exit!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {font-family: Arial, Helvetica, sans-serif;}
form {border: 3px solid #f1f1f1;}

input[type=text], input[type=password] {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

button {
  background-color: #04AA6D;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
}

button:hover {
  opacity: 0.8;
}

.cancelbtn {
  width: auto;
  padding: 10px 18px;
  background-color: #f44336;
}

.imgcontainer {
  text-align: center;
  margin: 24px 0 12px 0;
}

img.avatar {
  width: 40%;
  border-radius: 50%;
}

.container {
  padding: 16px;
}

span.psw {
  float: right;
  padding-top: 16px;
}

/* Change styles for span and cancel button on extra small screens */
@media screen and (max-width: 300px) {
  span.psw {
     display: block;
     float: none;
  }
  .cancelbtn {
     width: 100%;
  }
}
</style>
</head>
<body>

<h2>Login Form</h2>
<?php 
  if($error == true){    
?>
<p style ="color:red"><?php echo $error_massage; ?></p>
<?php
  }
?>

<form action="<?php echo $base_url; ?>login.php" method="post">

  <div class="container">
    <label for="uname"><b>Username</b></label>
    <input type="text" placeholder="Enter Username" name="username" value = "<?php echo $username; ?>" required>

    <label for="psw"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="password" required>
        
    <button type="submit" name ="submit">Login</button>
    <input type = "hidden" name= "form_sub" value = "1">
   
</form>

</body>
</html>
