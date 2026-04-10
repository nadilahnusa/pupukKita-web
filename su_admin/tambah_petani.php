<?php 
session_start();

// Proteksi halaman
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'super_admin') {
    header("Location: ../auth/login.php");
    exit(); 
}

include '../config/koneksi.php';

// Proses Simpan Data ketika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $nik = mysqli_real_escape_string($conn, $_POST['nik']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $luas_tanah = mysqli_real_escape_string($conn, $_POST['luas_tanah']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    // Cek apakah NIK sudah pernah didaftarkan
    $cek_nik = mysqli_query($conn, "SELECT id FROM users WHERE nik='$nik'");
    
    if (mysqli_num_rows($cek_nik) > 0) {
        $error = "Gagal: NIK $nik sudah terdaftar di sistem!";
    } else {
        // 1. Insert data ke tabel users terlebih dahulu
        $sql_user = "INSERT INTO users (nik, password, nama, role) VALUES ('$nik', '$password', '$nama', 'petani')";
        
        if (mysqli_query($conn, $sql_user)) {
            // Ambil ID secara manual berdasarkan NIK (Lebih aman dari bug auto-increment)
            $get_user = mysqli_query($conn, "SELECT id FROM users WHERE nik='$nik'");
            $user_data = mysqli_fetch_assoc($get_user);
            $user_id = $user_data['id'];
            
            // 2. Insert data ke tabel petani dengan menyertakan user_id, nama, dan nik
            $sql_petani = "INSERT INTO petani (user_id, nama, nik, luas_tanah, status) VALUES ('$user_id', '$nama', '$nik', '$luas_tanah', '$status')";
            
            if (mysqli_query($conn, $sql_petani)) {
                // Jika kedua proses berhasil, kembali ke halaman manajemen dengan pesan sukses
                header("Location: manajemen_petani.php?msg=add_success");
                exit();
            } else {
                // Gunakan NIK untuk menghapus agar 100% terhapus jika gagal
                mysqli_query($conn, "DELETE FROM users WHERE nik='$nik'");
                $error = "Gagal menyimpan ke tabel petani: " . mysqli_error($conn) . ". (Akun dibatalkan)";
            }
        } else {
            $error = "Gagal mendaftarkan akun pengguna: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>Tambah Petani | PupuKita</title>
  
  <link href="../assets/css/style.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700;900&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
</head>

<body class="bg-bg-soft font-display text-slate-800">
  <div class="flex min-h-screen overflow-x-hidden">
    
    <?php include '../components/sidebar_admin.php'; ?>

    <main class="flex-1 flex flex-col">
      
      <?php include '../components/header_admin.php'; ?>
      
      <div class="p-4 md:p-8 max-w-3xl mx-auto w-full mt-4">
        
        <!-- Header Area -->
        <div class="flex items-center gap-4 mb-8">
            <a href="manajemen_petani.php" class="p-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-500 transition-colors shadow-sm">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-slate-800">Tambah Petani Baru</h2>
                <p class="text-sm text-slate-500 font-medium mt-1">Daftarkan mitra petani baru ke dalam ekosistem PupuKita.</p>
            </div>
        </div>

        <!-- Notifikasi Error (jika query gagal / NIK kembar) -->
        <?php if(isset($error)): ?>
            <div class="bg-red-100 text-red-700 px-4 py-3 rounded-xl mb-6 font-semibold flex items-center gap-2 border border-red-200 shadow-sm">
                <span class="material-symbols-outlined text-[20px]">error</span>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <form action="" method="POST" class="p-6 md:p-8 space-y-6">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">Nama Lengkap</label>
                        <input type="text" name="nama" required placeholder="Masukkan nama petani"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-primary-lime focus:ring-2 focus:ring-primary-lime/20 outline-none bg-white transition-all text-slate-700 font-medium">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">NIK (Nomor Induk Kependudukan)</label>
                        <input type="text" name="nik" required placeholder="16 digit NIK" maxlength="16" minlength="16"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-primary-lime focus:ring-2 focus:ring-primary-lime/20 outline-none bg-white transition-all font-mono text-slate-700 font-medium">
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">Password Akun</label>
                        <input type="text" name="password" required placeholder="Buat password"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-primary-lime focus:ring-2 focus:ring-primary-lime/20 outline-none bg-white transition-all font-mono text-slate-700 font-medium">
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">Luas Tanah Lahan (m²)</label>
                        <input type="number" step="0.01" name="luas_tanah" placeholder="Contoh: 1500" required
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-primary-lime focus:ring-2 focus:ring-primary-lime/20 outline-none bg-white transition-all font-medium text-slate-700 shadow-sm">
                    </div>
                </div>

                <div class="space-y-2 pt-2">
                    <label class="text-sm font-bold text-slate-700">Status Awal</label>
                    <select name="status" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-primary-lime focus:ring-2 focus:ring-primary-lime/20 outline-none bg-white transition-all font-medium text-slate-700 shadow-sm appearance-none">
                        <option value="Aktif">Aktif</option>
                        <option value="Pending" selected>Pending</option>
                        <option value="Nonaktif">Nonaktif</option>
                    </select>
                </div>

                <!-- Form Actions -->
                <div class="pt-8 border-t border-slate-100 flex items-center justify-end gap-3 mt-8">
                    <a href="manajemen_petani.php" class="px-6 py-2.5 rounded-xl font-bold text-slate-600 hover:bg-slate-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary-forest text-white font-bold hover:bg-primary-lime hover:text-primary-forest transition-all shadow-md flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">person_add</span>
                        Tambahkan Petani
                    </button>
                </div>
            </form>
        </div>

      </div>
    </main>
  </div>

  <script>
    const toggleBtn = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');
    if (toggleBtn && sidebar) { toggleBtn.addEventListener('click', () => { if (window.innerWidth < 768) { sidebar.classList.toggle('hidden'); sidebar.classList.toggle('flex'); sidebar.classList.remove('-ml-64'); } else { sidebar.classList.toggle('-ml-64'); sidebar.classList.add('hidden'); sidebar.classList.remove('flex'); } }); }
  </script>
</body>
</html>