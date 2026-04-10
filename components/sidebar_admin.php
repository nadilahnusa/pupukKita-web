<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside id="sidebar" class="w-64 border-r border-primary-lime/20 bg-white hidden md:flex flex-col shrink-0 transition-all duration-300 fixed md:sticky top-0 left-0 z-50 h-screen shadow-2xl md:shadow-none">
  
  <div class="h-16 flex items-center px-4 border-b border-primary-lime/20">
    <div class="flex items-center gap-3">
      <div class="bg-primary-forest rounded-lg p-2 text-white shadow-md">
        <span class="material-symbols-outlined">agriculture</span>
      </div>
      <div>
        <h1 class="text-xl font-bold tracking-tight text-slate-900 leading-none">PupuKita</h1>
        <p class="text-xs font-bold text-primary-lime uppercase mt-1">Super Admin Console</p>
      </div>
    </div>
  </div>

  <nav class="flex-1 p-4 space-y-2">
    <a href="dashboard_su_admin.php"
   class="flex items-center gap-3 px-4 py-3 rounded-lg 
   <?php echo $current_page == 'dashboard_su_admin.php'
   ? 'bg-primary-lime/20 text-primary-forest font-bold shadow-sm'
   : 'text-slate-600 hover:bg-primary-lime/10 hover:text-primary-forest transition-colors font-medium'; ?>">
    
    <span class="material-symbols-outlined">dashboard</span>
    <span class="text-sm">Dashboard</span>
    </a>
    
    <a href="manajemen_petani.php"
   class="flex items-center gap-3 px-4 py-3 rounded-lg 
   <?php echo $current_page == 'manajemen_petani.php'
   ? 'bg-primary-lime/20 text-primary-forest font-bold shadow-sm'
   : 'text-slate-600 hover:bg-primary-lime/10 hover:text-primary-forest transition-colors font-medium'; ?>">

    <span class="material-symbols-outlined">group</span>
    <span class="text-sm">Data Petani</span>
    </a>
    <a href="#"
   class="flex items-center gap-3 px-4 py-3 rounded-lg 
   <?php echo $current_page == 'stok_pupuk.php'
   ? 'bg-primary-lime/20 text-primary-forest font-bold shadow-sm'
   : 'text-slate-600 hover:bg-primary-lime/10 hover:text-primary-forest transition-colors font-medium'; ?>">

    <span class="material-symbols-outlined">inventory_2</span>
    <span class="text-sm">Stok Pupuk</span>
    </a>
    <a href="#"
   class="flex items-center gap-3 px-4 py-3 rounded-lg 
   <?php echo $current_page == 'stok_pupuk.php'
   ? 'bg-primary-lime/20 text-primary-forest font-bold shadow-sm'
   : 'text-slate-600 hover:bg-primary-lime/10 hover:text-primary-forest transition-colors font-medium'; ?>">

    <span class="material-symbols-outlined">check_circle</span>
    <span class="text-sm">Validasi Data</span>
    </a>
    <a href="#"
   class="flex items-center gap-3 px-4 py-3 rounded-lg 
   <?php echo $current_page == 'stok_pupuk.php'
   ? 'bg-primary-lime/20 text-primary-forest font-bold shadow-sm'
   : 'text-slate-600 hover:bg-primary-lime/10 hover:text-primary-forest transition-colors font-medium'; ?>">

    <span class="material-symbols-outlined">bar_chart</span>
    <span class="text-sm">Laporan</span>
    </a>
    <a href="#"
   class="flex items-center gap-3 px-4 py-3 rounded-lg 
   <?php echo $current_page == 'stok_pupuk.php'
   ? 'bg-primary-lime/20 text-primary-forest font-bold shadow-sm'
   : 'text-slate-600 hover:bg-primary-lime/10 hover:text-primary-forest transition-colors font-medium'; ?>">

    <span class="material-symbols-outlined">history</span>
    <span class="text-sm">Log Aktifitas</span>
    </a>
    <a href="#"
   class="flex items-center gap-3 px-4 py-3 rounded-lg 
   <?php echo $current_page == 'stok_pupuk.php'
   ? 'bg-primary-lime/20 text-primary-forest font-bold shadow-sm'
   : 'text-slate-600 hover:bg-primary-lime/10 hover:text-primary-forest transition-colors font-medium'; ?>">

    <span class="material-symbols-outlined">help</span>
    <span class="text-sm">Pusat bantuan</span>
    </a>
  </nav>

  <div class="p-4 mt-auto border-t border-primary-lime/20">
    <button class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg border border-red-200 text-red-600 hover:bg-red-50 transition-colors text-sm font-bold shadow-sm">
      <span class="material-symbols-outlined text-[20px]">logout</span>
      Keluar
    </button>
  </div>
</aside>