<?php
$id = $_GET["id"];
$targetUser = readUser($id);
if ($targetUser == null || $targetUser->level === 'admin') {
    header('Location: ./?page=user/list');

}


$nameErr = $usernameErr = $passwdErr = '';
$name = $targetUser->name;
$username = $targetUser->username;
if (isset($_POST['name'], $_POST['username'], $_POST['passwd'], $_FILES['photo'])) {
    $photo = $_FILES['photo'];
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
    if (usernameExists($username)) {
        $usernameErr = 'please choose another username!';
    }
    if (empty($nameErr) && empty($usernameErr) && empty($passwdErr)) {
        try {
            if (updateUser($id, $name, $username, $password, $photo)) {
                echo '<div class="alert alert-success" role="alert">
            Update successful! <a href="./?page=user/list" class="alert-link">go to List</a>.
            </div>';
            } else {
                echo '<div class="alert alert-danger" role="alert">
            Update failed! Please try again.
            </div>';
            }
        } catch (Exception $e) {
            echo '<div class="alert alert-danger" role="alert">
                    upload failed please try again.
                    </div>';
        }
    }
}
?>


<form method="post" action="./?page=user/update" enctype="multipart/form-data" class="col-md-8 col-lg-6 mx-auto">
    <h3>Update</h3>
    <div class="d-flex justify-content-center">
        <input name="photo" type="file" id="profileUpload" hidden>
        <label role="button" for="profileUpload">
            <img src="./assets/image/emptyUser.jpg" class="rounded img-thumbnail" style="width: 200px">
        </label>
    </div>
    <div class="mb-3">
        <label for="exampleInputName" class="form-label">Name</label>
        <input name="name" type="text" value="<?php echo $name ?>" class="form-control
            <?php echo empty($nameErr) ? '' : 'is-invalid'; ?>">
        <div class="invalid-feedback">
            <?php echo $nameErr ?>
        </div>
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input name="username" type="text" value="<?php echo $username ?>" class="form-control
            <?php echo empty($usernameErr) ? '' : 'is-invalid'; ?>">
            <div class="invalid-feedback">
                <?php echo $usernameErr ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input name="passwd" type="password" class="form-control
            <?php echo empty($passwdErr) ? '' : 'is-invalid'; ?>">
                <div class="invalid-feedback">
                    <?php echo $passwdErr ?>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
</form>