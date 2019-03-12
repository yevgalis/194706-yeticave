<?php
    require_once('init.php');

    unset($_SESSION['user_id']);
    unset($user);
    header("Location: /index.php");
?>
