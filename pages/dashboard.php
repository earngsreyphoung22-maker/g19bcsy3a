<?php
    if(isset($_SESSION['user_id'])){
        echo $_SESSION['user_id'];
    }
    echo 'LEVEL:' . (isAdmin()? 'ADMIN':'USER');

    //if(isAdmin()){
      //  echo 'LEVEL'
    //}
?>

<h1>Dashboard</h1>
