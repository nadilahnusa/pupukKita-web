<?php
session_start();

if($_SESSION['role'] != 'super_admin'){
    header("Location: login.php");
}
?>

<h1>Dashboard Super Admin</h1>
<a href="logout.php">Logout</a>