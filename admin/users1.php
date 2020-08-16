<?php
require_once'../core/init.php';
include 'includes/head.php';
include 'includes/navigation.php';

if(isset($_GET['add'])){
  $name =((isset($_POST['name']))?sanitize($_POST['name']):'');
  $email =((isset($_POST['email']))?sanitize($_POST['email']):'');
  $password =((isset($_POST['password']))?sanitize($_POST['password']):'');
  $confirm =((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
  $permissions =((isset($_POST['permissions']))?sanitize($_POST['permissions']):'');
  $errors = array();
  if($_POST){
    $emailQuery=$db->query("SELECT * FROM users WHERE email='$email'");
    $emailCount =mysqli_num_rows($emailQuery);
    if($emailCount !=0){
      $errors[]='That email already exist in database';
    }
    $required = array('name','email','password','confirm');
    foreach ($required as $f ) {
      // code...
      if(empty($_POST[$f])){
        $errors[] ='You must fill out all fields';
        break;
      }
    }
    if(strlen($password)<6){
      $errors[]='password at last 6 char ';
    }
    if($password !=$confirm){
      $errors[]='password and confirm password doesnot matched..!';
    }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
      $errors[]='Your email is not correct';
    }
  if(!empty($errors)){
    echo display_errors($errors);
  }else{
    //add user to database
    $hashed = password_hash($password,PASSWORD_DEFAULT);
    $db->query("INSERT INTO users (full_name,email,password,permission) VALUES ('$name','$email','$hashed','editor') ");
    $_SESSION['success_flash']= 'Yor are successfully registared';
    header('Location:login.php');
  }
  }
?>
<h2 class="text-center">ADD A NEW USER</h2><hr>
<form align="center" action="users1.php?add=1" method="post">

<div class="form-group col-md-14">
<label for ="name">Full Name</lable>
  <input type="text" name="name" id="name" class="form-control" value="<?=$name;?>" >
</div>
<div class="form-group col-md-14">
<label for ="email">Email</lable>
  <input type="text" name="email" id="email" class="form-control" value="<?=$email;?>" >
</div>
<div class="form-group col-md-14">
<label for ="password">Password</lable>
  <input type="password" name="password" id="password" class="form-control" value="<?=$password;?>" >
</div

<div class="form-group col-md-14">
<label for ="confirm">Confirm Password</lable>
  <input type="password" name="confirm" id="confirm" class="form-control" value="<?=$confirm;?>" >
</div>

<div class="form-group col-md-7 text-right" style="margin-top:25px;">
  <a href="users1.php" class="btn btn-default">Cancel</a> &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
  <input type="submit" value="Add User" class="btn btn-primary"> 
</div>
</form>
<?php
} include'includes/footer.php';?>
