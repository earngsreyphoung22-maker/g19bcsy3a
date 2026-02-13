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

if (isset($_POST['deletePhoto'])) {
    $photoPath = './assets/image/' . $photo;
    if (file_exists($photoPath) && $photo !== 'image.png') {
        unlink($photoPath);
        $response = deleteUserImage();
        if ($response === true) {
            $photo = 'image.png';
            echo '<div class="alert alert-danger" role="alert">
                Delete Image Success.
           </div>';
        }
    }
}


if (isset($_POST['uploadPhoto']) && isset($_FILES['photo'])) {
    $photo = $_FILES['photo']['name'];
    $photoTmp = $_FILES['photo']['tmp_name'];
    $photoSize = $_FILES['photo']['size'];
    $photoError = $_FILES['photo']['error'];
    $photoType = $_FILES['photo']['type'];
    $fileExt = explode('.', $photo);
    $fileActualExt = strtolower(end($fileExt));
    $allowed = array('jpg', 'jpeg', 'png');
    if (in_array($fileActualExt, $allowed)) {
        if ($photoError === 0) {
            if ($photoSize < 1000000) {
                $response = insertImage($_FILES);
                if ($response === true) {
                    echo '<div class="alert alert-success" role="alert">
                             Upload Image Success.
                             </div>';
                } else {
                    echo '<div class="alert alert-danger" role="alert">
                             Upload Image Unsuccess.
                             </div>';
                }
            } else {
                echo '<div class="alert alert-danger" role="alert">
                         Your file is too big!
                         </div>';
            }
        } else {
            echo '<div class="alert alert-danger" role="alert">
                     There was an error uploading your file!
                     </div>';
        }
    } else {
        echo '<div class="alert alert-danger" role="alert">
                 You cannot upload files of this type!
                 </div>';
    }

}


?>
<div class="row">
    <div class="col-6">
        <form method="post" action="./?page=profile" enctype="multipart/form-data">
            <div class="d-flex justify-content-center">
                <input name="photo" type="file" id="profileUpload" hidden accept=".jpg, .jpeg, .png">
                <label role="button" for="profileUpload">
                    <img src="./assets/image/<?php echo $photo ?>" class="rounded"
                        style="width: 200px; height: 200px; object-fit: cover;" alt="Profile Picture">

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