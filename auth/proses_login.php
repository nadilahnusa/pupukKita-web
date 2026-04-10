<?php
session_start();
include '../config/koneksi.php';

$nik = mysqli_real_escape_string($conn, $_POST['nik']);
$password = $_POST['password'];

$query = "SELECT * FROM users WHERE nik='$nik'";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) > 0){
    $data = mysqli_fetch_assoc($result);

    if($password == $data['password']){
        
        // ✅ SESSION
        $_SESSION['nik'] = $data['nik'];
        $_SESSION['role'] = $data['role'];
        $_SESSION['nama'] = $data['nama']; // ← INI PENTING

        // ✅ REDIRECT
        if($data['role'] == 'super_admin'){
            header("Location: ../su_admin/dashboard_su_admin.php");
        } elseif($data['role'] == 'admin_gudang'){
            header("Location: ../gudang/dashboard_gudang.php");
        } elseif($data['role'] == 'petani'){
            header("Location: ../petani/dashboard_petani.php");
        }
        exit();

    } else {
        header("Location: login.php?error=password");
        exit();
    }

} else {
    header("Location: login.php?error=nik");
    exit();
}
?>