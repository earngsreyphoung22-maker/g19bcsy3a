<?php
function usernameExists($username)
{
    global $db;
    $query = $db->prepare('SELECT * FROM tbl_users WHERE username = ?');
    $query->bind_param('s', $username);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows) {
        return true;

    }
    return false;
}
function registerUser($name, $username, $passwd)
{
    global $db;
    $query = $db->prepare('INSERT INTO tbl_users (name,username,passwd)VALUE(?,?,?)');
    $query->bind_param('sss', $name, $username, $passwd);
    $query->execute();
    if ($db->affected_rows) {
        return true;
    }
    return false;
}

function logUserIN($username, $passwd)
{
    global $db;
    $query = $db->prepare('SELECT * FROM tbl_users WHERE username = ? AND passwd = ?');
    $query->bind_param('ss', $username, $passwd);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows) {
        return $result->fetch_object();
    }
    return false;
}
function loggedUserIN()
{
    global $db;
    if (!isset($_SESSION['user_id'])) {
        return null;
    }
    $user_id = $_SESSION['user_id'];
    $query = $db->prepare('SELECT * FROM tbl_users WHERE id = ?');
    $query->bind_param('d', $user_id);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows) {
        return $result->fetch_object();
    }
    return null;
}

function isAdmin()
{
    $user = loggedUserIN();
    if ($user && $user->level === 'admin') {
        return true;
    }
    return false;
}
function isUserHasPassword($passwd)
{
    global $db;
    $user = loggedUserIN();
    $query = $db->prepare(
        "SELECT * FROM tbl_users where id = ?  AND passwd = ?"

    );
    $query->bind_param(
        'ss',
        $user->id,
        $passwd
    );
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows) {
        return true;
    }
    return false;
}
function setUserNewPassword($passwd)
{
    global $db;
    $user = loggedUserIN();
    $query = $db->prepare(
        "UPDATE tbl_users SET passwd = ? WHERE id = ?"

    );
    $query->bind_param('ss', $passwd, $user->id);
    $query->execute();
    $result = $query->get_result();
    if ($db->affected_rows) {
        return true;
    }
    return false;
}
function changeProfileImage($image)
{
    global $db;
    $user = loggedUserIN();
    $image_path = uploadImage($image);
    if ($image_path && $user->photo) {
        unlink($user->photo);
    }
    $query = $db->prepare('UPDATE tbl_users SET photo = ? WHERE id = ?');
    $query->bind_param('sd', $image_path, $user->id);
    $query->execute();
    if ($db->affected_rows) {
        return true;
    }
    return false;
}
function deleteProfileImage()
{
    global $db;
    $user = loggedUserIN();
    if ($user->photo) {
        unlink($user->photo);
    }
    $query = $db->prepare('UPDATE tbl_users SET photo = null WHERE id = ?');

    $query->bind_param('d', $user->id);
    $query->execute();
    if ($db->affected_rows) {
        return true;
    }
    return false;
}
function uploadImage($image)
{
    $image_name = $image['name'];
    $image_size = $image['size'];
    $tmp_name = $image['tmp_name'];
    $error = $image['error'];

    $dir = 'assets/image/';

    $allow_exs = ['jpg', 'jpeg', 'png'];
    $image_ex = pathinfo($image_name, PATHINFO_EXTENSION);
    $image_lowercase_ex = strtolower($image_ex);

    if (!in_array($image_lowercase_ex, $allow_exs)) {
        throw new Exception('file extension is not allowed!');
    }

    if ($error !== 0) {
        throw new Exception('Unknown error occurred!');
    }
    if ($image_size > 500000) {
        throw new Exception('file size is too large!');
    }

    $new_image_name = uniqid("PI-") . '.' . $image_lowercase_ex;
    $image_path = $dir . $new_image_name;
    move_uploaded_file($tmp_name, $image_path);
    return $image_path;
}
