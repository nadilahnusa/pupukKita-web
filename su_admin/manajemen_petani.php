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

// --- LOGIKA PAGINATION ---
$items_per_page = 10; // Jumlah item per halaman
$page_aktif = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page_aktif < 1) $page_aktif = 1;

// Hitung total data yang sesuai dengan filter
$count_sql = "SELECT COUNT(petani.id) as total 
              FROM petani 
              LEFT JOIN users ON petani.user_id = users.id" . $where_sql;
$count_result = $conn->query($count_sql);
$total_items = $count_result->fetch_assoc()['total'] ?? 0;
$total_pages = ceil($total_items / $items_per_page);

// Pastikan halaman saat ini tidak melebihi total halaman
if ($page_aktif > $total_pages && $total_pages > 0) {
    $page_aktif = $total_pages;
}

// Hitung offset untuk query
$offset = ($page_aktif - 1) * $items_per_page;

// Terapkan filter ke Query SQL
$sql = "SELECT petani.*, users.nik AS user_nik, users.nama AS user_nama
        FROM petani
        LEFT JOIN users ON petani.user_id = users.id" . $where_sql . " ORDER BY petani.id DESC LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $items_per_page, $offset);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>Manajemen Petani | PupuKita</title>
  
  <link href="../assets/css/style.css" rel="stylesheet" />
  
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
</head>

<body class="bg-bg-soft font-display text-slate-800">
  <div class="relative flex h-screen w-full overflow-hidden">
    
    <?php include '../components/sidebar_admin.php'; ?>

    <main class="flex-1 overflow-y-auto p-4 md:p-8">
      
      <?php include '../components/header_admin.php'; ?>
      
      <div class="space-y-6 md:space-y-8 mt-4 md:mt-6">

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
            <h2 class="text-3xl font-black tracking-tight text-emerald-800">Direktori Petani</h2>
            <p class="text-primary-lime font-bold uppercase text-xs tracking-wider">Kelola data <?php echo number_format($total_items); ?> petani yang terdaftar di ekosistem.</p>
          </div>
          <a href="tambah_petani.php" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-primary-forest text-white font-bold rounded-lg hover:bg-primary-lime transition-all shadow-md">
            <span class="material-symbols-outlined text-[20px]">person_add</span>
            <span class="text-sm">Tambah Petani Baru</span>
          </a>
        </div>

        <!-- Filters and Table Card -->
        <div class="bg-bg-card rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
          <!-- Filter Bar -->
          <form method="GET" action="" class="p-4 md:p-6 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white">
            <div class="flex flex-wrap gap-3 flex-1">
              <div class="relative min-w-[300px] sm:min-w-[250px]">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg">search</span>
                <input name="search" value="<?php echo htmlspecialchars($search_query); ?>" class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-lime/20 focus:border-primary-lime outline-none transition-all bg-bg-soft" placeholder="Cari nama, NIK, atau ID..." type="text" onblur="this.form.submit()"/>
              </div>
            <select name="status" onchange="this.form.submit()" class="min-w-[180px] border border-slate-200 rounded-xl text-sm px-4 py-2 focus:ring-2 focus:ring-primary-lime/20 focus:border-primary-lime outline-none bg-bg-soft text-slate-600 font-medium">
                <option value="all">Semua Status</option>
                <option value="Aktif" <?php echo $status_filter == 'Aktif' ? 'selected' : ''; ?>>Aktif</option>
              <option value="Pending" <?php echo $status_filter == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="Nonaktif" <?php echo $status_filter == 'Nonaktif' ? 'selected' : ''; ?>>Nonaktif</option>
              </select>
            </div>
            <div class="flex items-center gap-2">
              <button type="button" class="flex items-center gap-2 px-4 py-2 text-primary-forest bg-primary-lime/10 hover:bg-primary-lime/20 rounded-xl transition-colors text-sm font-bold">
                <span class="material-symbols-outlined text-[18px]">upload</span>
                Impor
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
      <button type="button"
         class="delete-btn p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
         data-id="<?php echo $row['id']; ?>"
         data-nama="<?php echo htmlspecialchars($nama, ENT_QUOTES); ?>"
         title="Hapus">
        <span class="material-symbols-outlined text-[18px]">delete</span>
      </button>

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
            <?php
                $start_item = ($page_aktif - 1) * $items_per_page + 1;
                $end_item = min($start_item + $items_per_page - 1, $total_items);
            ?>
            <p class="text-xs font-semibold text-slate-500">
                <?php if ($total_items > 0): ?>
                    Menampilkan <?php echo $start_item; ?> hingga <?php echo $end_item; ?> dari <?php echo $total_items; ?> petani
                <?php else: ?>
                    Tidak ada data untuk ditampilkan
                <?php endif; ?>
            </p>

            <?php if ($total_pages > 1): ?>
            <div class="flex gap-2 w-full sm:w-auto justify-between sm:justify-end">
                <?php
                    // Fungsi untuk membuat URL dengan parameter yang ada
                    function page_url($page, $status_filter, $search_query) {
                        $params = [];
                        if ($status_filter && $status_filter !== 'all') $params['status'] = $status_filter;
                        if ($search_query) $params['search'] = $search_query;
                        $params['page'] = $page;
                        return '?' . http_build_query($params);
                    }
                ?>

                <!-- Tombol Prev -->
                <a href="<?php echo page_url($page_aktif - 1, $status_filter, $search_query); ?>" 
                   class="px-3 py-1.5 border border-slate-200 bg-white rounded-lg text-xs font-bold text-slate-500 hover:bg-slate-100 transition-colors shadow-sm <?php if($page_aktif <= 1){ echo 'opacity-50 cursor-not-allowed'; } ?>">Prev</a>

                <?php
                    // Logika untuk menampilkan nomor halaman
                    $range = 1;
                    $start = max(1, $page_aktif - $range);
                    $end = min($total_pages, $page_aktif + $range);

                    if ($start > 1) {
                        echo '<a href="'.page_url(1, $status_filter, $search_query).'" class="px-3 py-1.5 border border-slate-200 bg-white rounded-lg text-xs font-bold text-slate-500 hover:bg-slate-100 transition-colors shadow-sm">1</a>';
                        if ($start > 2) echo '<span class="px-2 py-1.5 text-slate-400 font-bold text-xs">...</span>';
                    }

                    for ($i = $start; $i <= $end; $i++) {
                        if ($i == $page_aktif) {
                            echo '<button class="px-3 py-1.5 bg-primary-forest text-white rounded-lg text-xs font-bold shadow-md">'.$i.'</button>';
                        } else {
                            echo '<a href="'.page_url($i, $status_filter, $search_query).'" class="px-3 py-1.5 border border-slate-200 bg-white rounded-lg text-xs font-bold text-slate-500 hover:bg-slate-100 transition-colors shadow-sm">'.$i.'</a>';
                        }
                    }

                    if ($end < $total_pages) {
                        if ($end < $total_pages - 1) echo '<span class="px-2 py-1.5 text-slate-400 font-bold text-xs">...</span>';
                        echo '<a href="'.page_url($total_pages, $status_filter, $search_query).'" class="px-3 py-1.5 border border-slate-200 bg-white rounded-lg text-xs font-bold text-slate-500 hover:bg-slate-100 transition-colors shadow-sm">'.$total_pages.'</a>';
                    }
                ?>

                <!-- Tombol Next -->
                <a href="<?php echo page_url($page_aktif + 1, $status_filter, $search_query); ?>" 
                   class="px-3 py-1.5 border border-slate-200 bg-white rounded-lg text-xs font-bold text-slate-500 hover:bg-slate-100 transition-colors shadow-sm <?php if($page_aktif >= $total_pages){ echo 'opacity-50 cursor-not-allowed'; } ?>">Next</a>
            </div>
            <?php endif; ?>
          </div>
        </div>

      </div>
    </main>

    <!-- Custom Delete Confirmation Modal -->
    <div id="deleteConfirmModal" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-md flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
        <div id="deleteConfirmCard" class="bg-white rounded-3xl shadow-2xl w-full max-w-xs p-5 md:p-6 text-center transform scale-95 transition-all duration-300" role="dialog" aria-modal="true" aria-labelledby="modal-title">
            <div class="mx-auto w-14 h-14 rounded-full bg-red-100 flex items-center justify-center text-red-600 mb-4 shadow-sm border-[4px] border-white ring-4 ring-red-50">
                <span class="material-symbols-outlined text-2xl">delete_forever</span>
            </div>
            <h3 id="modal-title" class="text-xl font-black text-slate-800">Konfirmasi Hapus</h3>
            <p class="text-slate-500 mt-1 text-sm font-medium leading-relaxed">
                Apakah Anda yakin ingin menghapus data petani <strong id="modalPetaniName" class="text-slate-800 font-bold"></strong>? Tindakan ini tidak dapat dibatalkan.
            </p>
            <div class="flex flex-col-reverse sm:flex-row justify-center gap-3 mt-6">
                <button id="cancelDeleteBtn" type="button" class="w-full px-4 py-2.5 rounded-xl font-bold text-slate-600 bg-slate-50 hover:bg-slate-100 border border-slate-200 transition-colors">
                    Batal
                </button>
                <a id="modalConfirmDeleteBtn" href="#" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-red-500 text-white font-bold hover:brightness-110 transition-all shadow-md">
                    Ya, Hapus
                </a>
            </div>
        </div>
    </div>
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

    // Custom Modal Logic
    const deleteModal = document.getElementById('deleteConfirmModal');
    const deleteCard = document.getElementById('deleteConfirmCard');
    if (deleteModal) {
        const cancelBtn = document.getElementById('cancelDeleteBtn');
        const confirmBtn = document.getElementById('modalConfirmDeleteBtn');
        const petaniNameSpan = document.getElementById('modalPetaniName');
        const deleteButtons = document.querySelectorAll('.delete-btn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.getAttribute('data-id');
                const nama = this.getAttribute('data-nama');
                
                petaniNameSpan.textContent = nama;
                confirmBtn.href = `hapus_petani.php?id=${id}`;
                
                deleteModal.classList.remove('pointer-events-none');
                // Trigger reflow untuk animasi fade-in
                void deleteModal.offsetWidth;
                deleteModal.classList.remove('opacity-0');
                deleteCard.classList.remove('scale-95');
            });
        });

        function hideModal() {
            deleteModal.classList.add('opacity-0');
            deleteCard.classList.add('scale-95');
            setTimeout(() => {
                deleteModal.classList.add('pointer-events-none');
            }, 300); // Sesuaikan dengan durasi animasi
        }

        cancelBtn.addEventListener('click', hideModal);

        deleteModal.addEventListener('click', function(event) {
            // Sembunyikan modal jika user klik area overlay (di luar kotak modal)
            if (event.target === deleteModal) {
                hideModal();
            }
        });
    }
  </script>
</body>
</html>