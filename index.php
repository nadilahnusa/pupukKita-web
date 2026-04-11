<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link href="./assets/css/style.css" rel="stylesheet" />

    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
      rel="stylesheet"
    />

    <link
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
      rel="stylesheet"
    />
  </head>

  <body
    class="bg-bg-soft font-display text-slate-800 transition-colors duration-300"
  >
    <div class="relative flex h-auto min-h-screen w-full flex-col">
      <div class="layout-container flex h-full grow flex-col">
        <!-- HEADER -->
        <header
          class="sticky top-0 z-50 bg-white/70 backdrop-blur-xl px-6 md:px-20 lg:px-40 flex items-center justify-between whitespace-nowrap border-b border-solid border-slate-100 py-4"
        >
          <div class="flex items-center gap-3">
            <div class="text-primary-lime">
              <span class="material-symbols-outlined text-3xl font-semibold"
                >eco</span
              >
            </div>

            <h2
              class="text-primary-forest text-2xl font-bold leading-tight tracking-tight"
            >
              Pupu<span class="text-primary-lime">Kita</span>
            </h2>
          </div>

          <div class="hidden md:flex flex-1 justify-end gap-10 items-center">
            <nav class="flex items-center gap-10">
              <a
                class="text-slate-600 text-sm font-semibold hover:text-primary-lime transition-colors"
                href="#"
              >
                Beranda
              </a>

              <a
                class="text-slate-600 text-sm font-semibold hover:text-primary-lime transition-colors"
                href="#stats"
              >
                Statistik
              </a>

              <a
                class="text-slate-600 text-sm font-semibold hover:text-primary-lime transition-colors"
                href="#features"
              >
                Fitur
              </a>

              <a
                class="text-slate-600 text-sm font-semibold hover:text-primary-lime transition-colors"
                href="#produk"
              >
                Produk
              </a>

              <a
                class="text-slate-600 text-sm font-semibold hover:text-primary-lime transition-colors"
                href="#team"
              >
                Tim
              </a>
            </nav>

            <a
              href="./auth/login.php"
              class="flex min-w-[130px] cursor-pointer items-center justify-center rounded-full h-11 px-6 bg-primary-forest text-white text-sm font-bold tracking-wide hover:bg-primary-lime transition-all duration-300 futuristic-shadow"
            >
              Masuk
            </a>
          </div>

          <button id="menuBtn" class="md:hidden text-primary-forest">
            <span class="material-symbols-outlined text-3xl">menu</span>
          </button>
        </header>

        <div
          id="mobileMenu"
          class="hidden md:hidden flex-col gap-6 px-6 py-6 bg-white border-b border-slate-100"
        >
          <a href="#" class="text-slate-600 font-semibold"> Beranda </a>

          <a href="#stats" class="text-slate-600 font-semibold"> Statistik </a>

          <a href="#features" class="text-slate-600 font-semibold"> Fitur </a>

          <a href="#produk" class="text-slate-600 font-semibold"> Produk </a>

          <a href="#team" class="text-slate-600 font-semibold"> Tim </a>

          <a
            href="./auth/login.php"
            class="mt-4 flex items-center justify-center rounded-full py-3 bg-primary-forest text-white font-semibold"
          >
            Masuk
          </a>
        </div>

        <main class="flex-1">
          <!-- HERO -->
          <section
            class="px-6 md:px-20 lg:px-40 py-16 md:py-28 bg-gradient-to-b from-white to-bg-soft"
          >
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
              <div class="flex flex-col gap-10 order-2 lg:order-1">
                <div class="flex flex-col gap-6">
                  <div
                    class="inline-flex items-center gap-3 bg-primary-lime/10 text-primary-forest px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-[0.2em] border border-primary-lime/20 w-fit"
                  >
                    Kelompok Tani Dsn. Kowak
                  </div>

                  <h1
                    class="text-slate-900 text-5xl md:text-7xl font-bold leading-[1.1] tracking-tight"
                  >
                    Solusi Digital
                    <span>Distribusi</span>
                    <span
                      class="text-transparent bg-clip-text bg-gradient-to-r from-primary-forest to-primary-lime"
                    >
                      Pupuk </span
                    >.
                  </h1>

                  <p
                    class="text-slate-500 text-lg md:text-xl max-w-[560px] leading-relaxed"
                  >
                    Membantu Kelompok Tani dsn. Kowak mengelola distribusi pupuk
                    secara transparan dan efisen.
                  </p>
                </div>

                <div class="flex flex-wrap gap-5">
                  <a
                    href="login.html"
                    class="flex min-w-[180px] cursor-pointer items-center justify-center rounded-2xl h-14 px-8 bg-primary-lime text-primary-forest text-base font-bold hover:bg-primary-forest hover:text-white transition-all duration-500 futuristic-shadow glow-accent"
                  >
                    Masuk
                  </a>

                  <button
                    class="flex min-w-[180px] cursor-pointer items-center justify-center rounded-2xl h-14 px-8 border border-slate-200 bg-white text-slate-800 text-base font-bold hover:border-primary-lime transition-all duration-300"
                  >
                    Lihat Panduan
                  </button>
                </div>
              </div>

              <div class="relative order-1 lg:order-2">
                <div
                  class="absolute -inset-10 bg-primary-lime/5 rounded-full blur-[100px] opacity-60"
                ></div>

                <div
                  class="relative rounded-[2rem] overflow-hidden aspect-square border-4 border-white futuristic-shadow"
                >
                  <img
                    alt="Futuristic greenhouse"
                    class="w-full h-full object-cover"
                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuAowJ2KvPuGaHFBA7CU989oLZ78Y270d5cMQIwNG-PWUQUH4owh26-Pgqm7Zwzfq4ssix5UoPM6o8lJrZ3nC8kU7zg0atDayyq6WpZS6KMZW4WZxfo3LhgUsQ9Bh9nNOj4JM0HmcVo0I5HMa6zNMn6SNfX34iE8uAmvDMQmwM89sbt_UpLf_W3g85DetWZmGrRMIfDoC5BXTPs4Q96qzLcrenWANJIAUP2iRMz12T-9jVtAh62KQ8fkmDhaEqz_v8jqSIhCDCNSRLM"
                  />

                  <div
                    class="absolute inset-0 bg-gradient-to-t from-primary-forest/20 to-transparent"
                  ></div>
                </div>
              </div>
            </div>
          </section>
          <!-- STATS -->
          <section
            class="px-6 md:px-20 lg:px-40 py-20 bg-white border-y border-slate-100"
            id="stats"
          >
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
              <div
                class="flex flex-col gap-4 rounded-3xl p-10 bg-bg-soft/50 border border-slate-100 hover:border-primary-lime/30 transition-all duration-500 group"
              >
                <div
                  class="w-12 h-12 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-primary-lime group-hover:scale-110 transition-transform"
                >
                  <span class="material-symbols-outlined text-2xl font-bold"
                    >conveyor_belt</span
                  >
                </div>

                <div>
                  <p
                    class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-1"
                  >
                    Petani Terdaftar
                  </p>
                  <div class="flex items-baseline gap-2">
                    <p
                      class="text-primary-forest text-4xl font-bold tracking-tight"
                    >
                      150
                    </p>
                    <span
                      class="text-primary-lime text-sm font-bold bg-primary-lime/10 px-2 rounded-lg"
                      >+2%</span
                    >
                  </div>
                </div>

                <div
                  class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden mt-2"
                >
                  <div
                    class="bg-primary-lime h-full w-[75%] rounded-full"
                  ></div>
                </div>
              </div>

              <div
                class="flex flex-col gap-4 rounded-3xl p-10 bg-bg-soft/50 border border-slate-100 hover:border-primary-lime/30 transition-all duration-500 group"
              >
                <div
                  class="w-12 h-12 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-primary-lime group-hover:scale-110 transition-transform"
                >
                  <span class="material-symbols-outlined text-2xl font-bold"
                    >groups</span
                  >
                </div>

                <div>
                  <p
                    class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-1"
                  >
                    Kelompok Tani
                  </p>
                  <div class="flex items-baseline gap-2">
                    <p
                      class="text-primary-forest text-4xl font-bold tracking-tight"
                    >
                      1
                    </p>
                  </div>
                </div>

                <div
                  class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden mt-2"
                >
                  <div
                    class="bg-primary-lime h-full w-[60%] rounded-full"
                  ></div>
                </div>
              </div>

              <div
                class="flex flex-col gap-4 rounded-3xl p-10 bg-bg-soft/50 border border-slate-100 hover:border-primary-lime/30 transition-all duration-500 group"
              >
                <div
                  class="w-12 h-12 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-primary-lime group-hover:scale-110 transition-transform"
                >
                  <span class="material-symbols-outlined text-2xl font-bold"
                    >monitoring</span
                  >
                </div>

                <div>
                  <p
                    class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-1"
                  >
                    Distribusi Tepat Sasaran
                  </p>
                  <div class="flex items-baseline gap-2">
                    <p
                      class="text-primary-forest text-4xl font-bold tracking-tight"
                    >
                      90%
                    </p>
                    <span
                      class="text-primary-lime text-sm font-bold bg-primary-lime/10 px-2 rounded-lg"
                      >+20%</span
                    >
                  </div>
                </div>

                <div
                  class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden mt-2"
                >
                  <div
                    class="bg-primary-lime h-full w-[90%] rounded-full"
                  ></div>
                </div>
              </div>
            </div>
          </section>

          <!-- FEATURES -->
          <section
            class="px-6 md:px-20 lg:px-40 py-24 bg-bg-soft"
            id="features"
          >
            <div class="flex flex-col gap-16">
              <div class="max-w-3xl">
                <h2
                  class="text-primary-lime font-extrabold text-sm uppercase tracking-[0.3em] mb-4"
                >
                  Keunggulan PupuKita
                </h2>

                <h1
                  class="text-primary-forest text-4xl md:text-5xl font-bold leading-tight mb-8"
                >
                  Sistem Pintar Distribusi Pupuk
                </h1>

                <p class="text-slate-500 text-lg leading-relaxed">
                  PupuKita adalah sistem informasi yang membantu kelompok tani
                  dalam mengelola distribusi pupuk secara digital. Sistem ini
                  dirancang untuk transparansi, efisiensi, dan akurasi dalam
                  pencatatan pupuk bersubsidi.
                </p>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <div
                  class="group flex flex-col gap-8 rounded-[2.5rem] border border-white bg-white p-10 futuristic-shadow hover:-translate-y-2 transition-all duration-500"
                >
                  <div
                    class="w-16 h-16 rounded-2xl bg-bg-soft border border-slate-100 flex items-center justify-center text-primary-forest group-hover:bg-primary-lime group-hover:text-white transition-all duration-500"
                  >
                    <span class="material-symbols-outlined text-3xl"
                      >database</span
                    >
                  </div>

                  <div class="flex flex-col gap-4">
                    <h3 class="text-primary-forest text-2xl font-bold">
                      Monitoring Kuota
                    </h3>
                    <p class="text-slate-500 text-base leading-relaxed">
                      Petani dapat melihat sisa kuota pupuk secara real-time
                    </p>
                  </div>
                </div>

                <div
                  class="group flex flex-col gap-8 rounded-[2.5rem] border border-white bg-white p-10 futuristic-shadow hover:-translate-y-2 transition-all duration-500"
                >
                  <div
                    class="w-16 h-16 rounded-2xl bg-bg-soft border border-slate-100 flex items-center justify-center text-primary-forest group-hover:bg-primary-lime group-hover:text-white transition-all duration-500"
                  >
                    <span class="material-symbols-outlined text-3xl"
                      >local_shipping</span
                    >
                  </div>

                  <div class="flex flex-col gap-4">
                    <h3 class="text-primary-forest text-2xl font-bold">
                      Pencatatan Digital
                    </h3>
                    <p class="text-slate-500 text-base leading-relaxed">
                      Admin mencatat distribusi pupuk tanpa sistem manual.
                    </p>
                  </div>
                </div>

                <div
                  class="group flex flex-col gap-8 rounded-[2.5rem] border border-white bg-white p-10 futuristic-shadow hover:-translate-y-2 transition-all duration-500"
                >
                  <div
                    class="w-16 h-16 rounded-2xl bg-bg-soft border border-slate-100 flex items-center justify-center text-primary-forest group-hover:bg-primary-lime group-hover:text-white transition-all duration-500"
                  >
                    <span class="material-symbols-outlined text-3xl"
                      >query_stats</span
                    >
                  </div>

                  <div class="flex flex-col gap-4">
                    <h3 class="text-primary-forest text-2xl font-bold">
                      Laporan Transparan
                    </h3>
                    <p class="text-slate-500 text-base leading-relaxed">
                      Riwayat distribusi pupuk tercatat dan dapat dipantau.
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </section>

          <!-- RPODUCT SECT-->
          <section class="px-6 md:px-20 lg:px-40 py-24 bg-white" id="produk">
            <div class="flex flex-col items-center text-center gap-16">
              <!-- TITLE -->
              <div class="max-w-2xl">
                <h2
                  class="text-primary-lime font-extrabold text-sm uppercase tracking-[0.3em] mb-4"
                >
                  Produk
                </h2>

                <h1 class="text-primary-forest text-4xl md:text-5xl font-bold">
                  Jenis Pupuk
                </h1>

                <p class="text-slate-500 mt-6">
                  Sistem PupuKita membantu pengelolaan dan distribusi berbagai
                  jenis pupuk untuk mendukung produktivitas pertanian.
                </p>
              </div>

              <!-- PRODUCT GRID -->
              <div class="grid grid-cols-1 md:grid-cols-3 gap-10 w-full">
                <!-- UREA -->
                <div
                  class="group bg-bg-soft p-10 rounded-[2rem] border border-slate-100 hover:shadow-xl transition-all duration-500"
                >
                  <div
                    class="w-16 h-16 rounded-full bg-primary-lime/10 flex items-center justify-center text-primary-lime mb-6 mx-auto"
                  >
                    <span class="material-symbols-outlined text-3xl">eco</span>
                  </div>

                  <h3 class="text-primary-forest text-xl font-bold mb-4">
                    Urea
                  </h3>

                  <p class="text-slate-500 text-sm leading-relaxed">
                    Pupuk nitrogen yang membantu meningkatkan pertumbuhan daun
                    dan batang tanaman.
                  </p>
                </div>

                <!-- NPK -->
                <div
                  class="group bg-bg-soft p-10 rounded-[2rem] border border-slate-100 hover:shadow-xl transition-all duration-500"
                >
                  <div
                    class="w-16 h-16 rounded-full bg-primary-lime/10 flex items-center justify-center text-primary-lime mb-6 mx-auto"
                  >
                    <span class="material-symbols-outlined text-3xl"
                      >agriculture</span
                    >
                  </div>

                  <h3 class="text-primary-forest text-xl font-bold mb-4">
                    NPK
                  </h3>

                  <p class="text-slate-500 text-sm leading-relaxed">
                    Mengandung nitrogen, fosfor, dan kalium yang membantu
                    pertumbuhan tanaman secara optimal.
                  </p>
                </div>

                <!-- ORGANIK -->
                <div
                  class="group bg-bg-soft p-10 rounded-[2rem] border border-slate-100 hover:shadow-xl transition-all duration-500"
                >
                  <div
                    class="w-16 h-16 rounded-full bg-primary-lime/10 flex items-center justify-center text-primary-lime mb-6 mx-auto"
                  >
                    <span class="material-symbols-outlined text-3xl"
                      >compost</span
                    >
                  </div>

                  <h3 class="text-primary-forest text-xl font-bold mb-4">
                    Organik
                  </h3>

                  <p class="text-slate-500 text-sm leading-relaxed">
                    Pupuk alami yang membantu memperbaiki struktur tanah dan
                    menjaga kesuburan secara berkelanjutan.
                  </p>
                </div>
              </div>
            </div>
          </section>

          <!-- TEAM SECTION -->

          <section class="px-6 md:px-20 lg:px-40 py-24 bg-white" id="team">
            <div class="flex flex-col items-center text-center gap-16">
              <!-- TITLE -->
              <div class="max-w-2xl">
                <h2
                  class="text-primary-lime font-extrabold text-sm uppercase tracking-[0.3em] mb-4"
                >
                  PupuKita
                </h2>

                <h1 class="text-primary-forest text-4xl md:text-5xl font-bold">
                  Tim Pengembang
                </h1>
              </div>

              <!-- TEAM GRID -->
              <div
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10 w-full"
              >
                <!-- MEMBER 1 -->
                <div class="group flex flex-col gap-6">
                  <div
                    class="aspect-[4/5] rounded-[2rem] overflow-hidden bg-bg-soft border-2 border-slate-50 futuristic-shadow"
                  >
                    <img
                      alt="Nadilah"
                      class="w-full h-full object-cover transition-all duration-700 group-hover:scale-105"
                      src="./assets/img/nadilah.jpg"
                    />
                  </div>

                  <div class="text-center">
                    <h3 class="text-primary-forest text-xl font-bold">
                      Nadilah
                    </h3>
                    <p
                      class="text-primary-lime text-sm font-extrabold uppercase tracking-widest mt-1"
                    >
                      System Analyst
                    </p>
                  </div>
                </div>

                <!-- MEMBER 2 -->
                <div class="group flex flex-col gap-6">
                  <div
                    class="aspect-[4/5] rounded-[2rem] overflow-hidden bg-bg-soft border-2 border-slate-50 futuristic-shadow"
                  >
                    <img
                      alt="Alya Darwanti"
                      class="w-full h-full object-cover transition-all duration-700 group-hover:scale-105"
                      src="./assets/img/Alee.jpeg"
                    />
                  </div>

                  <div class="text-center">
                    <h3 class="text-primary-forest text-xl font-bold">
                      Alya Darwanti
                    </h3>
                    <p
                      class="text-primary-lime text-sm font-extrabold uppercase tracking-widest mt-1"
                    >
                      Web Developer
                    </p>
                  </div>
                </div>

                <!-- MEMBER 3 -->
                <div class="group flex flex-col gap-6">
                  <div
                    class="aspect-[4/5] rounded-[2rem] overflow-hidden bg-bg-soft border-2 border-slate-50 futuristic-shadow"
                  >
                    <img
                      alt="Septia Tsabitha"
                      class="w-full h-full object-cover transition-all duration-700 group-hover:scale-105"
                      src="./assets/img/tsabita.jpeg"
                    />
                  </div>

                  <div class="text-center">
                    <h3 class="text-primary-forest text-xl font-bold">
                      Septia Tsabitha
                    </h3>
                    <p
                      class="text-primary-lime text-sm font-extrabold uppercase tracking-widest mt-1"
                    >
                      UI/UX Designer
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </section>

          <!-- CONTACT SECTION -->
          <section
            class="px-6 md:px-20 lg:px-40 py-24 relative overflow-hidden bg-bg-soft"
          >
            <div
              class="absolute -bottom-24 -left-24 w-[500px] h-[500px] bg-primary-lime/5 rounded-full blur-[100px] pointer-events-none"
            ></div>
            <div
              class="absolute -top-24 -right-24 w-[500px] h-[500px] bg-primary-forest/5 rounded-full blur-[100px] pointer-events-none"
            ></div>

            <div
              class="relative glass-light rounded-[3rem] p-10 md:p-20 grid grid-cols-1 lg:grid-cols-2 gap-20 items-start futuristic-shadow"
            >
              <!-- CONTACT INFO -->
              <div class="space-y-10">
                <div>
                  <h2
                    class="text-primary-lime font-extrabold text-sm uppercase tracking-[0.3em] mb-4"
                  >
                    Kontak
                  </h2>

                  <h1
                    class="text-primary-forest text-4xl md:text-5xl font-bold leading-tight mb-8"
                  >
                    Butuh Bantuan? Hubungi Kami!
                  </h1>

                  <p class="text-slate-500 text-lg">
                    Jika Anda memiliki pertanyaan terkait distribusi pupuk atau
                    penggunaan sistem PupuKita, silakan kirimkan pesan melalui
                    kontak kami atau form berikut.
                  </p>
                </div>

                <div class="space-y-8">
                  <!-- EMAIL -->
                  <div class="flex items-center gap-6 group">
                    <div
                      class="w-14 h-14 rounded-full bg-white futuristic-shadow flex items-center justify-center text-primary-lime group-hover:bg-primary-lime group-hover:text-white transition-all"
                    >
                      <span class="material-symbols-outlined text-2xl"
                        >mail</span
                      >
                    </div>

                    <div>
                      <p
                        class="text-slate-400 text-xs font-bold uppercase tracking-widest"
                      >
                        Digital Mail
                      </p>
                      <p class="text-primary-forest font-bold text-lg">
                        help@pupukita.kowak.id
                      </p>
                    </div>
                  </div>

                  <!-- LOCATION -->
                  <div class="flex items-center gap-6 group">
                    <div
                      class="w-14 h-14 rounded-full bg-white futuristic-shadow flex items-center justify-center text-primary-lime group-hover:bg-primary-lime group-hover:text-white transition-all"
                    >
                      <span class="material-symbols-outlined text-2xl"
                        >location_on</span
                      >
                    </div>

                    <div>
                      <p
                        class="text-slate-400 text-xs font-bold uppercase tracking-widest"
                      >
                        Pusat
                      </p>
                      <p class="text-primary-forest font-bold text-lg">
                        Dsn. Kowak
                      </p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- CONTACT FORM -->
              <form
                class="space-y-8 bg-white/40 p-10 rounded-[2.5rem] border border-white/60"
              >
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                  <div class="space-y-3">
                    <label
                      class="text-xs font-bold uppercase tracking-widest text-slate-400 ml-1"
                    >
                      Identitas
                    </label>

                    <input
                      type="text"
                      placeholder="Nama Anda"
                      class="w-full bg-white border border-slate-100 rounded-2xl px-5 py-4 focus:border-primary-lime focus:ring-4 focus:ring-primary-lime/5 outline-none transition-all placeholder:text-slate-300"
                    />
                  </div>

                  <div class="space-y-3">
                    <label
                      class="text-xs font-bold uppercase tracking-widest text-slate-400 ml-1"
                    >
                      Alamat Email
                    </label>

                    <input
                      type="email"
                      placeholder="email@gmail.com"
                      class="w-full bg-white border border-slate-100 rounded-2xl px-5 py-4 focus:border-primary-lime focus:ring-4 focus:ring-primary-lime/5 outline-none transition-all placeholder:text-slate-300"
                    />
                  </div>
                </div>

                <div class="space-y-3">
                  <label
                    class="text-xs font-bold uppercase tracking-widest text-slate-400 ml-1"
                  >
                    Pesan
                  </label>

                  <textarea
                    rows="5"
                    placeholder="Tulis pesan Anda di sini..."
                    class="w-full bg-white border border-slate-100 rounded-2xl px-5 py-4 focus:border-primary-lime focus:ring-4 focus:ring-primary-lime/5 outline-none transition-all placeholder:text-slate-300"
                  >
                  </textarea>
                </div>

                <button
                  class="w-full bg-primary-forest text-white font-bold py-5 rounded-2xl hover:bg-primary-lime hover:text-primary-forest transition-all duration-500 futuristic-shadow"
                >
                  Kirim Pesan
                </button>
              </form>
            </div>
          </section>
        </main>

        <!-- FOOTER -->
        <footer
          class="px-6 md:px-20 lg:px-40 py-16 border-t border-slate-100 bg-white"
        >
          <div
            class="flex flex-col md:flex-row justify-between items-start md:items-center gap-12"
          >
            <!-- BRAND -->
            <div class="flex flex-col gap-4">
              <div class="flex items-center gap-3">
                <div class="text-primary-lime">
                  <span class="material-symbols-outlined text-3xl font-bold">
                    eco
                  </span>
                </div>

                <h2 class="text-primary-forest text-2xl font-bold">
                  Pupu<span class="text-primary-lime">Kita</span>
                </h2>
              </div>

              <p class="text-slate-400 text-sm max-w-xs">
                mengelola distribusi pupuk secara transparan dan efisen.
              </p>
            </div>

            <!-- FOOTER LINKS -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-10 md:gap-16">
              <div class="flex flex-col gap-4">
                <p
                  class="text-primary-forest font-bold text-sm uppercase tracking-widest"
                >
                  Tentang
                </p>
                <a
                  href="#"
                  class="text-slate-500 hover:text-primary-lime text-sm font-medium transition-colors"
                  >Tentang PupuKita</a
                >
                <a
                  href="#"
                  class="text-slate-500 hover:text-primary-lime text-sm font-medium transition-colors"
                  >Cara Kerja Sistem</a
                >
              </div>

              <div class="flex flex-col gap-4">
                <p
                  class="text-primary-forest font-bold text-sm uppercase tracking-widest"
                >
                  Layanan
                </p>
                <a
                  href="#"
                  class="text-slate-500 hover:text-primary-lime text-sm font-medium transition-colors"
                  >Distribusi Pupuk</a
                >
                <a
                  href="#"
                  class="text-slate-500 hover:text-primary-lime text-sm font-medium transition-colors"
                  >Manajemen Data Petani</a
                >
              </div>

              <div class="flex flex-col gap-4">
                <p
                  class="text-primary-forest font-bold text-sm uppercase tracking-widest"
                >
                  Kontak
                </p>
                <a
                  href="#"
                  class="text-slate-500 hover:text-primary-lime text-sm font-medium transition-colors"
                  >Email Admin</a
                >
                <a
                  href="#"
                  class="text-slate-500 hover:text-primary-lime text-sm font-medium transition-colors"
                  >Lokasi Desa</a
                >
              </div>
            </div>
          </div>

          <!-- BOTTOM FOOTER -->
          <div
            class="mt-16 pt-8 border-t border-slate-50 flex flex-col md:flex-row justify-between items-center gap-4"
          >
            <p
              class="text-slate-400 text-xs font-bold tracking-widest uppercase"
            >
              © 2026 PupuKita OS. All systems operational.
            </p>

            <div class="flex gap-2 items-center">
              <div class="w-2 h-2 rounded-full bg-primary-lime"></div>
              <p
                class="text-primary-lime text-[10px] font-bold uppercase tracking-tighter"
              >
                Build with Love
              </p>
            </div>
          </div>
        </footer>
      </div>
    </div>

    <script>
      const menuBtn = document.getElementById("menuBtn");
      const mobileMenu = document.getElementById("mobileMenu");

      menuBtn.addEventListener("click", () => {
        mobileMenu.classList.toggle("hidden");
        mobileMenu.classList.toggle("flex");
      });
    </script>
  </body>
</html>
