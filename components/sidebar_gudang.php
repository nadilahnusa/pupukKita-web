<aside id="sidebar" class="w-64 border-r border-primary-lime/20 bg-white hidden md:flex flex-col shrink-0 transition-all duration-300 fixed md:relative top-0 left-0 md:left-auto z-50 h-screen shadow-2xl md:shadow-none rounded-r-3xl">
  
  <!-- Tombol Toggle Desktop -->
  <button id="desktopToggleBtn" class="absolute top-6 -right-10 w-10 h-10 bg-white border border-l-0 border-primary-lime/20 rounded-r-xl hidden md:flex items-center justify-center text-slate-400 hover:text-primary-lime transition-colors shadow-sm focus:outline-none z-50 group">
      <span class="material-symbols-outlined transition-transform group-hover:scale-110">menu_open</span>
  </button>

  <div class="p-6 border-b border-primary-lime/20">
    <div class="flex items-center gap-3">
      <div class="bg-primary-forest rounded-lg p-2 text-white shadow-md">
        <span class="material-symbols-outlined">agriculture</span>
      </div>
      <div>
        <h1 class="text-xl font-bold tracking-tight text-slate-900 leading-none">PupuKita</h1>
        <p class="text-xs font-bold text-primary-lime uppercase mt-1">Portal Admin Gudang</p>
      </div>
    </div>
  </div>

  <nav class="flex-1 p-4 space-y-2">
    <a class="flex items-center gap-3 px-4 py-3 rounded-lg bg-primary-lime/20 text-primary-forest font-bold shadow-sm" href="dashboard_su_admin.php">
      <span class="material-symbols-outlined">dashboard</span>
      <span class="text-sm">Dashboard</span>
    </a>
    
    <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-600 hover:bg-primary-lime/10 hover:text-primary-forest transition-colors font-medium" href="manajemen_petani.php">
      <span class="material-symbols-outlined">local_shipping</span>
      <span class="text-sm">Permintaan Pengambilan</span>
    </a>
    <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-600 hover:bg-primary-lime/10 hover:text-primary-forest transition-colors font-medium" href="#">
      <span class="material-symbols-outlined">fact_check</span>
      <span class="text-sm">Validasi Pengambilan</span>
    </a>
    <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-600 hover:bg-primary-lime/10 hover:text-primary-forest transition-colors font-medium" href="#">
      <span class="material-symbols-outlined">inventory_2</span>
      <span class="text-sm">Stok Gudang</span>
    </a>
  </nav>

  <div class="p-4 mt-auto border-t border-primary-lime/20">
    <a href="../auth/logout.php" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg border border-red-200 text-red-600 hover:bg-red-50 transition-colors text-sm font-bold shadow-sm">
      <span class="material-symbols-outlined text-[20px]">logout</span>
      Keluar
    </a>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
        const deskBtn = document.getElementById('desktopToggleBtn');
        const side = document.getElementById('sidebar');
        if(deskBtn && side) {
            deskBtn.addEventListener('click', () => {
                side.classList.toggle('-ml-64');
                const icon = deskBtn.querySelector('span');
                icon.textContent = side.classList.contains('-ml-64') ? 'menu' : 'menu_open';
            });
        }
    });
  </script>
</aside>