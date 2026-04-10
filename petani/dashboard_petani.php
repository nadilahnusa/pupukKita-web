<?php 
session_start();

// Cek apakah session role sudah ada (sudah login) DAN apakah rolenya super_admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'petani') {
    // FIX: Arahkan mundur satu folder, lalu masuk ke folder auth
    header("Location: ../auth/login.php");
    exit(); // WAJIB ADA!
}

include '../config/koneksi.php';
?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>Dashboard Petani | PupuKita</title>
  
  <link href="../assets/css/style.css" rel="stylesheet" />
  
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700;900&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
</head>


<body class="bg-bg-soft font-display text-slate-800 min-h-screen">
  <div class="flex min-h-screen overflow-x-hidden">
    <!-- Sidebar -->
    <?php include '../components/sidebar_petani.php'; ?>
    
    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col min-w-0">
      <!-- Header -->

      <?php include '../components/header_admin.php'; ?>
      
      <!-- Dashboard Content -->
      <div class="p-4 md:p-8 space-y-6 md:space-y-8">
        <!-- Welcome Section -->
        <div>
          <h3 class="text-3xl font-bold text-slate-800">Selamat Datang, Bapak Subur</h3>
          <p class="text-slate-500 mt-1">Berikut adalah ringkasan kuota pupuk subsidi Anda hari ini.</p>
        </div>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="bg-bg-card p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-primary-lime/30 transition-all">
            <div class="flex items-start justify-between">
              <div>
                <p class="text-sm font-semibold text-slate-500">Total Kuota</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-1">500 <span class="text-lg font-medium text-slate-400">kg</span></h3>
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
                <h3 class="text-3xl font-bold text-slate-800 mt-1">200 <span class="text-lg font-medium text-slate-400">kg</span></h3>
              </div>
              <div class="bg-primary-lime/20 p-3 rounded-xl text-primary-forest">
                <span class="material-symbols-outlined">local_shipping</span>
              </div>
            </div>
            <div class="mt-4 flex items-center gap-2 text-sm font-semibold text-slate-500">
              <span>Update: 12 Okt 2023</span>
            </div>
          </div>
          
          <div class="bg-bg-card p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-primary-lime/30 transition-all">
            <div class="flex items-start justify-between">
              <div>
                <p class="text-sm font-semibold text-slate-500">Sisa Kuota</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-1">300 <span class="text-lg font-medium text-slate-400">kg</span></h3>
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
                  <th class="px-4 md:px-6 py-3 md:py-4 border-b border-slate-100 whitespace-nowrap">Nama Petani</th>
                  <th class="px-4 md:px-6 py-3 md:py-4 border-b border-slate-100 whitespace-nowrap">Pupuk</th>
                  <th class="px-4 md:px-6 py-3 md:py-4 border-b border-slate-100 whitespace-nowrap">Jumlah</th>
                  <th class="px-4 md:px-6 py-3 md:py-4 border-b border-slate-100 whitespace-nowrap">Tanggal</th>
                  <th class="px-4 md:px-6 py-3 md:py-4 border-b border-slate-100 whitespace-nowrap">Status</th>
                  <th class="px-4 md:px-6 py-3 md:py-4 border-b border-slate-100 text-right whitespace-nowrap">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <tr class="hover:bg-slate-50 transition-colors group">
                  <td class="px-4 md:px-6 py-3 md:py-4 text-sm whitespace-nowrap">12 Okt 2023</td>
                  <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
                    <div class="flex items-center gap-2">
                      <div class="size-8 rounded-lg bg-primary-lime/20 flex items-center justify-center text-primary-forest">
                        <span class="material-symbols-outlined text-[18px]">eco</span>
                      </div>
                      <span class="font-medium text-slate-700">Urea Subsidi</span>
                    </div>
                  </td>
                  <td class="px-4 md:px-6 py-3 md:py-4 font-semibold whitespace-nowrap">100 kg</td>
                  <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
                    <span class="px-3 py-1 rounded-lg bg-emerald-100 text-emerald-700 text-xs font-bold flex items-center gap-1 w-fit">
                      <span class="material-symbols-outlined text-[14px]">check_circle</span>
                      Selesai
                    </span>
                  </td>
                  <td class="px-4 md:px-6 py-3 md:py-4 text-right whitespace-nowrap">
                    <button class="p-2 text-slate-400 hover:text-primary-lime bg-white hover:bg-primary-lime/10 rounded-lg border border-slate-200 hover:border-primary-lime/30 transition-all shadow-sm">
                      <span class="material-symbols-outlined text-[18px]">receipt_long</span>
                    </button>
                  </td>
                </tr>
                <tr class="hover:bg-slate-50 transition-colors group">
                  <td class="px-4 md:px-6 py-3 md:py-4 text-sm whitespace-nowrap">05 Okt 2023</td>
                  <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
                    <div class="flex items-center gap-2">
                      <div class="size-8 rounded-lg bg-primary-lime/20 flex items-center justify-center text-primary-forest">
                        <span class="material-symbols-outlined text-[18px]">water_drop</span>
                      </div>
                      <span class="font-medium text-slate-700">NPK Phonska</span>
                    </div>
                  </td>
                  <td class="px-4 md:px-6 py-3 md:py-4 font-semibold whitespace-nowrap">100 kg</td>
                  <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
                    <span class="px-3 py-1 rounded-lg bg-yellow-100 text-yellow-700 text-xs font-bold flex items-center gap-1 w-fit">
                      <span class="material-symbols-outlined text-[14px]">schedule</span>
                      Pending
                    </span>
                  </td>
                  <td class="px-4 md:px-6 py-3 md:py-4 text-right whitespace-nowrap">
                    <button class="p-2 text-slate-400 hover:text-primary-lime bg-white hover:bg-primary-lime/10 rounded-lg border border-slate-200 hover:border-primary-lime/30 transition-all shadow-sm">
                      <span class="material-symbols-outlined text-[18px]">receipt_long</span>
                    </button>
                  </td>
                </tr>
                <tr class="hover:bg-slate-50 transition-colors group">
                  <td class="px-4 md:px-6 py-3 md:py-4 text-sm whitespace-nowrap">20 Sep 2023</td>
                  <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
                    <div class="flex items-center gap-2">
                      <div class="size-8 rounded-lg bg-primary-lime/20 flex items-center justify-center text-primary-forest">
                        <span class="material-symbols-outlined text-[18px]">eco</span>
                      </div>
                      <span class="font-medium text-slate-700">Urea Subsidi</span>
                    </div>
                  </td>
                  <td class="px-4 md:px-6 py-3 md:py-4 font-semibold whitespace-nowrap">50 kg</td>
                  <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
                    <span class="px-3 py-1 rounded-lg bg-slate-100 text-slate-600 text-xs font-bold flex items-center gap-1 w-fit">
                      <span class="material-symbols-outlined text-[14px]">cancel</span>
                      Expired
                    </span>
                  </td>
                  <td class="px-4 md:px-6 py-3 md:py-4 text-right whitespace-nowrap">
                    <button class="p-2 text-slate-400 hover:text-primary-lime bg-white hover:bg-primary-lime/10 rounded-lg border border-slate-200 hover:border-primary-lime/30 transition-all shadow-sm">
                      <span class="material-symbols-outlined text-[18px]">receipt_long</span>
                    </button>
                  </td>
                </tr>
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