<?php 
session_start();

// Cek apakah session role sudah ada (sudah login) DAN apakah rolenya super_admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'petani') {
    // FIX: Arahkan mundur satu folder, lalu masuk ke folder auth
    header("Location: ../auth/login.php");
    exit(); // WAJIB ADA!
}

include '../config/koneksi.php';

// 1. Ambil Session Login (Menyesuaikan data apapun yang disimpan oleh proses_login.php)
$session_conditions = [];
if (!empty($_SESSION['id'])) $session_conditions[] = "u.id = '" . mysqli_real_escape_string($conn, $_SESSION['id']) . "'";
if (!empty($_SESSION['nik'])) $session_conditions[] = "u.nik = '" . mysqli_real_escape_string($conn, $_SESSION['nik']) . "'";
if (!empty($_SESSION['nama'])) $session_conditions[] = "u.nama = '" . mysqli_real_escape_string($conn, $_SESSION['nama']) . "'";
$where_sql = count($session_conditions) > 0 ? implode(" OR ", $session_conditions) : "u.id = 0";

// 2. Ambil data petani berdasarkan user yang login (Join dengan tabel users)
$query_petani = mysqli_query($conn, "SELECT p.id, p.nama FROM petani p 
                                     JOIN users u ON p.user_id = u.id 
                                     WHERE $where_sql LIMIT 1");
$data_petani = mysqli_fetch_assoc($query_petani);
$petani_id = $data_petani['id'] ?? 0;
$nama_petani = $data_petani['nama'] ?? $_SESSION['nama'] ?? 'Petani';

// 3. Hitung Total Kuota
$query_kuota = mysqli_query($conn, "SELECT SUM(kuota) as total_kuota FROM kuota_pupuk WHERE petani_id = '$petani_id'");
$total_kuota = mysqli_fetch_assoc($query_kuota)['total_kuota'] ?? 0;

// 4. Hitung Sudah Diambil (hanya status completed)
$query_diambil = mysqli_query($conn, "SELECT SUM(jumlah) as total_diambil FROM distribusi WHERE petani_id = '$petani_id' AND status = 'completed'");
$total_diambil = mysqli_fetch_assoc($query_diambil)['total_diambil'] ?? 0;

// 5. Sisa Kuota
$sisa_kuota = max(0, $total_kuota - $total_diambil);

// Hitung persentase pemakaian untuk Progress Bar
$persentase_pakai = 0;
if ($total_kuota > 0) {
    $persentase_pakai = min(100, round(($total_diambil / $total_kuota) * 100));
}

// 6. Ambil 5 Riwayat Pengambilan Terbaru
$query_riwayat = mysqli_query($conn, "SELECT d.jumlah, d.tanggal, d.status, p.nama_pupuk FROM distribusi d JOIN pupuk p ON d.pupuk_id = p.id WHERE d.petani_id = '$petani_id' ORDER BY d.tanggal DESC LIMIT 5");

?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>Dashboard Petani | PupuKita</title>
  
  <link href="../assets/css/style.css" rel="stylesheet" />
  
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
</head>


<body class="bg-bg-soft font-display text-slate-800 min-h-screen">
  <div class="relative flex h-screen w-full overflow-hidden">
    <!-- Sidebar -->
    <?php include '../components/sidebar_petani.php'; ?>
    
    <!-- Main Content Area -->
    <main class="flex-1 overflow-y-auto min-w-0 p-4 md:p-8">
      <!-- Header -->

      <?php include '../components/header_admin.php'; ?>
      
      <!-- Dashboard Content -->
      <div class="space-y-6 md:space-y-8 mt-4 md:mt-6">
        <!-- Welcome Section -->
        <header class="mb-4 flex flex-wrap items-center justify-between gap-4 mt-2 md:mt-4">
            <div class="flex flex-col gap-1">
                <h2 class="text-3xl font-black tracking-tight text-emerald-800">Selamat Datang, <?php echo htmlspecialchars($nama_petani); ?></h2>
                <p class="text-primary-lime font-bold uppercase text-xs tracking-wider">Ringkasan kuota pupuk subsidi Anda hari ini</p>
            </div>
            <div class="flex h-10 items-center gap-2 rounded-lg bg-white px-4 border border-slate-200 text-slate-600 shadow-sm">
                <span class="material-symbols-outlined text-primary-lime">calendar_today</span>
                <span class="text-sm font-bold"><?php echo date('d M Y'); ?></span>
            </div>
        </header>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="bg-bg-card p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-primary-lime/30 transition-all">
            <div class="flex items-start justify-between">
              <div>
                <p class="text-sm font-semibold text-slate-500">Total Kuota</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-1"><?php echo number_format($total_kuota); ?> <span class="text-lg font-medium text-slate-400">kg</span></h3>
              </div>
              <div class="bg-primary-forest/10 p-3 rounded-xl text-primary-forest">
                <span class="material-symbols-outlined">inventory</span>
              </div>
            </div>
            <div class="mt-4 flex items-center gap-2 text-sm font-semibold text-slate-500">
              <span>Alokasi Tahunan</span>
            </div>
          </div>
          
          <div class="bg-bg-card p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-primary-lime/30 transition-all">
            <div class="flex items-start justify-between">
              <div>
                <p class="text-sm font-semibold text-slate-500">Sudah Diambil</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-1"><?php echo number_format($total_diambil); ?> <span class="text-lg font-medium text-slate-400">kg</span></h3>
              </div>
              <div class="bg-primary-lime/20 p-3 rounded-xl text-primary-forest">
                <span class="material-symbols-outlined">local_shipping</span>
              </div>
            </div>
            <div class="mt-4">
              <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden flex">
                <div class="bg-primary-lime h-full rounded-full transition-all duration-1000" style="width: <?php echo $persentase_pakai; ?>%"></div>
              </div>
              <p class="text-xs text-slate-500 font-medium mt-2">Terpakai <span class="font-bold text-slate-700"><?php echo $persentase_pakai; ?>%</span> dari total alokasi</p>
            </div>
          </div>
          
          <div class="bg-bg-card p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-primary-lime/30 transition-all">
            <div class="flex items-start justify-between">
              <div>
                <p class="text-sm font-semibold text-slate-500">Sisa Kuota</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-1"><?php echo number_format($sisa_kuota); ?> <span class="text-lg font-medium text-slate-400">kg</span></h3>
              </div>
              <div class="bg-yellow-100 p-3 rounded-xl text-yellow-700">
                <span class="material-symbols-outlined">hourglass_top</span>
              </div>
            </div>
            <div class="mt-4 flex items-center gap-2 text-sm font-bold text-yellow-600">
              <span>Tersedia untuk diambil</span>
            </div>
          </div>
        </div>
        
        <!-- Table Section -->
        <div class="bg-bg-card rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
          <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h4 class="text-lg font-bold text-slate-800">Riwayat Pengambilan</h4>
            <button class="text-primary-forest text-sm font-bold hover:underline">Lihat Semua</button>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-left">
              <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider font-bold">
                <tr>
                  <th class="px-4 md:px-6 py-3 md:py-4 border-b border-slate-100 whitespace-nowrap">Tanggal</th>
                  <th class="px-4 md:px-6 py-3 md:py-4 border-b border-slate-100 whitespace-nowrap">Pupuk</th>
                  <th class="px-4 md:px-6 py-3 md:py-4 border-b border-slate-100 whitespace-nowrap">Jumlah</th>
                  <th class="px-4 md:px-6 py-3 md:py-4 border-b border-slate-100 whitespace-nowrap">Status</th>
                  <th class="px-4 md:px-6 py-3 md:py-4 border-b border-slate-100 text-right whitespace-nowrap">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <?php if ($query_riwayat && mysqli_num_rows($query_riwayat) > 0): ?>
                  <?php while($row = mysqli_fetch_assoc($query_riwayat)): ?>
                <tr class="hover:bg-slate-50 transition-colors group">
                  <td class="px-4 md:px-6 py-3 md:py-4 text-sm whitespace-nowrap"><?php echo date('d M Y', strtotime($row['tanggal'])); ?></td>
                  <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
                    <div class="flex items-center gap-2">
                      <div class="size-8 rounded-lg bg-primary-lime/20 flex items-center justify-center text-primary-forest">
                        <span class="material-symbols-outlined text-[18px]">eco</span>
                      </div>
                      <span class="font-medium text-slate-700"><?php echo htmlspecialchars($row['nama_pupuk']); ?></span>
                    </div>
                  </td>
                  <td class="px-4 md:px-6 py-3 md:py-4 font-semibold whitespace-nowrap"><?php echo $row['jumlah']; ?> kg</td>
                  <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
                    <?php if ($row['status'] == 'completed'): ?>
                      <span class="px-3 py-1 rounded-lg bg-emerald-100 text-emerald-700 text-xs font-bold flex items-center gap-1 w-fit">
                        <span class="material-symbols-outlined text-[14px]">check_circle</span> Selesai
                      </span>
                    <?php elseif ($row['status'] == 'pending'): ?>
                      <span class="px-3 py-1 rounded-lg bg-yellow-100 text-yellow-700 text-xs font-bold flex items-center gap-1 w-fit">
                        <span class="material-symbols-outlined text-[14px]">schedule</span> Pending
                      </span>
                    <?php else: ?>
                      <span class="px-3 py-1 rounded-lg bg-red-100 text-red-700 text-xs font-bold flex items-center gap-1 w-fit">
                        <span class="material-symbols-outlined text-[14px]">cancel</span> Batal/Expired
                      </span>
                    <?php endif; ?>
                  </td>
                  <td class="px-4 md:px-6 py-3 md:py-4 text-right whitespace-nowrap">
                    <button class="p-2 text-slate-400 hover:text-primary-lime bg-white hover:bg-primary-lime/10 rounded-lg border border-slate-200 hover:border-primary-lime/30 transition-all shadow-sm">
                      <span class="material-symbols-outlined text-[18px]">receipt_long</span>
                    </button>
                  </td>
                </tr>
                  <?php endwhile; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-slate-500 font-medium">Belum ada riwayat pengambilan pupuk.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
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