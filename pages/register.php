<?php
    if(isset($_POST['name'], $_POST['username'], $_POST['passwd'], $_POST['confirm_passwd'])){
      $name = $_POST['name'];
      $username= $_POST['username'];
      $passwd = $_POST['passwd'];
      $confirmpasswd =$_POST['confirmpasswd'];
      if(empty($name)){
        $nameErr = 'please input name!';
      }
      if(empty($username)){
        $usernameErr = 'please input username';
      }
      if(empty($passwd)){
        $usernameErr = 'please input passwd';
      }
      if($passwd !== $confirmpasswd){
        $usernameErr = 'password do not match';
      }
    }
?>

<form method="post" action="./?page=register" class="col-md-8 col-lg-6 mx-auto">
        <h1>Register Page</h1>
  <div class="mb-3">
    <label class="form-label">Name</label>
    <input name="name" type="text" class="form-control">
  </div>
  <div class="mb-3">
    <label class="form-label">Username</label>
    <input name="username" type="text" class="form-control">
  </div>
  <div class="mb-3">
    <label class="form-label">Password</label>
    <input name="password" type="password" class="form-control">
  </div>
  <div class="mb-3">
    <label class="form-label">Confirm Password</label>
    <input name="confirmpassword" type="password" class="form-control">
  </div>
 
  <button type="submit" class="btn btn-primary">Submit</button>
  
</form>
