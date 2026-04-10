<?php 
session_start();


// Cek apakah session role sudah ada (sudah login) DAN apakah rolenya super_admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'super_admin') {
    header("Location: ../auth/login.php");
    exit(); 
}

include '../config/koneksi.php';

// Tangkap nilai dari form filter & search
$status_filter = $_GET['status'] ?? 'all';
$search_query = $_GET['search'] ?? '';

// Buat logika penyaringan (WHERE)
$where_clauses = [];
if ($status_filter && $status_filter !== 'all') {
    $where_clauses[] = "petani.status = '" . mysqli_real_escape_string($conn, $status_filter) . "'";
}
if ($search_query) {
    $safe_search = mysqli_real_escape_string($conn, $search_query);
    $where_clauses[] = "(users.nama LIKE '%$safe_search%' OR users.nik LIKE '%$safe_search%' OR petani.id LIKE '%$safe_search%')";
}

$where_sql = count($where_clauses) > 0 ? " WHERE " . implode(" AND ", $where_clauses) : "";

// Terapkan filter ke Query SQL
$sql = "SELECT petani.*, users.nik AS user_nik, users.nama AS user_nama
        FROM petani
        LEFT JOIN users ON petani.user_id = users.id" . $where_sql . " ORDER BY petani.id DESC";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>Manajemen Petani | PupuKita</title>
  
  <link href="../assets/css/style.css" rel="stylesheet" />
  
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700;900&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
</head>

<body class="bg-bg-soft font-display text-slate-800">
  <div class="flex min-h-screen overflow-x-hidden">
    
    <?php include '../components/sidebar_admin.php'; ?>

    <main class="flex-1 flex flex-col">
      
      <?php include '../components/header_admin.php'; ?>
      
      <div class="p-4 md:p-8 space-y-6 md:space-y-8">

        <!-- Notifikasi Pesan Sukses -->
        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'edit_success'): ?>
          <div class="bg-emerald-100 text-emerald-700 px-4 py-3 rounded-xl font-bold flex items-center gap-2 shadow-sm border border-emerald-200">
            <span class="material-symbols-outlined text-[20px]">check_circle</span>
            Data petani berhasil diperbarui!
          </div>
        <?php endif; ?>
        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'add_success'): ?>
          <div class="bg-emerald-100 text-emerald-700 px-4 py-3 rounded-xl font-bold flex items-center gap-2 shadow-sm border border-emerald-200">
            <span class="material-symbols-outlined text-[20px]">check_circle</span>
            Petani baru berhasil didaftarkan!
          </div>
        <?php endif; ?>

        <!-- Top Navigation Area -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
          <div>
            <h2 class="text-2xl md:text-3xl font-bold text-slate-800">Direktori Petani</h2>
            <p class="text-sm text-slate-500 font-medium mt-1">Kelola data 1.284 petani yang terdaftar di ekosistem.</p>
          </div>
          <a href="tambah_petani.php" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-primary-forest text-white font-bold rounded-lg hover:bg-primary-lime transition-all shadow-md">
            <span class="material-symbols-outlined text-[20px]">person_add</span>
            <span class="text-sm">Tambah Petani Baru</span>
          </a>
        </div>
        <!-- Dashboard Stats Cards -->
       <!-- <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 md:gap-6">

        
          <div class="bg-white dark:bg-background-dark p-6 rounded-xl border border-primary/10 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
              <div>
                <p class="text-sm font-semibold text-slate-500">Total Petani</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-1">1.284</h3>
              </div>
              <div class="bg-primary-lime/10 p-3 rounded-xl text-primary-forest">
                <span class="material-symbols-outlined">groups</span>
              </div>
            </div>
            <div class="mt-4 flex items-center gap-2 text-sm font-bold text-emerald-600">
              <span class="material-symbols-outlined text-sm">trending_up</span>
              <span>+12% dari bulan lalu</span>
            </div>
          </div>

          
          <div class="bg-white dark:bg-background-dark p-6 rounded-xl border border-primary/10 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
              <div>
                <p class="text-sm font-semibold text-slate-500">Akun Aktif</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-1">1.102</h3>
              </div>
              <div class="bg-blue-100 p-3 rounded-xl text-blue-600">
                <span class="material-symbols-outlined">verified_user</span>
              </div>
            </div>
            <div class="mt-4 flex items-center gap-2 text-sm font-bold text-blue-500">
              <span class="material-symbols-outlined text-sm">check_circle</span>
              <span>86% dari total</span>
            </div>
          </div>

          
          <div class="bg-white dark:bg-background-dark p-6 rounded-xl border border-primary/10 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
              <div>
                <p class="text-sm font-semibold text-slate-500">Menunggu Persetujuan</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-1">45</h3>
              </div>
              <div class="bg-yellow-100 p-3 rounded-xl text-yellow-700">
                <span class="material-symbols-outlined">pending_actions</span>
              </div>
            </div>
            <div class="mt-4 flex items-center gap-2 text-sm font-bold text-yellow-600">
              <span class="material-symbols-outlined text-sm">warning</span>
              <span>Perlu verifikasi</span>
            </div>
          </div>

          
          <div class="bg-white dark:bg-background-dark p-6 rounded-xl border border-primary/10 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
              <div>
                <p class="text-sm font-semibold text-slate-500">Akun Nonaktif</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-1">137</h3>
              </div>
              <div class="bg-slate-100 p-3 rounded-xl text-slate-600">
                <span class="material-symbols-outlined">person_off</span>
              </div>
            </div>
            <div class="mt-4 flex items-center gap-2 text-sm font-bold text-slate-500">
              <span class="material-symbols-outlined text-sm">trending_down</span>
              <span>-2% dari bulan lalu</span>
            </div>
          </div>

        </div> -->

        <!-- Filters and Table Card -->
        <div class="bg-bg-card rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
          <!-- Filter Bar -->
          <form method="GET" action="" class="p-4 md:p-6 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white">
            <div class="flex flex-wrap gap-3 flex-1">
              <div class="relative min-w-[300px] sm:min-w-[250px]">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg">search</span>
                <input name="search" value="<?php echo htmlspecialchars($search_query); ?>" class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-lime/20 focus:border-primary-lime outline-none transition-all bg-bg-soft" placeholder="Cari nama, NIK, atau ID..." type="text" onblur="this.form.submit()"/>
              </div>
              <select name="status" onchange="this.form.submit()" class="border border-slate-200 rounded-xl text-sm px-4 py-2 focus:ring-2 focus:ring-primary-lime/20 focus:border-primary-lime outline-none bg-bg-soft text-slate-600 font-medium">
                <option value="all">Semua Status</option>
                <option value="Aktif" <?php echo $status_filter == 'Aktif' ? 'selected' : ''; ?>>Aktif</option>
                <option value="Pending" <?php echo $status_filter == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="Nonaktif" <?php echo $status_filter == 'Nonaktif' ? 'selected' : ''; ?>>Nonaktif</option>
              </select>
            </div>
            <div class="flex items-center gap-2">
              <button type="button" class="flex items-center gap-2 px-4 py-2 text-slate-600 hover:bg-slate-50 border border-transparent hover:border-slate-200 rounded-xl transition-all text-sm font-bold shadow-sm">
                <span class="material-symbols-outlined text-[18px]">filter_list</span>
                Filter Lanjut
              </button>
              <button type="button" class="flex items-center gap-2 px-4 py-2 text-primary-forest bg-primary-lime/10 hover:bg-primary-lime/20 rounded-xl transition-colors text-sm font-bold">
                <span class="material-symbols-outlined text-[18px]">download</span>
                Ekspor
              </button>
            </div>
          </form>

          <!-- Table Container -->
          <div class="overflow-x-auto">
            <table class="w-full text-left">
              <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider font-bold">
                <tr>
                  <th class="px-4 md:px-6 py-3 md:py-4 border-b border-slate-100 whitespace-nowrap">Detail Petani</th>
                  <th class="px-4 md:px-6 py-3 md:py-4 border-b border-slate-100 whitespace-nowrap">ID Petani</th>
                  <th class="px-4 md:px-6 py-3 md:py-4 border-b border-slate-100 whitespace-nowrap">Luas Tanah</th>
                  <th class="px-4 md:px-6 py-3 md:py-4 border-b border-slate-100 whitespace-nowrap">Status</th>
                  <th class="px-4 md:px-6 py-3 md:py-4 border-b border-slate-100 whitespace-nowrap text-right">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 text-sm">

<?php while ($row = $result->fetch_assoc()): ?>
<?php
  // Mencegah error jika data di database kosong / belum ada kolomnya
  $nama = $row['user_nama'] ?? 'Tanpa Nama';
  $inisial = strtoupper(substr($nama, 0, 2));
  $nik = $row['user_nik'] ?? '-';
  $luas_tanah = $row['luas_tanah'] ?? '-';
  
  // Membersihkan spasi dan menyeragamkan huruf besar/kecil (contoh: "aktif " -> "Aktif")
  $raw_status = $row['status'] ?? 'Nonaktif';
  $status = ucfirst(strtolower(trim($raw_status)));
?>
<tr class="hover:bg-slate-50 transition-colors group bg-white">
  
  <!-- Detail Petani -->
  <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
    <div class="flex items-center gap-3">

      <!-- Avatar -->
      <div class="size-9 rounded-full bg-primary-lime/20 flex items-center justify-center overflow-hidden font-bold text-primary-forest shrink-0">
        <?php echo $inisial; ?>
      </div>

      <div>
        <div class="font-bold text-slate-700"><?php echo htmlspecialchars($nama); ?></div>
        <div class="text-xs text-slate-500"><?php echo htmlspecialchars($nik); ?></div>
      </div>
    </div>
  </td>

  <!-- ID Petani -->
  <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap font-mono text-primary-forest font-bold">
    FR-<?php echo str_pad($row['id'], 3, '0', STR_PAD_LEFT); ?>
  </td>

  <!-- Luas Tanah -->
  <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap font-medium text-slate-600">
    <?php echo htmlspecialchars($luas_tanah); ?> m&sup2;
  </td>

  <!-- Status -->
  <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
    <?php if ($status == 'Aktif'): ?>
      <span class="px-3 py-1 rounded-lg text-xs font-bold flex items-center gap-1 w-fit bg-emerald-100 text-emerald-700">
        <span class="material-symbols-outlined text-[14px]">check_circle</span>
        Aktif
      </span>
    <?php elseif ($status == 'Pending'): ?>
      <span class="px-3 py-1 rounded-lg text-xs font-bold flex items-center gap-1 w-fit bg-yellow-100 text-yellow-700">
        <span class="material-symbols-outlined text-[14px]">schedule</span>
        Pending
      </span>
    <?php else: ?>
      <span class="px-3 py-1 rounded-lg text-xs font-bold flex items-center gap-1 w-fit bg-red-100 text-red-700">
        <span class="material-symbols-outlined text-[14px]">person_off</span>
        Nonaktif
      </span>
    <?php endif; ?>
  </td>

  <!-- Aksi -->
  <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
    <div class="flex items-center justify-end gap-2">
      
      <!-- Edit -->
      <a href="edit_petani.php?id=<?php echo $row['id']; ?>"
         class="p-2 text-slate-400 hover:text-primary-forest hover:bg-primary-lime/10 rounded-lg transition-colors"
         title="Edit Profil">
        <span class="material-symbols-outlined text-[18px]">edit</span>
      </a>



      <!-- Hapus -->
      <a href="hapus_petani.php?id=<?php echo $row['id']; ?>"
         onclick="return confirm('Yakin ingin menghapus?')"
         class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
         title="Hapus">
        <span class="material-symbols-outlined text-[18px]">delete</span>
      </a>

    </div>
  </td>
</tr>
<?php endwhile; ?>

<?php if ($result->num_rows == 0): ?>
<tr>
  <td colspan="5" class="px-4 py-8 text-center text-slate-500 font-medium">Tidak ada data petani yang ditemukan.</td>
</tr>
<?php endif; ?>

</tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div class="px-4 md:px-6 py-4 bg-slate-50 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-xs font-semibold text-slate-500">Menampilkan 1 hingga 5 dari 1.284 petani</p>
            <div class="flex gap-2 w-full sm:w-auto justify-between sm:justify-end">
              <button class="px-3 py-1.5 border border-slate-200 bg-white rounded-lg text-xs font-bold text-slate-400 disabled:opacity-50 shadow-sm" disabled>Prev</button>
              <button class="px-3 py-1.5 bg-primary-forest text-white rounded-lg text-xs font-bold shadow-md">1</button>
              <button class="px-3 py-1.5 border border-slate-200 bg-white rounded-lg text-xs font-bold text-slate-500 hover:bg-slate-100 transition-colors shadow-sm">2</button>
              <button class="px-3 py-1.5 border border-slate-200 bg-white rounded-lg text-xs font-bold text-slate-500 hover:bg-slate-100 transition-colors shadow-sm">3</button>
              <span class="px-2 py-1.5 text-slate-400 font-bold text-xs">...</span>
              <button class="px-3 py-1.5 border border-slate-200 bg-white rounded-lg text-xs font-bold text-slate-500 hover:bg-slate-100 transition-colors shadow-sm">257</button>
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

    if (toggleBtn && sidebar) {
      // Event listener untuk tombol hamburger
      toggleBtn.addEventListener('click', () => {
        if (window.innerWidth < 768) {
          sidebar.classList.toggle('hidden');
          sidebar.classList.toggle('flex');
          sidebar.classList.remove('-ml-64'); 
        } else {
          sidebar.classList.toggle('-ml-64');
          sidebar.classList.add('hidden'); 
          sidebar.classList.remove('flex');
        }
      });

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