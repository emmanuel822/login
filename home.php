<?php session_start();
if ($_SESSION['rol'] == 1 ||$_SESSION['rol'] ==2) {
    header('location: login.php');
    exit();
} ?>
<?php include('views/home.view.php');?>