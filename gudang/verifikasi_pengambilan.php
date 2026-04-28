<?php
session_start();
include '../config/koneksi.php';

// Proteksi halaman, hanya admin gudang atau super admin yang bisa akses
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin_gudang', 'super_admin'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Pastikan ID distribusi ada di URL
if (!isset($_GET['id'])) {
    header("Location: pengambilan_pupuk.php?verifikasi=gagal_id");
    exit();
}

$distribusi_id = (int)$_GET['id'];

// Ambil detail distribusi untuk mendapatkan jumlah dan pupuk_id
$query_dist = "SELECT pupuk_id, jumlah, status FROM distribusi WHERE id = ?";
$stmt_dist = $conn->prepare($query_dist);
$stmt_dist->bind_param("i", $distribusi_id);
$stmt_dist->execute();
$result_dist = $stmt_dist->get_result();
$distribusi = $result_dist->fetch_assoc();

// Jika data tidak ditemukan atau statusnya bukan 'pending', redirect
if (!$distribusi || $distribusi['status'] !== 'pending') {
    header("Location: pengambilan_pupuk.php?verifikasi=gagal_status");
    exit();
}

$pupuk_id = $distribusi['pupuk_id'];
$jumlah_diambil = $distribusi['jumlah'];

// Mulai transaksi database untuk memastikan integritas data
$conn->begin_transaction();

try {
    // 1. Update status distribusi menjadi 'completed' dan catat waktu pengambilan
    $sql_update_distribusi = "UPDATE distribusi SET status = 'completed', taken_at = NOW() WHERE id = ?";
    $stmt_update_distribusi = $conn->prepare($sql_update_distribusi);
    $stmt_update_distribusi->bind_param("i", $distribusi_id);
    $stmt_update_distribusi->execute();

    // 2. Kurangi stok pupuk di gudang
    $sql_update_stok = "UPDATE stok_pupuk SET jumlah = jumlah - ? WHERE pupuk_id = ?";
    $stmt_update_stok = $conn->prepare($sql_update_stok);
    $stmt_update_stok->bind_param("ii", $jumlah_diambil, $pupuk_id);
    $stmt_update_stok->execute();

    // Jika semua query berhasil, commit transaksi
    $conn->commit();

    // Redirect kembali dengan pesan sukses
    header("Location: pengambilan_pupuk.php?verifikasi=sukses");
    exit();

} catch (mysqli_sql_exception $exception) {
    // Jika terjadi error, rollback semua perubahan
    $conn->rollback();
    header("Location: pengambilan_pupuk.php?verifikasi=gagal_db");
    exit();
}