<?php
$id = $_GET['id'];
$targetUser = readUser($id);
if ($targetUser == null || $targetUser->level == 'admin') {
    header('Location: ./?page=user/list');

}

$nameError = $usernameError = $passwdError = '';

$name = $targetUser->name;
$username = $targetUser->username;
$photo = $targetUser->photo;

if (isset($_POST['username'], $_POST['password'], $_POST['name'], $_FILES['photo'])) {
    $photo = $_FILES['photo'];
    $name = trim($_POST['name']);
    $username = trim($_POST['username']);
    $passwd = trim($_POST['password']);
    if (empty($name)) {
        $nameError = "Name is required";
    }
    if (empty($username)) {
        $usernameError = "Username is required";
    }
    if (!empty($passwd) && (strlen($passwd) < 6 || strlen($passwd) > 25)) {
        $passwdError = "Password must be at least 6 characters";
    }
    if ($targetUser->username !== $username && usernameExist($username)) {
        $usernameError = 'Username is currently unavailable!';
    }
    try {
        if (empty($nameError) && empty($usernameError) && empty($passwdError)) {
            if (updateUser($id, $name, $username, $passwd, $photo)) {
                echo '<div class="alert alert-success" role="alert">
                    User updated successfully! <a href="./?page=user/list" class="alert-link">Go back to list</a>
                </div>';
                $targetUser = readUser($id);
            } else {
                echo '<div class="alert alert-danger" role="alert">
                    Failed to update user!
                </div>';
            }
        }
    } catch (Exception $e) {
        echo '<div class="alert alert-danger" role="alert">
                 ' . $e->getMessage() . '
                </div>';
    }
}
?>

<form method="post" action="./?page=user/update&id=<?php echo $id ?>" class="col-md-8 col-lg-6 mx-auto"
    enctype="multipart/form-data">
    <h3>Update User</h3>
    <div class="d-flex justify-content-center">
        <input name="photo" type="file" id="profileUpload" hidden>
        <label role="button" for="profileUpload">
            <img src="<?php echo $targetUser->photo ?? './assets/images/emptyuser.png' ?>" class="rounded img-thumbnail"
                style="max-width: 200px;">
        </label>
    </div>
    <div class="mb-3">
        <label class="form-label">Name</label>
        <input name="name" value="<?php echo $name; ?>" type="text" class="form-control
            <?php echo empty($nameError) ? '' : 'is-invalid' ?>">
        <div class="invalid-feedback"><?php echo $nameError; ?></div>
    </div>
    <div class="mb-3">
        <label class="form-label">Username</label>
        <input name="username" value="<?php echo $username; ?>" type="text" class="form-control
            <?php echo empty($usernameError) ? '' : 'is-invalid' ?>">
        <div class="invalid-feedback"><?php echo $usernameError; ?></div>
    </div>
    <div class="mb-3">
        <label class="form-label">Password</label>
        <input name="password" type="password" class="form-control
            <?php echo empty($passwdError) ? '' : 'is-invalid' ?>">
        <div class="invalid-feedback"><?php echo $passwdError; ?></div>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>