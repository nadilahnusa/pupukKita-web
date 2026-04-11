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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<body class="bg-bg-soft font-display text-slate-800">
<div class="relative flex h-screen w-full overflow-hidden">
    
    <?php include '../components/sidebar_gudang.php'; ?>

    <main class="flex-1 overflow-y-auto p-4 md:p-8">
        
        <?php include '../components/header_admin.php'; ?>

        <div class="space-y-6 md:space-y-8 mt-4 md:mt-6">

        <header class="mb-8 flex flex-wrap items-center justify-between gap-4 mt-6">
            <div class="flex flex-col gap-1">
                <h2 class="text-3xl font-black tracking-tight text-emerald-800">Dashboard Admin Gudang</h2>
                <p class="text-primary-lime font-bold uppercase text-xs tracking-wider">Ringkasan Data Real-time</p>
            </div>
            <div class="flex h-10 items-center gap-2 rounded-lg bg-white px-4 border border-slate-200 text-slate-600 shadow-sm">
                <span class="material-symbols-outlined text-primary-lime">calendar_today</span>
                <span class="text-sm font-bold"><?php echo date('d M Y'); ?></span>
            </div>
        </header>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <div class="bg-bg-card p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-primary-lime/30 transition-all">
            <div class="flex items-start justify-between">
              <div>
                <p class="text-sm font-semibold text-slate-500">Distribusi Pending</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-1"><?= $data_pending['total'] ?? 0 ?></h3>
              </div>
              <div class="bg-yellow-100 p-3 rounded-xl text-yellow-700">
                <span class="material-symbols-outlined">schedule</span>
              </div>
            </div>
            <div class="mt-4 flex items-center gap-2 text-sm font-bold text-yellow-600">
              <span class="material-symbols-outlined text-[16px]">pending_actions</span>
              <span>Menunggu Pengambilan</span>
            </div>
          </div>
          
          <div class="bg-bg-card p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-primary-lime/30 transition-all">
            <div class="flex items-start justify-between">
              <div>
                <p class="text-sm font-semibold text-slate-500">Distribusi Selesai</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-1"><?= $data_completed['total'] ?? 0 ?></h3>
              </div>
              <div class="bg-emerald-100 p-3 rounded-xl text-emerald-700">
                <span class="material-symbols-outlined">check_circle</span>
              </div>
            </div>
            <div class="mt-4 flex items-center gap-2 text-sm font-bold text-emerald-600">
              <span class="material-symbols-outlined text-[16px]">verified</span>
              <span>Berhasil didistribusikan</span>
            </div>
          </div>
          
          <div class="bg-bg-card p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-primary-lime/30 transition-all">
            <div class="flex items-start justify-between">
              <div>
                <p class="text-sm font-semibold text-slate-500">Volume Harian</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-1"><?= number_format($data_volume['total_volume'] ?? 0) ?> <span class="text-lg font-medium text-slate-400">Ton</span></h3>
              </div>
              <div class="bg-primary-forest/10 p-3 rounded-xl text-primary-forest">
                <span class="material-symbols-outlined">local_shipping</span>
              </div>
            </div>
            <div class="mt-4 flex items-center gap-2 text-sm font-bold text-primary-forest">
              <span class="material-symbols-outlined text-[16px]">trending_up</span>
              <span>Total pupuk keluar hari ini</span>
            </div>
          </div>
        </div>

        <!-- Bottom Section -->
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            <section class="lg:col-span-2 flex flex-col gap-6">
                <div class="bg-bg-card p-6 rounded-2xl border border-slate-100 shadow-sm">
                    <div class="flex items-center justify-between mb-6 border-b border-slate-100 pb-4">
                        <div class="flex items-center gap-3">
                            <div class="bg-primary-forest/10 p-2 rounded-lg text-primary-forest">
                                <span class="material-symbols-outlined">inventory_2</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800">Status Stok Gudang</h3>
                        </div>
                        <a href="#" class="text-primary-forest text-sm font-bold hover:underline">Kelola Stok</a>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="p-5 rounded-xl bg-slate-50 border border-slate-100 hover:border-primary-lime/30 transition-all">
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Pupuk Urea</p>
                            <p class="text-3xl font-black text-slate-800"><?= $data_urea['jumlah'] ?? 0 ?> <span class="text-sm font-bold text-slate-400">Ton</span></p>
                        </div>
                        <div class="p-5 rounded-xl bg-slate-50 border border-slate-100 hover:border-primary-lime/30 transition-all">
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">NPK Phonska</p>
                            <p class="text-3xl font-black text-slate-800"><?= $data_phonska['jumlah'] ?? 0 ?> <span class="text-sm font-bold text-slate-400">Ton</span></p>
                        </div>
                        <div class="p-5 rounded-xl bg-slate-50 border border-slate-100 hover:border-primary-lime/30 transition-all">
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Pupuk Organik</p>
                            <p class="text-3xl font-black text-slate-800"><?= $data_organik['jumlah'] ?? 0 ?> <span class="text-sm font-bold text-slate-400">Ton</span></p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="flex flex-col gap-6">
                <div class="bg-bg-card rounded-2xl border border-slate-100 shadow-sm h-full flex flex-col overflow-hidden">
                    <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-white">
                        <div class="flex items-center gap-3">
                            <div class="bg-blue-100 p-2 rounded-lg text-blue-600">
                                <span class="material-symbols-outlined">history</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800">Aktivitas Terbaru</h3>
                        </div>
                    </div>
                    <div class="divide-y divide-slate-100 flex-1 bg-white">
                        <?php if (mysqli_num_rows($query_aktivitas) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($query_aktivitas)): ?>
                            <div class="flex gap-4 p-4 hover:bg-slate-50 transition-colors items-center">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full <?= $row['status'] == 'completed' ? 'bg-emerald-100 text-emerald-600' : 'bg-yellow-100 text-yellow-600' ?>">
                                    <span class="material-symbols-outlined text-[20px]">
                                        <?= $row['status'] == 'completed' ? 'check_circle' : 'pending' ?>
                                    </span>
                                </div>
                                <div class="flex flex-col justify-center flex-1">
                                    <p class="text-sm font-bold text-slate-800 leading-tight"><?= $row['nama_pupuk'] ?></p>
                                    <p class="text-xs text-slate-500 font-medium mt-0.5"><?= $row['nama_petani'] ?> • <span class="font-bold text-slate-600"><?= $row['jumlah'] ?> Ton</span></p>
                                </div>
                                <div class="shrink-0">
                                    <span class="text-[10px] bg-slate-100 border border-slate-200 text-slate-500 px-2 py-1 rounded-lg font-bold uppercase tracking-wider"><?= date('H:i', strtotime($row['created_at'])) ?></span>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="p-6 text-center text-slate-500 text-sm font-medium">Belum ada aktivitas terbaru.</div>
                        <?php endif; ?>
                    </div>
                    <div class="p-3 border-t border-slate-100 bg-slate-50 text-center">
                        <a href="#" class="text-sm font-bold text-primary-forest hover:underline">Lihat Semua Log</a>
                    </div>
                </div>
            </section>
        </div>

        </div>
    </main>
</div>

  <script>
    const toggleBtn = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');

    if (toggleBtn && sidebar) {
      // Event listener untuk tombol hamburger
      toggleBtn.addEventListener('click', () => {
        if (window.innerWidth < 768) {
          // Tampilan Mobile: Toggle class hidden dan flex agar melayang
          sidebar.classList.toggle('hidden');
          sidebar.classList.toggle('flex');
          sidebar.classList.remove('-ml-64'); // Mencegah bug margin
        } else {
          // Tampilan Desktop: Toggle margin kiri untuk efek slide
          sidebar.classList.toggle('-ml-64');
          sidebar.classList.add('hidden'); // Reset state mobile
          sidebar.classList.remove('flex');
        }
      });

      // Menutup sidebar jika klik di luar area sidebar (khusus mobile)
      document.addEventListener('click', (e) => {
        if (window.innerWidth < 768 && !sidebar.classList.contains('hidden')) {
          if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
            sidebar.classList.add('hidden');
            sidebar.classList.remove('flex');
          }
        }
      });
    }
  </script>

</body>
</html>