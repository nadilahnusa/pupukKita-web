<header class="h-16 md:h-20 border border-white/80 bg-white/70 backdrop-blur-xl px-4 md:px-8 flex items-center justify-between gap-3 sticky top-0 z-40 shadow-sm rounded-2xl">
  
    <button id="toggleSidebar" class="mr-1 md:hidden p-2 text-slate-400 hover:text-primary-forest bg-slate-50 hover:bg-primary-lime/20 rounded-xl transition-colors focus:outline-none shrink-0" type="button" aria-label="Buka menu navigasi">
        <span class="material-symbols-outlined">menu</span>
    </button>

    <!-- Indikator User Mini -->
    <a href="profil.php" class="flex items-center gap-2 md:gap-3 pl-2 md:pl-4 border-l border-slate-200 group cursor-pointer transition-all">
        <div class="size-9 rounded-full bg-primary-lime/20 group-hover:bg-primary-lime/40 flex items-center justify-center text-primary-forest font-bold shrink-0 shadow-sm border border-primary-lime/30 transition-colors">
            <?php echo strtoupper(substr($_SESSION['nama'] ?? 'U', 0, 1)); ?>
        </div>   
        <div class="text-right hidden sm:block">
            <p class="text-sm font-bold text-slate-700 group-hover:text-primary-forest transition-colors"><?php echo $_SESSION['nama'] ?? 'User'; ?></p>
        </div>
    </a>
    <div class="flex items-center gap-4 min-w-0 flex-1">
        <!-- Ruang kosong di kiri: Nantinya bisa diisi Breadcrumb atau Global Search Bar -->
    </div>

    <div class="flex items-center gap-2 md:gap-4 shrink-0">   
       
        <button class="relative p-2 text-slate-400 hover:text-primary-lime bg-slate-50 hover:bg-primary-lime/10 rounded-xl transition-colors shrink-0" type="button" aria-label="Notifikasi">
            <span class="material-symbols-outlined">notifications</span>
            <span class="absolute top-2 right-2 size-2.5 bg-red-500 rounded-full border-2 border-white"></span>
        </button>
    </div>
</header>
