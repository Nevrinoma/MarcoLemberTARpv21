<?php
session_start();
if (!isset($_SESSION['tuvastamine'])) {
    header('Location: loginAB.php');
    exit();
}
if(isset($_POST['logout'])){
    session_destroy();
    header('Location: admin.php');
    exit();
}
?>