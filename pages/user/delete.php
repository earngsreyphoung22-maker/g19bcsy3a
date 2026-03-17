<?php
$id = $_GET['id'];
$targetUser = readUser($id);
if ($targetUser == null || $targetUser->level === 'admin') {
    header('Location: ./?page=user/list');
} else {
    if (deleteUser($id)) {
        echo '<div class="alert alert-success" role="alert">
        Delete successful! <a href="./?page=user/list" class="alert-link">go to List</a>.
        </div>';
    } else {
        echo '<div class="alert alert-danger" role="alert">
            Delete failed! Please try again.
            <a> href="./?page/user/list" class="alert-link">go to List</a>.
            </div>';
    }
}

?>