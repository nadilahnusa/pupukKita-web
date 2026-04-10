<header class="h-16 md:h-20 md:border-b border-primary-lime/20 bg-bg-card px-4 md:px-8 flex items-center justify-between gap-3 sticky top-0 z-10 shadow-sm">
  
    <button id="toggleSidebar" class="mr-1 md:hidden p-2 text-slate-400 hover:text-primary-forest bg-slate-50 hover:bg-primary-lime/20 rounded-xl transition-colors focus:outline-none shrink-0" type="button" aria-label="Buka menu navigasi">
        <span class="material-symbols-outlined">menu</span>
    </button>
    <div class="flex items-center gap-4 min-w-0 flex-1">
        <h2 class="text-lg md:text-lg font-semibold truncate">
            Halo, <?php echo $_SESSION['nama'] ?? 'User'; ?> 👋
        </h2>
    </div>
    <div class="flex items-center gap-2 md:gap-4 shrink-0">   
        <div class="relative hidden sm:block">
        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-primary-lime font-bold text-sm">search</span>
        <input class="pl-10 pr-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-primary-lime/20 focus:border-primary-lime text-sm bg-bg-soft w-64 outline-none transition-all" placeholder="Search data..." type="text"/>
        </div>

        <button class="relative p-2 text-slate-400 hover:text-primary-lime bg-slate-50 hover:bg-primary-lime/10 rounded-xl transition-colors shrink-0" type="button" aria-label="Notifikasi">
        <span class="material-symbols-outlined">notifications</span>
        <span class="absolute top-2 right-2 size-2.5 bg-red-500 rounded-full border-2 border-white"></span>
        </button>

  </div>
</header>
