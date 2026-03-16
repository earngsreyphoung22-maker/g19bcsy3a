<?php
$id = $_GET['id'];
$targetUser = readUser($id);
if ($targetUser == null || $targetUser->level === 'admin') {
    header('Location: ./?page=user/list');
}

?>