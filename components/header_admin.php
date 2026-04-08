<header class="h-16 border-b border-primary-lime/20 bg-bg-card px-8 flex items-center justify-between sticky top-0 z-10 shadow-sm">
  
    <button id="toggleSidebar" class="mr-4 p-2 text-slate-400 hover:text-primary-forest bg-slate-50 hover:bg-primary-lime/20 rounded-xl transition-colors focus:outline-none">
        <span class="material-symbols-outlined">menu</span>
    </button>
    <div class="flex items-center gap-4">
        <h2 class="text-lg font-semibold"><?php echo $pageTitle ?? 'Dashboard Overview'; ?></h2>
    </div>
    <div class="flex items-center gap-4">
        <div class="relative hidden sm:block">
        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-primary-lime font-bold text-sm">search</span>
        <input class="pl-10 pr-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-primary-lime/20 focus:border-primary-lime text-sm bg-bg-soft w-64 outline-none transition-all" placeholder="Search data..." type="text"/>
        </div>

        <button class="relative p-2 text-slate-400 hover:text-primary-lime bg-slate-50 hover:bg-primary-lime/10 rounded-xl transition-colors">
        <span class="material-symbols-outlined">notifications</span>
        <span class="absolute top-2 right-2 size-2.5 bg-red-500 rounded-full border-2 border-white"></span>
        </button>

        <button class="p-2 text-slate-400 hover:text-primary-lime bg-slate-50 hover:bg-primary-lime/10 rounded-xl transition-colors">
        <span class="material-symbols-outlined">settings</span>
        </button>
  </div>
</header>