<?php 
session_start();

// Cek apakah session role sudah ada (sudah login) DAN apakah rolenya super_admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'super_admin') {
    // FIX: Arahkan mundur satu folder, lalu masuk ke folder auth
    header("Location: ../auth/login.php");
    exit(); // WAJIB ADA!
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>Dashboard Super Admin | PupuKita</title>
  
  <link href="../assets/css/style.css" rel="stylesheet" />
  
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700;900&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
</head>

<body class="bg-bg-soft font-display text-slate-800">
  <div class="flex min-h-screen overflow-x-hidden">
    
    <?php include '../components/sidebar_admin.php'; ?>

    <main class="flex-1 flex flex-col">
      
      <?php include '../components/header_admin.php'; ?>
      
      <div class="p-8 space-y-8">
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          
          <div class="bg-bg-card p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
              <div>
                <p class="text-sm font-semibold text-slate-500">Total Petani</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-1">1,250</h3>
              </div>
              <div class="bg-primary-lime/20 p-3 rounded-xl text-primary-forest">
                <span class="material-symbols-outlined">groups</span>
              </div>
            </div>
            <div class="mt-4 flex items-center gap-2 text-sm font-bold text-primary-lime">
              <span class="material-symbols-outlined text-sm">trending_up</span>
              <span>+5.2% dari bulan lalu</span>
            </div>
          </div>

          <div class="bg-bg-card p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
              <div>
                <p class="text-sm font-semibold text-slate-500">Total Stok Pupuk</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-1">45,000 <span class="text-lg font-medium text-slate-400">kg</span></h3>
              </div>
              <div class="bg-primary-forest/10 p-3 rounded-xl text-primary-forest">
                <span class="material-symbols-outlined">inventory</span>
              </div>
            </div>
            <div class="mt-4 flex items-center gap-2 text-sm font-semibold text-slate-500">
              <span>Stabil di 12 gudang utama</span>
            </div>
          </div>

          <div class="bg-bg-card p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
              <div>
                <p class="text-sm font-semibold text-slate-500">Pengajuan Perubahan</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-1">12 <span class="text-lg font-medium text-orange-400">Pending</span></h3>
              </div>
              <div class="bg-orange-100 p-3 rounded-xl text-orange-600">
                <span class="material-symbols-outlined">pending_actions</span>
              </div>
            </div>
            <div class="mt-4 flex items-center gap-2 text-sm font-bold text-orange-500">
              <span class="material-symbols-outlined text-sm">priority_high</span>
              <span>3 butuh validasi segera</span>
            </div>
          </div>

        </div>

        <div class="bg-bg-card rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
          <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
              <h2 class="text-xl font-bold text-slate-800">Manajemen Data Petani</h2>
              <p class="text-sm text-slate-500 font-medium mt-1">Daftar lengkap petani terdaftar di wilayah kerja</p>
            </div>
            <button class="bg-primary-forest hover:bg-primary-forest/90 text-white px-5 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 transition-all shadow-lg shadow-primary-forest/20">
              <span class="material-symbols-outlined text-[18px]">person_add</span>
              Tambah Petani
            </button>
          </div>
          
          <div class="overflow-x-auto">
            <table class="w-full text-left">
              <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider font-bold">
                <tr>
                  <th class="px-6 py-4 border-b border-slate-100">Nama Petani</th>
                  <th class="px-6 py-4 border-b border-slate-100">Luas Lahan (Ha)</th>
                  <th class="px-6 py-4 border-b border-slate-100">Kuota Tersisa (kg)</th>
                  <th class="px-6 py-4 border-b border-slate-100">Status Akun</th>
                  <th class="px-6 py-4 border-b border-slate-100 text-right">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 text-sm">
                
                <tr class="hover:bg-slate-50 transition-colors group">
                  <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                      <div class="size-9 rounded-full bg-primary-lime/20 flex items-center justify-center text-primary-forest font-bold">
                        B
                      </div>
                      <span class="font-bold text-slate-700">Budi Cahyono</span>
                    </div>
                  </td>
                  <td class="px-6 py-4 font-medium text-slate-600">2.5</td>
                  <td class="px-6 py-4">
                    <div class="w-full bg-slate-100 h-2 rounded-full max-w-[120px] mb-1.5 overflow-hidden">
                      <div class="bg-primary-lime h-full rounded-full" style="width: 75%;"></div>
                    </div>
                    <span class="text-xs font-bold text-slate-500">1,250 kg</span>
                  </td>
                  <td class="px-6 py-4">
                    <span class="px-3 py-1 rounded-lg bg-emerald-100 text-emerald-700 text-xs font-bold uppercase tracking-wide">Aktif</span>
                  </td>
                  <td class="px-6 py-4 text-right">
                    <div class="flex justify-end gap-2">
                      <button class="p-2 text-slate-400 hover:text-primary-lime bg-white hover:bg-primary-lime/10 rounded-lg border border-slate-200 hover:border-primary-lime/30 transition-all shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">edit</span>
                      </button>
                      <button class="p-2 text-slate-400 hover:text-red-500 bg-white hover:bg-red-50 rounded-lg border border-slate-200 hover:border-red-200 transition-all shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">delete</span>
                      </button>
                    </div>
                  </td>
                </tr>

                <tr class="hover:bg-slate-50 transition-colors group">
                  <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                      <div class="size-9 rounded-full bg-primary-lime/20 flex items-center justify-center text-primary-forest font-bold">
                        B
                      </div>
                      <span class="font-bold text-slate-700">Budi Cahyono</span>
                    </div>
                  </td>
                  <td class="px-6 py-4 font-medium text-slate-600">2.5</td>
                  <td class="px-6 py-4">
                    <div class="w-full bg-slate-100 h-2 rounded-full max-w-[120px] mb-1.5 overflow-hidden">
                      <div class="bg-primary-lime h-full rounded-full" style="width: 75%;"></div>
                    </div>
                    <span class="text-xs font-bold text-slate-500">1,250 kg</span>
                  </td>
                  <td class="px-6 py-4">
                    <span class="px-3 py-1 rounded-lg bg-emerald-100 text-emerald-700 text-xs font-bold uppercase tracking-wide">Aktif</span>
                  </td>
                  <td class="px-6 py-4 text-right">
                    <div class="flex justify-end gap-2">
                      <button class="p-2 text-slate-400 hover:text-primary-lime bg-white hover:bg-primary-lime/10 rounded-lg border border-slate-200 hover:border-primary-lime/30 transition-all shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">edit</span>
                      </button>
                      <button class="p-2 text-slate-400 hover:text-red-500 bg-white hover:bg-red-50 rounded-lg border border-slate-200 hover:border-red-200 transition-all shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">delete</span>
                      </button>
                    </div>
                  </td>
                </tr>

                <tr class="hover:bg-slate-50 transition-colors group">
                  <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                      <div class="size-9 rounded-full bg-primary-lime/20 flex items-center justify-center text-primary-forest font-bold">
                        B
                      </div>
                      <span class="font-bold text-slate-700">Budi Cahyono</span>
                    </div>
                  </td>
                  <td class="px-6 py-4 font-medium text-slate-600">2.5</td>
                  <td class="px-6 py-4">
                    <div class="w-full bg-slate-100 h-2 rounded-full max-w-[120px] mb-1.5 overflow-hidden">
                      <div class="bg-primary-lime h-full rounded-full" style="width: 75%;"></div>
                    </div>
                    <span class="text-xs font-bold text-slate-500">1,250 kg</span>
                  </td>
                  <td class="px-6 py-4">
                    <span class="px-3 py-1 rounded-lg bg-emerald-100 text-emerald-700 text-xs font-bold uppercase tracking-wide">Aktif</span>
                  </td>
                  <td class="px-6 py-4 text-right">
                    <div class="flex justify-end gap-2">
                      <button class="p-2 text-slate-400 hover:text-primary-lime bg-white hover:bg-primary-lime/10 rounded-lg border border-slate-200 hover:border-primary-lime/30 transition-all shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">edit</span>
                      </button>
                      <button class="p-2 text-slate-400 hover:text-red-500 bg-white hover:bg-red-50 rounded-lg border border-slate-200 hover:border-red-200 transition-all shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">delete</span>
                      </button>
                    </div>
                  </td>
                </tr>

                </tbody>
            </table>
          </div>
          
          <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
            <p class="text-xs font-semibold text-slate-500">Menampilkan 1 dari 1,250 petani</p>
            <div class="flex gap-2">
              <button class="px-3 py-1.5 border border-slate-200 bg-white rounded-lg text-xs font-bold text-slate-500 hover:bg-slate-100 transition-colors shadow-sm">Prev</button>
              <button class="px-3 py-1.5 bg-primary-forest text-white rounded-lg text-xs font-bold shadow-md">1</button>
              <button class="px-3 py-1.5 border border-slate-200 bg-white rounded-lg text-xs font-bold text-slate-500 hover:bg-slate-100 transition-colors shadow-sm">Next</button>
            </div>
          </div>

        </div>

      </div>
    </main>
  </div>

  <script>
    const toggleBtn = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');

    toggleBtn.addEventListener('click', () => {
      // Menambahkan/menghapus margin negatif untuk menggeser sidebar
      sidebar.classList.toggle('-ml-64');
    });
  </script>
  
</body>
</html>