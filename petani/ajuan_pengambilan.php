<?php 
session_start();

// Cek apakah session role sudah ada (sudah login) DAN apakah rolenya petani
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'petani') {
    header("Location: ../auth/login.php");
    exit();
}

include '../config/koneksi.php';

// 1. Ambil ID Petani berdasarkan session
$user_nik = $_SESSION['nik'] ?? '';
$query_petani = mysqli_query($conn, "SELECT p.id FROM petani p JOIN users u ON p.user_id = u.id WHERE u.nik = '$user_nik' LIMIT 1");
$data_petani = mysqli_fetch_assoc($query_petani);
$petani_id = $data_petani['id'] ?? 0;

// 2. Setup Data Kuota
$tahun_ini = date('Y');
$kuota_data = [
    1 => ['total' => 0, 'sisa' => 0, 'persen' => 0, 'harga' => 0], // 1: Urea
    2 => ['total' => 0, 'sisa' => 0, 'persen' => 0, 'harga' => 0], // 2: Phonska
    3 => ['total' => 0, 'sisa' => 0, 'persen' => 0, 'harga' => 0], // 3: Organik
];

// Ambil Harga per Kg dari tabel pupuk
$q_harga = mysqli_query($conn, "SELECT id, harga_kg FROM pupuk");
if ($q_harga) {
    while ($row = mysqli_fetch_assoc($q_harga)) {
        if (isset($kuota_data[$row['id']])) {
            $kuota_data[$row['id']]['harga'] = $row['harga_kg'];
        }
    }
}

// Ambil Total Kuota per Pupuk
$q_kuota = mysqli_query($conn, "SELECT pupuk_id, kuota FROM kuota_pupuk WHERE petani_id = '$petani_id' AND tahun = '$tahun_ini'");
if ($q_kuota) {
    while ($row = mysqli_fetch_assoc($q_kuota)) {
        $pid = $row['pupuk_id'];
        if (isset($kuota_data[$pid])) {
            $kuota_data[$pid]['total'] = $row['kuota'];
            $kuota_data[$pid]['sisa'] = $row['kuota']; // Set awal sisa = total
        }
    }
}

// Kurangi dengan yang sudah Terpakai (status completed/pending, abaikan yang expired/dibatalkan)
$q_terpakai = mysqli_query($conn, "SELECT pupuk_id, SUM(jumlah) as terpakai FROM distribusi WHERE petani_id = '$petani_id' AND status != 'expired' GROUP BY pupuk_id");
if ($q_terpakai) {
    while ($row = mysqli_fetch_assoc($q_terpakai)) {
        $pid = $row['pupuk_id'];
        if (isset($kuota_data[$pid])) {
            $kuota_data[$pid]['sisa'] = max(0, $kuota_data[$pid]['total'] - $row['terpakai']);
        }
    }
}

// Hitung persentase untuk Progress Bar
foreach ($kuota_data as $pid => $data) {
    if ($data['total'] > 0) {
        $kuota_data[$pid]['persen'] = min(100, round(($data['sisa'] / $data['total']) * 100));
    }
}

// 3. Proses Pengajuan Form
$pesan = '';
$tipe_pesan = '';
$token_display = '';
$harga_display = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_ajuan'])) {
    $pupuk_id = (int)$_POST['jenis_pupuk'];
    $jumlah = (int)$_POST['jumlah'];
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $token = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6)); // Generate 6 string unik
    $sisa_kuota = $kuota_data[$pupuk_id]['sisa'] ?? 0;
    $harga_per_kg = $kuota_data[$pupuk_id]['harga'] ?? 0;
    $total_harga = $jumlah * $harga_per_kg;

    if ($jumlah > $sisa_kuota) {
        $pesan = "Pengajuan gagal! Jumlah yang diajukan melebihi sisa kuota Anda.";
        $tipe_pesan = "error";
    } elseif ($jumlah <= 0) {
        $pesan = "Jumlah pengajuan tidak valid.";
        $tipe_pesan = "error";
    } else {
        $query_insert = "INSERT INTO distribusi (petani_id, pupuk_id, jumlah, tanggal, token, status, total_harga) 
                         VALUES ('$petani_id', '$pupuk_id', '$jumlah', '$tanggal', '$token', 'pending', '$total_harga')";
        if (mysqli_query($conn, $query_insert)) {
            $pesan = "Pengajuan berhasil dikirim!";
            $tipe_pesan = "success";
            $token_display = $token;
            $harga_display = $total_harga;
            
            // Update visual sisa kuota ke layar setelah insert sukses
            $kuota_data[$pupuk_id]['sisa'] -= $jumlah;
            if ($kuota_data[$pupuk_id]['total'] > 0) {
                $kuota_data[$pupuk_id]['persen'] = min(100, round(($kuota_data[$pupuk_id]['sisa'] / $kuota_data[$pupuk_id]['total']) * 100));
            }
        } else {
            $pesan = "Terjadi kesalahan sistem saat menyimpan data.";
            $tipe_pesan = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>Pengajuan Pengambilan | PupuKita</title>
  
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
      
      <!-- Page Content -->
      <div class="space-y-6 md:space-y-8 mt-4 md:mt-6">
        
        <!-- Welcome Section -->
        <header class="mb-4 flex flex-wrap items-center justify-between gap-4 mt-2 md:mt-4">
            <div class="flex flex-col gap-1">
                <h2 class="text-3xl font-black tracking-tight text-emerald-800">Form Pengajuan Pengambilan</h2>
                <p class="text-primary-lime font-bold uppercase text-xs tracking-wider">Jadwalkan pengambilan pupuk subsidi Anda di kios resmi</p>
            </div>
        </header>

        <!-- Token Card (Tampil jika berhasil) -->
        <?php if ($tipe_pesan == 'success'): ?>
          <div class="bg-white rounded-3xl p-6 md:p-10 border border-primary-lime/20 shadow-xl mb-8 text-center relative overflow-hidden flex flex-col items-center justify-center max-w-2xl mx-auto mt-8">
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-primary-lime to-primary-forest"></div>
            <div class="w-20 h-20 bg-primary-lime/20 text-primary-forest rounded-full flex items-center justify-center mb-6 shadow-sm ring-8 ring-primary-lime/5">
                <span class="material-symbols-outlined text-4xl">task_alt</span>
            </div>
            <h3 class="text-2xl md:text-3xl font-black text-slate-800 mb-3">Pengajuan Berhasil!</h3>
            <p class="text-slate-500 mb-8 font-medium text-sm md:text-base leading-relaxed">Silakan simpan atau salin token di bawah ini dan tunjukkan kepada petugas kios untuk memproses pengambilan pupuk subsidi Anda.</p>

            <div class="bg-bg-soft border-2 border-dashed border-primary-lime/40 rounded-2xl p-6 md:p-8 mb-8 min-w-[280px]">
                <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mb-3">Kode Token Anda</p>
                <span class="text-4xl md:text-5xl font-mono font-black text-primary-forest tracking-widest select-all block mb-6" id="tokenText"><?php echo $token_display; ?></span>
                
                <div class="pt-5 border-t border-slate-200/60 text-left flex flex-col items-center justify-center">
                    <p class="text-xs text-slate-500 font-bold uppercase tracking-wider mb-1">Total Pembayaran</p>
                    <p class="text-3xl font-black text-slate-800 mb-3">Rp <?php echo number_format($harga_display, 0, ',', '.'); ?></p>
                    <div class="bg-blue-50 border border-blue-100 text-blue-700 px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">info</span>
                        Harap siapkan uang pas saat pengambilan di kios
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                <button onclick="copyToken()" class="w-full sm:w-auto px-8 py-3.5 bg-primary-forest text-white hover:bg-primary-lime hover:text-primary-forest font-bold rounded-xl flex items-center justify-center gap-2 transition-all shadow-md active:scale-95">
                    <span class="material-symbols-outlined text-[20px]" id="copyIcon">content_copy</span>
                    <span id="copyBtnText">Salin Token</span>
                </button>
                <a href="dashboard_petani.php" class="w-full sm:w-auto px-8 py-3.5 bg-slate-100 text-slate-600 hover:bg-slate-200 font-bold rounded-xl flex items-center justify-center transition-all">
                    Kembali ke Beranda
                </a>
            </div>
          </div>
          
        <?php else: ?>
        
          <!-- Alert Error Notifications -->
          <?php if ($pesan): ?>
            <div class="p-4 rounded-xl font-bold flex items-center gap-3 shadow-sm border bg-red-100 text-red-700 border-red-200">
              <span class="material-symbols-outlined text-[20px]">error</span>
              <?php echo $pesan; ?>
            </div>
          <?php endif; ?>
          
        <!-- Form & Kuota Area (Sembunyikan saat sukses) -->
        <div class="flex flex-col gap-6 md:gap-8">
          <!-- Top Section: Quota Summary -->
          <div class="w-full">
            <div class="bg-bg-card rounded-2xl p-6 shadow-sm border border-slate-100">
              <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary-forest">inventory_2</span>
                Sisa Kuota Anda
              </h3>
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Urea Card -->
                <div class="bg-green-50 rounded-xl p-4 border border-green-100">
                  <div class="flex justify-between items-start mb-2">
                    <span class="text-xs font-bold text-primary-forest uppercase tracking-wider">Pupuk Urea</span>
                    <span class="text-[10px] bg-white text-primary-forest px-2 py-0.5 rounded-full font-bold shadow-sm"><?php echo $tahun_ini; ?></span>
                  </div>
                  <div class="flex items-baseline gap-1">
                    <span class="text-2xl font-black text-slate-800"><?php echo number_format($kuota_data[1]['sisa']); ?></span>
                    <span class="text-sm font-medium text-slate-500">kg</span>
                  </div>
                  <div class="mt-3 w-full bg-white h-2 rounded-full overflow-hidden flex">
                    <div class="bg-primary-lime h-full rounded-full transition-all duration-1000" style="width: <?php echo $kuota_data[1]['persen']; ?>%;"></div>
                  </div>
                </div>
                
                <!-- Phonska Card -->
                <div class="bg-green-50 rounded-xl p-4 border border-green-100">
                  <div class="flex justify-between items-start mb-2">
                    <span class="text-xs font-bold text-green-700 uppercase tracking-wider">Pupuk Phonska</span>
                    <span class="text-[10px] bg-white text-green-800 px-2 py-0.5 rounded-full font-bold shadow-sm"><?php echo $tahun_ini; ?></span>
                  </div>
                  <div class="flex items-baseline gap-1">
                    <span class="text-2xl font-black text-slate-800"><?php echo number_format($kuota_data[2]['sisa']); ?></span>
                    <span class="text-sm font-medium text-slate-500">kg</span>
                  </div>
                <div class="mt-3 w-full bg-white h-2 rounded-full overflow-hidden flex">
                    <div class="bg-primary-lime h-full rounded-full transition-all duration-1000" style="width: <?php echo $kuota_data[2]['persen']; ?>%;"></div>
                  </div>
                </div>
                
                <!-- Organik Card -->
                <div class="bg-green-50 rounded-xl p-4 border border-green-100">
                  <div class="flex justify-between items-start mb-2">
                    <span class="text-xs font-bold text-green-700 uppercase tracking-wider">Pupuk Organik</span>
                    <span class="text-[10px] bg-white text-green-800 px-2 py-0.5 rounded-full font-bold shadow-sm"><?php echo $tahun_ini; ?></span>
                  </div>
                  <div class="flex items-baseline gap-1">
                    <span class="text-2xl font-black text-slate-800"><?php echo number_format($kuota_data[3]['sisa']); ?></span>
                    <span class="text-sm font-medium text-slate-500">kg</span>
                  </div>

                <div class="mt-3 w-full bg-white h-2 rounded-full overflow-hidden flex">
                    <div class="bg-primary-lime h-full rounded-full transition-all duration-1000" style="width: <?php echo $kuota_data[3]['persen']; ?>%;"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Bottom Section: Form Section -->
          <div class="w-full">
            <div class="bg-bg-card rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
              <div class="p-6 md:p-8">
                <div class="flex items-center gap-3 mb-8">
                  <div class="w-10 h-10 bg-primary-lime/20 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary-forest">edit_note</span>
                  </div>
                  <h3 class="text-xl font-bold text-slate-800">Detail Pengajuan</h3>
                </div>
                    <form action="" method="POST" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- Jenis Pupuk -->
                            <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 flex items-center gap-1">
                                Pilih Jenis Pupuk
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="jenis_pupuk" required
                                class="w-full bg-white border border-slate-200 rounded-xl py-3 pl-4 pr-12 text-slate-700 focus:ring-2 focus:ring-primary-lime/20 focus:border-primary-lime outline-none transition-all appearance-none">
                                <option disabled selected value="">Pilih pupuk...</option>
                                <option value="1">Urea</option>
                                <option value="2">Phonska</option>
                                <option value="3">Organik</option>
                                </select>
                            </div>
                            </div>

                            <!-- Jumlah Pupuk -->
                            <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 flex items-center gap-1">
                                Jumlah Pupuk (Kg)
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input name="jumlah" min="1" required type="number"
                                placeholder="Contoh: 50"
                                class="w-full bg-white border border-slate-200 rounded-xl py-3 pl-4 pr-12 text-slate-700 focus:ring-2 focus:ring-primary-lime/20 focus:border-primary-lime outline-none transition-all"/>
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-sm pointer-events-none">
                                KG
                                </div>
                            </div>
                            <!-- Area Estimasi Harga (Muncul via JS) -->
                            <div class="mt-3 p-3 bg-slate-50 rounded-xl border border-slate-100 flex justify-between items-center hidden transition-all" id="estimasiBox">
                                <span class="text-sm font-bold text-slate-600">Estimasi Bayar:</span>
                                <span class="text-lg font-black text-primary-forest" id="estimasiHarga">Rp 0</span>
                            </div>
                            <p class="text-xs text-slate-500 font-medium pt-1">
                                Pastikan tidak melebihi sisa kuota Anda.
                            </p>
                            </div>

                            <!-- Tanggal Pengambilan -->
                            <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 flex items-center gap-1">
                                Tanggal Pengambilan
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input name="tanggal" required type="date"
                                class="w-full bg-white border border-slate-200 rounded-xl py-3 pl-4 pr-12 text-slate-700 focus:ring-2 focus:ring-primary-lime/20 focus:border-primary-lime outline-none transition-all 
                                [&::-webkit-calendar-picker-indicator]:opacity-0 
                                [&::-webkit-calendar-picker-indicator]:absolute 
                                [&::-webkit-calendar-picker-indicator]:w-full 
                                [&::-webkit-calendar-picker-indicator]:cursor-pointer"/>
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none">
                                <span class="material-symbols-outlined text-slate-400 text-[20px]">calendar_today</span>
                                </div>
                            </div>
                            </div>

                            <!-- Slot Waktu -->
                            <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 flex items-center gap-1">
                                Slot Waktu
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="slot_waktu" required
                                class="w-full bg-white border border-slate-200 rounded-xl py-3 pl-4 pr-12 text-slate-700 focus:ring-2 focus:ring-primary-lime/20 focus:border-primary-lime outline-none transition-all appearance-none">
                                <option disabled selected value="">Pilih slot waktu...</option>
                                <option value="pagi">Pagi (08:00 - 11:00)</option>
                                <option value="siang">Siang (13:00 - 15:00)</option>
                                <option value="sore">Sore (15:00 - 17:00)</option>
                                </select>
                            </div>
                            </div>

                        </div>

                        <!-- Button -->
                        <div class="pt-8 border-t border-slate-100 flex flex-col sm:flex-row gap-3 sm:justify-end mt-8">
                            <a href="dashboard_petani.php"
                            class="px-6 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition-colors text-center">
                            Batal
                            </a>

                            <button type="submit" name="submit_ajuan"
                            class="px-6 py-2.5 rounded-xl bg-primary-forest text-white font-bold hover:bg-primary-lime hover:text-primary-forest shadow-md transition-all flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">send</span>
                            Kirim Pengajuan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Form Guidance -->
            <div class="mt-6 flex items-start gap-4 p-4 bg-primary-lime/10 rounded-2xl border border-primary-lime/20">
              <span class="material-symbols-outlined text-primary-forest mt-0.5">info</span>
              <div>
                <p class="text-sm font-bold text-primary-forest mb-1">Butuh bantuan?</p>
                <p class="text-xs text-slate-600 leading-relaxed font-medium">Pastikan Anda membawa KTP asli dan kartu tani saat melakukan pengambilan di kios yang dipilih. Jadwal yang sudah disetujui akan muncul di menu <b class="text-slate-800">Riwayat</b>.</p>
              </div>
            </div>
          </div>
        </div>
        
        <?php endif; ?>
        <!-- Akhir dari logika Token / Form -->

      </div>
    </main>
  </div>

  <script>
    const toggleBtn = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');

    if (toggleBtn && sidebar) {
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
    
    // Fungsi untuk menyalin token ke clipboard
    function copyToken() {
        const token = document.getElementById('tokenText').innerText;
        navigator.clipboard.writeText(token).then(() => {
            document.getElementById('copyBtnText').innerText = 'Tersalin!';
            document.getElementById('copyIcon').innerText = 'check';
            setTimeout(() => { 
                document.getElementById('copyBtnText').innerText = 'Salin Token'; 
                document.getElementById('copyIcon').innerText = 'content_copy';
            }, 3000);
        });
    }

    // JS Untuk Perhitungan Harga Otomatis
    const hargaPupuk = {
        1: <?php echo $kuota_data[1]['harga'] ?? 0; ?>,
        2: <?php echo $kuota_data[2]['harga'] ?? 0; ?>,
        3: <?php echo $kuota_data[3]['harga'] ?? 0; ?>
    };

    const selectPupuk = document.querySelector('select[name="jenis_pupuk"]');
    const inputJumlah = document.querySelector('input[name="jumlah"]');
    const estimasiBox = document.getElementById('estimasiBox');
    const estimasiHarga = document.getElementById('estimasiHarga');

    function hitungEstimasi() {
        const pupukId = selectPupuk.value;
        const jumlah = parseInt(inputJumlah.value) || 0;

        if (pupukId && jumlah > 0) {
            const total = hargaPupuk[pupukId] * jumlah;
            estimasiHarga.innerText = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(total);
            estimasiBox.classList.remove('hidden');
        } else {
            estimasiBox.classList.add('hidden');
        }
    }

    if(selectPupuk && inputJumlah) {
        selectPupuk.addEventListener('change', hitungEstimasi);
        inputJumlah.addEventListener('input', hitungEstimasi);
    }
  </script>
</body>
</html>