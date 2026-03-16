<?php
$oldPasswd = $newPasswd = $confirmNewPasswd = '';
$oldPasswdErr = $newPasswdErr = '';
$response = null;

$photo = empty(getUserImage($_SESSION['user_id'])) ? 'image.png' : getUserImage($_SESSION['user_id']);

if (isset($_POST['changePasswd'], $_POST['oldPasswd'], $_POST['newPasswd'], $_POST['confirmNewPasswd'])) {
    $oldPasswd = trim($_POST['oldPasswd']);
    $newPasswd = trim($_POST['newPasswd']);
    $confirmNewPasswd = trim($_POST['confirmNewPasswd']);
    if (empty($oldPasswd)) {
        $oldPasswdErr = 'please input your old password';
    }
    if (empty($newPasswd)) {
        $newPasswdErr = 'please input your new password';
    }
    if ($newPasswd !== $confirmNewPasswd) {
        $newPasswdErr = 'password does not match';
    }
    if (!isUserHasPassword($oldPasswd)) {
        $oldPasswdErr = 'password is incorrect';
    }
    if (empty($oldPasswdErr) && empty($newPasswdErr)) {
        if (setUserNewPassword($newPasswd)) {
            header('Location: ./?page=logout');
        } else {
            echo '<div class="alert alert-danger" role="alert">
                try again.
                </div>';
        }
    }
}
if (isset($_POST['uploadPhoto']) && isset($_FILES['photo'])) {
    $photo = $_FILES['photo'];
    if (empty($photo['name'])) {
        echo '<div class="alert alert-danger" role="alert">
                Please select a photo to Upload.
                </div>';
    } else {
        try {
            if (changeProfileImage($photo)) {
                echo '<div class="alert alert-success" role="alert">
                photo image changed successfully.
                </div>';
            } else {
                echo '<div class="alert alert-danger" role="alert">
                    failed to change profile image.
                    </div>';
            }
        } catch (Exception $e) {
            echo '<div class="alert alert-danger" role="alert">
                    ' . $e->getMessage() . '
                    </div>';
        }
    }
}
if (isset($_POST['deletePhoto'])) {
    deleteProfileImage();
}
?>
<div class="row">
    <div class="col-6">
        <form method="post" action="./?page=profile" enctype="multipart/form-data">
        <form method="post" action="./?page=profile" enctype="multipart/form-data">
            <div class="d-flex justify-content-center">
                <input name="photo" type="file" id="profileUpload" hidden accept=".jpg, .jpeg, .png , .gif">
                <label role="button" for="profileUpload">
                    <img src="<?php echo loggedUserIN()->photo ?? './assets/image/emptyUser.jpg' ?>"
                        class="rounded img-thumbnail" style="max-width: 100px">
                </label>
                <?php
                if (!$response) { ?>
                    <div class="invalid-feedback">Upload Image Unsuccess</div>
                <?php }
                ?>
                <?php
                if ($response) { ?>
                    <div class="invalid-feedback">Upload Image success</div>
                <?php }
                ?>
            </div>
            <div class="d-flex justify-content-center">
                <button type="submit" name="deletePhoto" class="btn btn-outline-danger"
                    onclick="return confirm('Are you sure you want to deleted this image?')">Delete</button>
                <button type="submit" name="uploadPhoto" class="btn btn-outline-success"
                    onclick="return confirm('Do you want to upload this image?')">Upload</button>

            </div>
        </form>
    </div>
    <div class="col-6">
        <form method="post" action="./?page=profile" class="col-md-10 col-lg-6 mx-auto">
            <h3>Change Password</h3>
            <div class="mb-3">
                <label class="form-label">Old Password</label>
                <input value="<?php echo $oldPasswd ?>" name="oldPasswd" type="password" class="form-control 
                <?php echo empty($oldPasswdErr) ? '' : 'is-invalid' ?>">
                <div class="invalid-feedback">
                    <?php echo $oldPasswdErr ?>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">New Password</label>
                <input name="newPasswd" type="password" class="form-control 
                <?php echo empty($newPasswdErr) ? '' : 'is-invalid' ?>">
                <div class="invalid-feedback">
                    <?php echo $newPasswdErr ?>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm New Password</label>
                <input name="confirmNewPasswd" type="password" class="form-control">
            </div>
            <button type="submit" name="changePasswd" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>

<script>
    document.getElementById('profileUpload').addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                document.querySelector('label img').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
</script>