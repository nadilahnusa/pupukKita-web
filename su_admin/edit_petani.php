<?php 
session_start();

// Proteksi halaman
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'super_admin') {
    header("Location: ../auth/login.php");
    exit(); 
}

include '../config/koneksi.php';

$id = $_GET['id'] ?? 0;

// Ambil data petani saat ini untuk ditampilkan di form
$sql = "SELECT petani.*, users.nik AS user_nik, users.nama AS user_nama, users.id AS id_user
        FROM petani 
        LEFT JOIN users ON petani.user_id = users.id 
        WHERE petani.id = '$id'";
$result = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Data Petani tidak ditemukan di database.");
}

// Proses Update Data ketika form disubmit (Tombol Simpan ditekan)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $nik = mysqli_real_escape_string($conn, $_POST['nik']);
    $luas_tanah = mysqli_real_escape_string($conn, $_POST['luas_tanah']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $id_user = $data['id_user'];
    
    // Update data di tabel users (karena nama dan NIK ada di tabel users)
    $sql_update_users = "UPDATE users SET nama='$nama', nik='$nik' WHERE id='$id_user'";
    $update_users = mysqli_query($conn, $sql_update_users);
    
    // Update data luas tanah, nama, nik, dan status di tabel petani
    $sql_update_petani = "UPDATE petani SET luas_tanah='$luas_tanah', nama='$nama', nik='$nik', status='$status' WHERE id='$id'";
    $update_petani = mysqli_query($conn, $sql_update_petani);
    
    if ($update_users && $update_petani) {
        // Jika berhasil, lempar kembali ke manajemen petani dengan pesan sukses
        header("Location: manajemen_petani.php?msg=edit_success");
        exit();
    } else {
        $error = "Gagal memperbarui data: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>Edit Petani | PupuKita</title>
  
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
                <h2 class="text-2xl md:text-3xl font-bold text-slate-800">Edit Data Petani</h2>
                <p class="text-sm text-slate-500 font-medium mt-1">Perbarui informasi dan status akun untuk FR-<?php echo str_pad($data['id'], 3, '0', STR_PAD_LEFT); ?>.</p>
            </div>
        </div>

        <!-- Notifikasi Error (jika query gagal) -->
        <?php if(isset($error)): ?>
            <div class="bg-red-100 text-red-700 px-4 py-3 rounded-xl mb-6 font-semibold flex items-center gap-2 border border-red-200 shadow-sm">
                <span class="material-symbols-outlined text-[20px]">error</span>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <form id="formEditPetani" action="" method="POST" class="p-6 md:p-8 space-y-6">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama (Hanya dibaca, diambil dari tabel users) -->
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">Nama Lengkap</label>
                        <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($data['user_nama'] ?? ''); ?>" required
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-primary-lime focus:ring-2 focus:ring-primary-lime/20 outline-none bg-white transition-all text-slate-700 font-medium">
                        <p id="error-nama" class="text-red-500 text-xs mt-1 font-medium hidden"></p>
                    </div>

                    <!-- NIK (Hanya dibaca) -->
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">NIK (Nomor Induk Kependudukan)</label>
                        <input type="text" id="nik" name="nik" value="<?php echo htmlspecialchars($data['user_nik'] ?? ''); ?>" required minlength="16" maxlength="16"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-primary-lime focus:ring-2 focus:ring-primary-lime/20 outline-none bg-white transition-all font-mono text-slate-700 font-medium">
                        <p id="error-nik" class="text-red-500 text-xs mt-1 font-medium hidden"></p>
                    </div>
                </div>

                <!-- Luas Tanah -->
                <div class="space-y-2 pt-2">
                    <label class="text-sm font-bold text-slate-700">Luas Tanah Lahan (m²)</label>
                    <input type="number" step="0.01" id="luas_tanah" name="luas_tanah" value="<?php echo htmlspecialchars($data['luas_tanah'] ?? ''); ?>" placeholder="Contoh: 1500" required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-primary-lime focus:ring-2 focus:ring-primary-lime/20 outline-none bg-white transition-all font-medium text-slate-700 shadow-sm">
                    <p id="error-luas-tanah" class="text-red-500 text-xs mt-1 font-medium hidden"></p>
                </div>

                <!-- Status Petani -->
                <div class="space-y-2 pt-2">
                    <label class="text-sm font-bold text-slate-700">Status Akun</label>
                    <select name="status" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-primary-lime focus:ring-2 focus:ring-primary-lime/20 outline-none bg-white transition-all font-medium text-slate-700 shadow-sm appearance-none">
                        <option value="Aktif" <?php echo (($data['status'] ?? '') == 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
                        <option value="Pending" <?php echo (($data['status'] ?? '') == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                        <option value="Nonaktif" <?php echo (($data['status'] ?? '') == 'Nonaktif') ? 'selected' : ''; ?>>Nonaktif</option>
                    </select>
                </div>

                <!-- Form Actions -->
                <div class="pt-8 border-t border-slate-100 flex items-center justify-end gap-3 mt-8">
                    <a href="manajemen_petani.php" class="px-6 py-2.5 rounded-xl font-bold text-slate-600 hover:bg-slate-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary-forest text-white font-bold hover:bg-primary-lime hover:text-primary-forest transition-all shadow-md flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        Simpan Perubahan
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
    if (toggleBtn && sidebar) {
      toggleBtn.addEventListener('click', () => {
        if (window.innerWidth < 768) {
          sidebar.classList.toggle('hidden'); sidebar.classList.toggle('flex'); sidebar.classList.remove('-ml-64'); 
        } else {
          sidebar.classList.toggle('-ml-64'); sidebar.classList.add('hidden'); sidebar.classList.remove('flex');
        }
      });
    }

    const formEdit = document.getElementById('formEditPetani');
    if (formEdit) {
        const inputNama = document.getElementById('nama');
        const inputNik = document.getElementById('nik');
        const inputLuasTanah = document.getElementById('luas_tanah');

        const errNama = document.getElementById('error-nama');
        const errNik = document.getElementById('error-nik');
        const errLuasTanah = document.getElementById('error-luas-tanah');

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

        function checkLuasTanah() {
            const val = inputLuasTanah.value.trim();
            if (val !== '' && (isNaN(val) || parseFloat(val) <= 0)) {
                return toggleError(inputLuasTanah, errLuasTanah, "Luas tanah harus berupa angka valid lebih dari 0.");
            }
            return toggleError(inputLuasTanah, errLuasTanah, null);
        }

        inputNama.addEventListener('input', checkNama);
        inputNik.addEventListener('input', checkNik);
        inputLuasTanah.addEventListener('input', checkLuasTanah);

        formEdit.addEventListener('submit', function(e) {
            const v1 = checkNama();
            const v2 = checkNik();
            const v3 = checkLuasTanah();
            if (!v1 || !v2 || !v3) {
                e.preventDefault();
            }
        });
    }
  </script>
</body>
</html>