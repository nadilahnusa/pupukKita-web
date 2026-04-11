<?php
session_start();
// Menghapus semua memori session yang tersimpan
session_unset();
session_destroy();

// Arahkan pengguna kembali ke halaman login
header("Location: login.php");
exit();
?>