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
function insertImage($file)
{
    global $db;
    $image_name = $file["photo"]["name"];
    $image_temp = $file["photo"]["tmp_name"];

    $db->begin_transaction();

    $query = $db->prepare("UPDATE tbl_users SET photo = ? WHERE id = ?");
    $query->bind_param('sd', $image_name, $_SESSION['user_id']);
    $query->execute();
    if (!$query->affected_rows) {
        $db->rollback();
        return false;
    }
    if (!move_uploaded_file($image_temp, "./assets/image/" . $image_name)) {
        $db->rollback();
        return false;
    }
    $db->commit();

    return true;
}
function getUserImage($user_id)
{
    global $db;
    $query = $db->prepare("SELECT photo FROM tbl_users WHERE id = ?");
    $query->bind_param('d', $user_id);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows) {
        return $result->fetch_object()->photo;
    }
    return null;
}

function deleteUserImage()
{
    global $db;
    $user = loggedUserIN();
    $query = $db->prepare("UPDATE tbl_users SET photo = NULL WHERE id = ?");
    $query->bind_param('d', $user->id);
    $query->execute();
    if ($db->affected_rows) {
        return true;
    }
    return false;
}
