<?php
session_start();
include '../config/koneksi.php';

// Data Database
$query_pending = mysqli_query($conn, "SELECT COUNT(*) as total FROM distribusi WHERE status='pending'");
$data_pending = mysqli_fetch_assoc($query_pending);

$query_completed = mysqli_query($conn, "SELECT COUNT(*) as total FROM distribusi WHERE status='completed'");
$data_completed = mysqli_fetch_assoc($query_completed);

$query_volume = mysqli_query($conn, "SELECT SUM(jumlah) as total_volume FROM distribusi WHERE DATE(created_at) = CURDATE()");
$data_volume = mysqli_fetch_assoc($query_volume);

$stok_urea = mysqli_query($conn, "SELECT jumlah FROM stok_pupuk WHERE pupuk_id = 1"); 
$data_urea = mysqli_fetch_assoc($stok_urea);

$stok_phonska = mysqli_query($conn, "SELECT jumlah FROM stok_pupuk WHERE pupuk_id = 2"); 
$data_phonska = mysqli_fetch_assoc($stok_phonska);

$stok_organik = mysqli_query($conn, "SELECT jumlah FROM stok_pupuk WHERE pupuk_id = 3"); 
$data_organik = mysqli_fetch_assoc($stok_organik);

$query_aktivitas = mysqli_query($conn, "
    SELECT d.*, p.nama as nama_petani, pk.nama_pupuk 
    FROM distribusi d 
    JOIN petani p ON d.petani_id = p.id 
    JOIN pupuk pk ON d.pupuk_id = pk.id 
    ORDER BY d.created_at DESC LIMIT 4
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>PupuKita - Dashboard Admin Gudang</title>
    <link href="../assets/css/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    
    <style>
        /* CSS UNTUK TAMPILAN PUTIH BERSIH */
        .main-content-putih {
            background-color: #f8fafc !important; /* Abu-abu sangat muda/hampir putih */
            min-height: 100vh;
        }
        .text-hijau-pupuk { color: #86bd05 !important; }
        .bg-hijau-pupuk { background-color: #86bd05 !important; }
        .border-hijau-pupuk { border-color: rgba(134, 189, 5, 0.2) !important; }
        
        .material-symbols-outlined { 
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; 
            display: inline-block;
        }
    </style>
</head>

<body class="font-display">
<div class="relative flex h-screen w-full overflow-hidden">
    
    <?php include '../components/sidebar_gudang.php'; ?>

    <main class="flex-1 overflow-y-auto p-8 main-content-putih">
        
        <?php include '../components/header_admin.php'; ?>

        <header class="mb-8 flex flex-wrap items-center justify-between gap-4 mt-6">
            <div class="flex flex-col gap-1">
                <h2 class="text-3xl font-black tracking-tight text-slate-800">Dashboard Admin Gudang</h2>
                <p class="text-hijau-pupuk font-bold uppercase text-xs tracking-wider">Ringkasan Data Real-time</p>
            </div>
            <div class="flex h-10 items-center gap-2 rounded-lg bg-white px-4 border border-slate-200 text-slate-600 shadow-sm">
                <span class="material-symbols-outlined text-hijau-pupuk">calendar_today</span>
                <span class="text-sm font-bold"><?php echo date('d M Y'); ?></span>
            </div>
        </header>

        <section class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            <div class="rounded-xl bg-white p-6 shadow-sm border border-slate-100">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Pickups Pending</p>
                <p class="text-4xl font-black text-slate-900 mt-1"><?= $data_pending['total'] ?? 0 ?></p>
                <div class="mt-4 h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-orange-500" style="width: 40%"></div>
                </div>
            </div>

            <div class="rounded-xl bg-white p-6 shadow-sm border border-slate-100">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Pickups Completed</p>
                <p class="text-4xl font-black text-slate-900 mt-1"><?= $data_completed['total'] ?? 0 ?></p>
                <div class="mt-4 h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-green-500" style="width: 85%"></div>
                </div>
            </div>

            <div class="rounded-xl bg-hijau-pupuk p-6 shadow-lg shadow-[#86bd05]/20">
                <p class="text-xs font-bold text-white/80 uppercase tracking-widest">Total Daily Volume</p>
                <p class="text-4xl font-black text-white mt-1"><?= number_format($data_volume['total_volume'] ?? 0) ?> <span class="text-lg">Ton</span></p>
            </div>
        </section>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            <section class="lg:col-span-2 flex flex-col gap-6">
                <h3 class="text-xl font-black text-slate-800">Quick Stock Status</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-100">
                        <p class="text-xs font-bold text-slate-400 uppercase">Stok Urea</p>
                        <p class="text-3xl font-black text-slate-800 mt-2"><?= $data_urea['jumlah'] ?? 0 ?> <span class="text-sm font-medium">Ton</span></p>
                    </div>
                    <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-100">
                        <p class="text-xs font-bold text-slate-400 uppercase">Stok Phonska</p>
                        <p class="text-3xl font-black text-slate-800 mt-2"><?= $data_phonska['jumlah'] ?? 0 ?> <span class="text-sm font-medium">Ton</span></p>
                    </div>
                    <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-100">
                        <p class="text-xs font-bold text-slate-400 uppercase">Stok Organik</p>
                        <p class="text-3xl font-black text-slate-800 mt-2"><?= $data_organik['jumlah'] ?? 0 ?> <span class="text-sm font-medium">Ton</span></p>
                    </div>
                </div>
            </section>

            <section class="flex flex-col gap-6">
                <h3 class="text-xl font-black text-slate-800">Recent Activities</h3>
                <div class="flex flex-col gap-4">
                    <?php while($row = mysqli_fetch_assoc($query_aktivitas)): ?>
                    <div class="flex gap-4 rounded-xl bg-white p-4 shadow-sm border border-slate-100 transition-all hover:shadow-md">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full <?= $row['status'] == 'completed' ? 'bg-green-50 text-green-600' : 'bg-orange-50 text-orange-600' ?>">
                            <span class="material-symbols-outlined text-2xl">
                                <?= $row['status'] == 'completed' ? 'check_circle' : 'pending' ?>
                            </span>
                        </div>
                        <div class="flex flex-col justify-center">
                            <p class="text-sm font-black text-slate-800 leading-none mb-1"><?= $row['nama_pupuk'] ?></p>
                            <p class="text-xs text-slate-500 font-medium"><?= $row['nama_petani'] ?> • <?= $row['jumlah'] ?> Ton</p>
                            <span class="text-[10px] text-hijau-pupuk font-bold mt-1 uppercase tracking-wider"><?= date('H:i', strtotime($row['created_at'])) ?></span>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </section>
        </div>
    </main>
</div>
</body>
</html>