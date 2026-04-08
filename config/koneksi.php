<?php
// Pengaturan default untuk local server (XAMPP/Laragon)
$host     = "localhost";
$username = "root";      // Default username XAMPP
$password = "";          // Default password XAMPP biasanya kosong
$database = "web_pupukita"; // Nama database yang akan kita gunakan
$port = 3307; // Port default MySQL di XAMPP biasanya 3306, tapi bisa berbeda jika ada konflik. Sesuaikan jika perlu.

// Membuat koneksi ke MySQL
$conn = new mysqli($host, $username, $password, $database, $port);

// Mengecek apakah koneksi berhasil atau error
if ($conn->connect_error) {
    // Jika gagal, hentikan proses dan tampilkan pesan error
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Catatan: Jika koneksi berhasil, biarkan kosong (jangan gunakan echo).
// Teks apapun yang di-echo di sini bisa merusak tampilan desain Tailwind kamu nanti.
?>