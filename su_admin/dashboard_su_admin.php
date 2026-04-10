<?php 
session_start();

// Cek apakah session role sudah ada (sudah login) DAN apakah rolenya super_admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'super_admin') {
    // FIX: Arahkan mundur satu folder, lalu masuk ke folder auth
    header("Location: ../auth/login.php");
    exit(); // WAJIB ADA!
}

include '../config/koneksi.php';


$current_filter = $_GET['filter'] ?? 'all';

// Statistik Dashboard
$query_total_petani = mysqli_query($conn, "SELECT COUNT(*) as total FROM petani");
$total_petani = mysqli_fetch_assoc($query_total_petani)['total'] ?? 0;

$query_total_kuota = mysqli_query($conn, "SELECT SUM(kuota) as total_kuota FROM kuota_pupuk");
$total_kuota = mysqli_fetch_assoc($query_total_kuota)['total_kuota'] ?? 0;

$query_total_users = mysqli_query($conn, "SELECT COUNT(*) as total FROM users");
$total_users = mysqli_fetch_assoc($query_total_users)['total'] ?? 0;

$total_pending = mysqli_fetch_assoc(
  mysqli_query($conn, "SELECT COUNT(*) as total FROM distribusi WHERE status='pending'")
)['total'] ?? 0;



// =====================
// DISTRIBUSI (PENDING + HARI INI)
// =====================
$filter = $_GET['filter'] ?? 'all';

$where = "";

if ($filter == 'pending') {
    $where = "WHERE d.status = 'pending'";
} elseif ($filter == 'hari_ini') {
    $where = "WHERE d.tanggal = CURDATE()";
} else {
    $where = ""; // TANPA FILTER
}

$query_distribusi = "
    SELECT 
        d.id,
        p.nama,
        pu.nama_pupuk,
        d.jumlah,
        d.tanggal,
        d.status
    FROM distribusi d
    JOIN petani p ON d.petani_id = p.id
    JOIN pupuk pu ON d.pupuk_id = pu.id
    $where
    ORDER BY d.tanggal ASC
";

$result_distribusi = mysqli_query($conn, $query_distribusi);

if (!$result_distribusi) {
    die("Query distribusi error: " . mysqli_error($conn));
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
      
      <div class="p-4 md:p-8 space-y-6 md:space-y-8">
        <?php if (isset($_GET['success'])): ?>
          <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg font-semibold">
              ✅ Distribusi berhasil diverifikasi!
          </div>
        <?php endif; ?>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 md:gap-6">

          <div class="bg-white dark:bg-background-dark p-6 rounded-xl border border-primary/10 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
              <div>
                <p class="text-sm font-semibold text-slate-500">Distribusi Pending</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-1"><?php echo $total_pending; ?></h3>
              </div>
              <div class="bg-yellow-100 p-3 rounded-xl text-yellow-700">
                <span class="material-symbols-outlined">schedule</span>
              </div>
            </div>
            <div class="mt-4 flex items-center gap-2 text-sm font-bold text-yellow-600">
              <span class="material-symbols-outlined text-sm">pending_actions</span>
              <span>Menunggu Pengambilan</span>
            </div>
          </div>
          
          <div class="bg-white dark:bg-background-dark p-6 rounded-xl border border-primary/10 shadow-sm hover:shadow-md transition-shadow">
              <div class="flex items-start justify-between">
                <div>
                  <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Petani</p>
                  <h3 class="text-3xl font-bold text-slate-800 mt-1"><?php echo number_format($total_petani, 0, ',', '.'); ?></h3>
                </div>
              <div class="bg-primary-lime/10 p-2 rounded-lg text-primary-forest">
                <span class="material-symbols-outlined">groups</span>
              </div>
            </div>
            <div class="mt-4 flex items-center gap-2 text-sm font-medium text-emerald-600">
              <span class="material-symbols-outlined text-sm">trending_up</span>
              <span>+5.2% dari bulan lalu</span>
            </div>
          </div>

          <div class="bg-white dark:bg-background-dark p-6 rounded-xl border border-primary/10 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
              <div>
                <p class="text-sm font-semibold text-slate-500">Total Kuota Pupuk</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-1"><?php echo number_format($total_kuota, 0, ',', '.'); ?> <span class="text-lg font-medium text-slate-400">kg</span></h3>
              </div>
              <div class="bg-primary-forest/10 p-3 rounded-xl text-primary-forest">
                <span class="material-symbols-outlined">inventory</span>
              </div>
            </div>
            <div class="mt-4 flex items-center gap-2 text-sm font-semibold text-slate-500">
              <span class="material-symbols-outlined text-sm">info</span>
              <span>Total alokasi semua petani</span>
            </div>
          </div>

          <div class="bg-white dark:bg-background-dark p-6 rounded-xl border border-primary/10 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
              <div>
                <p class="text-sm font-semibold text-slate-500">Total Akun Pengguna</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-1"><?php echo number_format($total_users, 0, ',', '.'); ?> <span class="text-lg font-medium text-blue-400">User</span></h3>
              </div>
              <div class="bg-blue-100 p-3 rounded-xl text-blue-600">
                <span class="material-symbols-outlined">manage_accounts</span>
              </div>
            </div>
            <div class="mt-4 flex items-center gap-2 text-sm font-bold text-blue-500">
              <span class="material-symbols-outlined text-sm">verified</span>
              <span>Terdaftar di sistem</span>
            </div>
          </div>

        </div>

        <div class="bg-bg-card rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
          <div class="p-4 md:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
              <h2 class="text-xl font-bold text-slate-800">Pengambilan Pupuk</h2>
              <p class="text-sm text-slate-500 font-medium mt-1">Data pending & hari ini</p>
            </div>
            <div class="flex flex-wrap gap-2">
              <a href="?filter=all"
                class="px-4 py-2 rounded-full text-sm font-semibold 
                <?php echo ($current_filter=='all') ? 'bg-primary-forest text-white' : 'bg-slate-100 text-slate-600'; ?>">
                Semua
              </a>

              <a href="?filter=pending"
                class="px-4 py-2 rounded-full text-sm font-semibold 
                <?php echo ($current_filter=='pending') ? 'bg-primary-forest text-white' : 'bg-slate-100 text-slate-600'; ?>">
                Pending
              </a>

              <a href="?filter=hari_ini"
                class="px-4 py-2 rounded-full text-sm font-semibold 
                <?php echo ($current_filter=='hari_ini') ? 'bg-primary-forest text-white' : 'bg-slate-100 text-slate-600'; ?>">
                Hari Ini
              </a>
            </div>
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
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 text-sm">
                
                <?php if (mysqli_num_rows($result_distribusi) > 0): ?>

                  <?php
                    $status_styles = [
                      'pending' => 'bg-yellow-100 text-yellow-700',
                      'completed' => 'bg-emerald-100 text-emerald-700',
                      'expired' => 'bg-red-100 text-red-700'
                    ];

                    $status_icons = [
                      'pending' => 'schedule',
                      'completed' => 'check_circle',
                      'expired' => 'cancel'
                    ];
                  ?>

                  <?php while($row = mysqli_fetch_assoc($result_distribusi)): ?>
                    <?php
                      $nama = !empty($row['nama']) ? $row['nama'] : 'Tanpa Nama';
                      $inisial = strtoupper(substr($nama, 0, 1));

                      $pupuk = isset($row['nama_pupuk']) ? $row['nama_pupuk'] : '-';
                      $jumlah = isset($row['jumlah']) ? $row['jumlah'] : 0;
                      $tanggal = isset($row['tanggal']) ? $row['tanggal'] : '-';
                      $status = isset($row['status']) ? $row['status'] : 'pending';
                    ?>
                    <tr class="hover:bg-slate-50 transition-colors group">
  
                      <!-- NAMA -->
                      <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                          <div class="size-9 rounded-full bg-primary-lime/20 flex items-center justify-center text-primary-forest font-bold">
                            <?php echo $inisial; ?>
                          </div>
                          <span class="font-bold text-slate-700"><?php echo htmlspecialchars($nama); ?></span>
                        </div>
                      </td>

                      <!-- PUPUK -->
                      <td class="px-4 md:px-6 py-3 md:py-4 font-medium text-slate-600 whitespace-nowrap">
                        <?php echo htmlspecialchars($pupuk); ?>
                      </td>

                      <!-- JUMLAH (GANTI PROGRESS BAR) -->
                      <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
                        <span class="text-sm font-bold text-slate-700">
                          <?php echo $jumlah; ?> kg
                        </span>
                      </td>

                      <!-- TANGGAL -->
                      <td class="px-4 md:px-6 py-3 md:py-4 text-slate-600 text-sm whitespace-nowrap">
                        <?php echo date('d M Y', strtotime($tanggal)); ?>
                      </td>

                      <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
                        <span class="px-3 py-1 rounded-lg text-xs font-bold flex items-center gap-1 w-fit 
                          <?php echo $status_styles[$status]; ?>">
                          
                          <span class="material-symbols-outlined text-[14px]">
                            <?php echo $status_icons[$status]; ?>
                          </span>

                          <?php echo ucfirst($status); ?>
                        </span>
                      </td>

                    </tr>
                  <?php endwhile; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="6" class="px-4 md:px-6 py-3 md:py-4 text-center text-slate-500">Belum ada data distribusi.</td>
                  </tr>
                <?php endif; ?>
                </tbody>
            </table>
          </div>
          
          <div class="px-4 md:px-6 py-4 bg-slate-50 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-xs font-semibold text-slate-500">Menampilkan <?php echo mysqli_num_rows($result_distribusi); ?> data pengambilan</p>
            <div class="flex gap-2 w-full sm:w-auto justify-between sm:justify-end">
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