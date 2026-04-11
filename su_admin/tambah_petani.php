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
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
</head>

<body class="bg-bg-soft font-display text-slate-800">
  <div class="relative flex h-screen w-full overflow-hidden">
    
    <?php include '../components/sidebar_admin.php'; ?>

    <main class="flex-1 overflow-y-auto p-4 md:p-8">
      
      <div class="space-y-6 md:space-y-8 mt-4 md:mt-6">
        
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
            <form id="formTambahPetani" action="" method="POST" class="p-6 md:p-8 space-y-6">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">Nama Lengkap</label>
                        <input type="text" id="nama" name="nama" required placeholder="Masukkan nama petani"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-primary-lime focus:ring-2 focus:ring-primary-lime/20 outline-none bg-white transition-all text-slate-700 font-medium">
                        <p id="error-nama" class="text-red-500 text-xs mt-1 font-medium hidden"></p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">NIK (Nomor Induk Kependudukan)</label>
                        <input type="text" id="nik" name="nik" required placeholder="16 digit NIK" maxlength="16" minlength="16"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-primary-lime focus:ring-2 focus:ring-primary-lime/20 outline-none bg-white transition-all font-mono text-slate-700 font-medium">
                        <p id="error-nik" class="text-red-500 text-xs mt-1 font-medium hidden"></p>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">Password Akun</label>
                        <input type="password" id="password" name="password" required placeholder="Buat password"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-primary-lime focus:ring-2 focus:ring-primary-lime/20 outline-none bg-white transition-all font-mono text-slate-700 font-medium">
                        <p id="error-password" class="text-red-500 text-xs mt-1 font-medium hidden"></p>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">Luas Tanah Lahan (m²)</label>
                        <input type="number" step="0.01" id="luas_tanah" name="luas_tanah" placeholder="Contoh: 1500" required
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-primary-lime focus:ring-2 focus:ring-primary-lime/20 outline-none bg-white transition-all font-medium text-slate-700 shadow-sm">
                        <p id="error-luas-tanah" class="text-red-500 text-xs mt-1 font-medium hidden"></p>
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

    const formTambah = document.getElementById('formTambahPetani');
    if (formTambah) {
        const inputNama = document.getElementById('nama');
        const inputNik = document.getElementById('nik');
        const inputPassword = document.getElementById('password');
        const inputLuasTanah = document.getElementById('luas_tanah');

        const errNama = document.getElementById('error-nama');
        const errNik = document.getElementById('error-nik');
        const errPassword = document.getElementById('error-password');
        const errLuasTanah = document.getElementById('error-luas-tanah');

        // Helper function untuk menampilkan/menyembunyikan error dan warna border
        function toggleError(input, errorEl, message) {
            if (message) {
                errorEl.textContent = message;
                errorEl.classList.remove('hidden');
                input.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500/20');
                input.classList.remove('border-slate-200', 'focus:border-primary-lime', 'focus:ring-primary-lime/20');
                return false;
            } else {
                errorEl.classList.add('hidden');
                input.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500/20');
                input.classList.add('border-slate-200', 'focus:border-primary-lime', 'focus:ring-primary-lime/20');
                return true;
            }
        }

        function checkNama() {
            const val = inputNama.value.trim();
            if (val !== '' && !/^[a-zA-Z\s]+$/.test(val)) {
                return toggleError(inputNama, errNama, "Nama hanya boleh berisi huruf.");
            }
            return toggleError(inputNama, errNama, null);
        }

        function checkNik() {
            const val = inputNik.value.trim();
            if (val !== '' && !/^\d{16}$/.test(val)) {
                return toggleError(inputNik, errNik, "NIK harus terdiri dari 16 digit angka.");
            }
            return toggleError(inputNik, errNik, null);
        }

        function checkPassword() {
            const val = inputPassword.value.trim();
            if (val !== '' && (val.length < 6 || !/(?=.*[a-zA-Z])(?=.*\d)(?=.*[\W_])/.test(val))) {
                return toggleError(inputPassword, errPassword, "Password minimal 6 digit, kombinasi huruf, angka & simbol.");
            }
            return toggleError(inputPassword, errPassword, null);
        }

        function checkLuasTanah() {
            const val = inputLuasTanah.value.trim();
            if (val !== '' && (isNaN(val) || parseFloat(val) <= 0)) {
                return toggleError(inputLuasTanah, errLuasTanah, "Luas tanah harus berupa angka valid lebih dari 0.");
            }
            return toggleError(inputLuasTanah, errLuasTanah, null);
        }

        // Terapkan validasi secara real-time saat mengetik
        inputNama.addEventListener('input', checkNama);
        inputNik.addEventListener('input', checkNik);
        inputPassword.addEventListener('input', checkPassword);
        inputLuasTanah.addEventListener('input', checkLuasTanah);

        // Tetap cegah submit jika masih ada yang error
        formTambah.addEventListener('submit', function(e) {
            const v1 = checkNama();
            const v2 = checkNik();
            const v3 = checkPassword();
            const v4 = checkLuasTanah();
            if (!v1 || !v2 || !v3 || !v4) {
                e.preventDefault();
            }
        });
    }
  </script>
</body>
</html>