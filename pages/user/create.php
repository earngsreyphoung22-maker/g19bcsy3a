<?php
$name = $username = '';
$nameErr = $usernameErr = $passwdErr = '';
if (isset($_POST['name'], $_POST['username'], $_POST['passwd'])) {
    $name = trim($_POST['name']);
    $username = trim($_POST['username']);
    $passwd = trim($_POST['passwd']);
    if (empty($name)) {
        $nameErr = 'please input name!';
    }
    if (empty($username)) {
        $usernameErr = 'please input username!';
    }
    if (empty($passwd)) {
        $passwdErr = 'please input password!';
    }
    if (strlen($passwd) < 6 || strlen($passwd) > 64) {
        $passwdErr = 'password must be at least 6 characters!';
    }

    if (usernameExists($username)) {
        $usernameErr = 'Please choose another username!';
    }
    if (empty($nameErr) && empty($usernameErr) && empty($passwdErr)) {
        try {
            if (createUser($username, $name, $passwd, $photo)) {
                $name = $username = '';
                echo '<div class="alert alert-success" role="alert">
                Create user successful! <a href="./?page=userlist"</a>
              </div>';
            } else {
                echo '<div class="alert alert-danger" role="alert">
                Create user failed! Please try again.
              </div>';
            }
        } catch (Exception $e) {
            echo '<div class="alert alert-danger" role="alert">
                ' . $e->getMessage() . '
              </div>';
        }
    }

}

?>
<form method="post" action="./?page=login" class="col-md-8 col-lg-6 mx-auto">
    <div class="d-flex justify-content-center">
        <input name="photo" type="file" id="profileUpload" hidden>
        <label role="button" for="profileUpload">
            <img src="<?php echo loggedUserIN()->photo ?? './assets/image/emptyUser.jpg' ?>"
                class="rounded img-thumbnail" style="max-width: 100px">
        </label>
    </div>
    <h3>Login page</h3>
    <div class="mb-3">
        <label class="form-label">Username</label>
        <input name="username" value="<?php echo $username ?>" type="text" class="form-control
    <?php echo empty($usernameErr) ? '' : 'is-invalid' ?>">
        <div class="invalid-feedback">
            <?php echo $usernameErr ?>
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Password</label>
        <input name="passwd" type="password" class="form-control
    <?php echo empty($passwdErr) ? '' : 'is-invalid' ?>">
        <div class="invalid-feedback <?php echo $passwdErr ?></div>">
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="exampleCheck1">
            <label class="form-check-label" for="exampleCheck1">Show Password</label>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>

</form>