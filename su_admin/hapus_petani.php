<?php 
session_start();

// Proteksi halaman
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'super_admin') {
    header("Location: ../auth/login.php");
    exit(); 
}

include '../config/koneksi.php';

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Ambil user_id terlebih dahulu untuk menghapus akun login
    $query = mysqli_query($conn, "SELECT user_id FROM petani WHERE id = '$id'");
    
    if ($query && mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);
        $user_id = $data['user_id'];
        
        // Hapus juga relasi data di tabel lain jika database tidak menggunakan ON DELETE CASCADE
        mysqli_query($conn, "DELETE FROM kuota_pupuk WHERE petani_id = '$id'");
        mysqli_query($conn, "DELETE FROM distribusi WHERE petani_id = '$id'");
        
        // Hapus data dari tabel petani, jika sukses hapus juga dari tabel users
        if (mysqli_query($conn, "DELETE FROM petani WHERE id = '$id'")) {
            mysqli_query($conn, "DELETE FROM users WHERE id = '$user_id'");
        }
    }
}

// Redirect kembali ke halaman manajemen dengan pesan sukses
header("Location: manajemen_petani.php?msg=delete_success");
exit();