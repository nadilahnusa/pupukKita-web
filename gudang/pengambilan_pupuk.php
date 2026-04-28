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


// =====================
// DISTRIBUSI (PENDING + HARI INI)
// =====================
$current_filter = $_GET['filter'] ?? 'all';
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
    <title>PupuKita - Dashboard Admin Gudang</title>
    <link href="../assets/css/style.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<body class="bg-background-light font-display text-slate-800">
<div class="relative flex h-screen w-full overflow-hidden">
    
    <?php include '../components/sidebar_gudang.php'; ?>

    <main class="flex-1 overflow-y-auto p-4 md:p-8">
        
        <?php include '../components/header_admin.php'; ?>

        <div class="space-y-6 md:space-y-8 mt-4 md:mt-6">

        <?php if (isset($_GET['verifikasi']) && $_GET['verifikasi'] == 'sukses'): ?>
          <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg font-semibold flex items-center gap-2">
              <span class="material-symbols-outlined text-[20px]">check_circle</span>
              Distribusi berhasil diverifikasi dan stok telah diperbarui!
          </div>
        <?php endif; ?>

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
                  <th class="px-4 md:px-6 py-3 md:py-4 border-b border-slate-100 whitespace-nowrap text-right">Aksi</th>
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

                      <!-- AKSI -->
                      <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
                        <div class="flex justify-end">
                          <?php if ($status == 'pending'): ?>
                            <a href="verifikasi_pengambilan.php?id=<?php echo $row['id']; ?>" 
                               class="px-4 py-2 rounded-lg bg-emerald-500 text-white text-xs font-bold hover:bg-emerald-600 transition-colors shadow-sm flex items-center gap-1.5 w-fit"
                               onclick="return confirm('Anda yakin ingin memverifikasi pengambilan ini? Stok akan dikurangi.')">
                              <span class="material-symbols-outlined text-[16px]">verified</span>
                              Verifikasi
                            </a>
                          <?php else: ?>
                            <span class="px-4 py-2 rounded-lg bg-slate-200 text-slate-500 text-xs font-bold flex items-center gap-1.5 w-fit cursor-not-allowed">
                              <span class="material-symbols-outlined text-[16px]">check_circle</span>
                              Selesai
                            </span>
                          <?php endif; ?>
                        </div>
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