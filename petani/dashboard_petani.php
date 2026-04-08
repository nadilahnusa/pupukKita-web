<?php
session_start();

if($_SESSION['role'] != 'admin_gudang'){
    header("Location: login.php");
}
?>

<h1>Dashboard Admin Gudang</h1>
<a href="logout.php">Logout</a>