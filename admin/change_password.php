<?php
require $_SERVER['DOCUMENT_ROOT'].'/project/core/init.php';
if(!is_logged_in()){
  login_error_redirect();
}
include 'includes/head.php';

$hashed = $user_data['password'];
$old_password =((isset($_POST['old_password']))?sanitize($_POST['old_password']):'' );
$old_password = trim($old_password);
$password =((isset($_POST['password']))?sanitize($_POST['password']):'' );
$password = trim($password);
$confirm =((isset($_POST['confirm']))?sanitize($_POST['confirm']):'' );
$confirm = trim($confirm);
$new_hashed = password_hash($password, PASSWORD_DEFAULT);
$user_id = $user_data['id'];
$errors = array();
?>

<div id= "login-form">
  <div>

    <?php
    if($_POST){
      // form validation
      if(empty($_POST['old_password']) || empty($_POST['password']) || empty($_POST['confirm'])){
        $errors[] ='You Must Fill Out All Fields';
      }
    //new password matches confirm password
    if($password != $confirm){
      $errors[] = 'The new password and confirm new password dose not matched ';

    }

// pass is more than 6 char
      if (strlen($password)<6){
        $errors[] ='password must be at last 6 char.';
      }

     if(!password_verify($old_password,$hashed)){
       $errors[]='Password not matched with our records,please try again';
     }
      // check errors
      if(!empty($errors)){
        echo display_errors($errors);
      }else {
        // change password
       $db->query("UPDATE users SET password = '$new_hashed' WHERE id = '$user_id'");
       $_SESSION['success_flash'] = 'Your password has been updated ';
       header('Location:index.php');
      }
    }
     ?>

  </div>
  <h2 class = "text-center">Change Password</h2><hr>
  <form acction ="change_password.php" method="post" >
  <div class ="form-group">
  <label for="old_password">OLD PASSWORD:</label>
  <input type="password" name="old_password" id="old_password" class="form-control" value="<?=$old_password;?>">
  </div>

  <div class ="form-group">
  <label for="password">NEW Password:</label>
  <input type="password" name="password" id="password" class="form-control" value="<?=$password;?>">
  </div>
  <div class ="form-group">
  <label for="confirm">Confirm new Password:</label>
  <input type="password" name="confirm" id="confirm" class="form-control" value="<?=$confirm;?>">
  </div>
  <div class ="form-group">
    <a href="index.php" class ="btn btn-default">Cancel</a>
    <input type="submit" value ="Login" class="btn btn-primary">
  </div>

  </form>
  <p class="text-right"><a href="/project/index.php" alt="home">Visit Site</a></p>
</div>

<?php include 'includes/footer.php'; ?>
