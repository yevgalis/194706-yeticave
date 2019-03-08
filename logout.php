<?php
    require_once('init.php');

    unset($_SESSION['user']);
    unset($user);
    header("Location: /index.php");
?>
