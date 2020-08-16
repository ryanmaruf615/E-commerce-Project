<?php
require $_SERVER['DOCUMENT_ROOT'].'/project/core/init.php';
include 'includes/head.php';

$email =((isset($_POST['email']))?sanitize($_POST['email']):'' );
$email = trim($email);
$password =((isset($_POST['password']))?sanitize($_POST['password']):'' );
$password = trim($password);
//$hashed = password_hash($password,PASSWORD_DEFAULT);
$errors = array();
?>
<style>
body{
  background-image:  url("/project/images/headerlogo/front.png");
  background-size: 100vw 100vh;
  background-attachment: fixed;
}
</style>
<div id= "login-form">
  <div>

    <?php
    if($_POST){
      // form validation
      if(empty($_POST['email']) || empty($_POST['password'])){
        $errors[] ='Please enter email And apassword';
      }
// email validation
      if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
        $errors[]='entered email is imvalide';
      }
// pass is more than 6 char
      if (strlen($password)<6){
        $errors[] ='password must be at last 6 char.';
      }
     //check email exist in db
     $query = $db->query("SELECT * FROM users WHERE email='$email' ");
     $user = mysqli_fetch_assoc($query);
     $userCount =mysqli_num_rows($query);
     if($userCount < 1){
       $errors[] = 'The given error is not in Database';
     }

     if(!password_verify($password, $user['password'])){
       $errors[]='Password not matched please try again';
     }
      // check errors
      if(!empty($errors)){
        echo display_errors($errors);
      }else {
        // log user in
        $user_id = $user['id'];
        login($user_id);
      }
    }
     ?>

  </div>
  <h2 class = "text-center">Login</h2><hr>
  <form acction ="login.php" method="post" >
  <div class ="form-group">
  <label for="email">Email:</label>
  <input type="text" name="email" id="email" class="form-control" value="<?=$email;?>">
  </div>

  <div class ="form-group">
  <label for="password">Password:</label>
  <input type="password" name="password" id="password" class="form-control" value="<?=$password;?>">
  </div>
  <div class ="form-group">
    <input type="submit" value ="Login" class="btn btn-primary">
  </div>
  </form>
  <h2><p class="text-right"><a href="/project/index.php" alt="home">Visit Site</a></p></h2>
  <p class="text-right"><a href="/project/admin/users1.php?add=1" alt="home">If New Then register</a></p>
</div>

<?php include 'includes/footer.php'; ?>
