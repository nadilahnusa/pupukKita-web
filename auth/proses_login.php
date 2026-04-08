<?php
session_start();

// 1. FIX PATH KONEKSI (mundur satu folder dari auth/ ke config/)
include '../config/koneksi.php';

// 2. MENCEGAH SQL INJECTION
$nik = mysqli_real_escape_string($conn, $_POST['nik']);
$password = $_POST['password'];

$query = "SELECT * FROM users WHERE nik='$nik'";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) > 0){
    $data = mysqli_fetch_assoc($result);

    // Catatan: Ini masih membandingkan password teks biasa (plain text).
    // Untuk ke depannya, sangat disarankan menggunakan password_hash() dan password_verify()
    if($password == $data['password']){
        
        $_SESSION['nik'] = $data['nik'];
        $_SESSION['role'] = $data['role'];
        // Opsional: Simpan nama juga untuk ditampilkan di header dashboard nanti
        // $_SESSION['nama'] = $data['nama']; 

        // 3. FIX PATH REDIRECT (Sesuai struktur folder baru PupuKita)
        if($data['role'] == 'super_admin'){
            header("Location: ../su_admin/dashboard_su_admin.php");
            exit();
        } elseif($data['role'] == 'admin_gudang'){
            header("Location: ../gudang/dashboard_gudang.php");
            exit();
        } elseif($data['role'] == 'petani'){
            header("Location: ../petani/dashboard_petani.php");
            exit();
        }

    } else {
        // Redirect kembali ke login.php dengan membawa status error password
        header("Location: login.php?error=password");
        exit();
    }

}    else {
    // Redirect kembali ke login.php dengan membawa status error nik
    header("Location: login.php?error=nik");
    exit();
}
?>