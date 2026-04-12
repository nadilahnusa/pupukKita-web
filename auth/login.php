<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Login | PupuKita</title>

    <link href="../assets/css/style.css" rel="stylesheet" />

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
  </head>

  <body class="bg-bg-soft font-display min-h-screen flex items-center justify-center relative overflow-hidden px-4">

    <!-- Background Blur -->
    <div class="absolute w-[250px] h-[250px] sm:w-[500px] sm:h-[500px] 
    bg-primary-lime/20 blur-[80px] sm:blur-[160px] 
    rounded-full -top-20 sm:-top-40 -left-20 sm:-left-40"></div>

    <div class="absolute w-[250px] h-[250px] sm:w-[500px] sm:h-[500px] 
    bg-primary-forest/20 blur-[80px] sm:blur-[160px] 
    rounded-full -bottom-20 sm:-bottom-40 -right-20 sm:-right-40"></div>

    <!-- Container (FIX: tanpa w-full) -->
    <div class="relative flex flex-col items-center gap-6 sm:gap-10 mt-10 sm:mt-0">
      
      <!-- Title -->
      <div class="text-center">
        <h1 class="text-3xl sm:text-4xl font-bold text-primary-forest mt-4">
          Selamat Datang Kembali
        </h1>
        <p class="text-slate-500 mt-2 text-sm sm:text-base">
          PupuKita: Sistem Distribusi Pupuk Dsn. Kowak
        </p>
      </div>

      <!-- Card (FIX utama max-width) -->
      <div class="w-full max-w-[420px] mx-auto 
      bg-white/70 backdrop-blur-xl border border-white/60 
      shadow-xl rounded-[2rem] p-6 sm:p-10">
        
        <?php
        if (isset($_GET['error'])) {
            $pesan = "";
            if ($_GET['error'] == "password") {
                $pesan = "Password yang Anda masukkan salah!";
            } elseif ($_GET['error'] == "nik") {
                $pesan = "NIK tidak terdaftar!";
            }
            if ($pesan != "") {
                echo '<div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl mb-6 text-sm font-medium text-center">
                        <span class="material-symbols-outlined align-middle text-[18px] mr-1">error</span>
                        ' . $pesan . '
                      </div>';
            }
        }
        ?>

        <form id="loginForm" method="POST" action="proses_login.php" class="space-y-6">
          
          <!-- NIK -->
          <div>
            <label class="text-sm font-semibold text-slate-600">NIK</label>
            <div class="relative mt-2">
              <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-primary-lime">fingerprint</span>
              <input type="text" id="nik" name="nik" placeholder="16-digit identification"
                class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 focus:border-primary-lime focus:ring-2 focus:ring-primary-lime/20 outline-none transition" />
            </div>
            <small id="nikError" class="text-red-500 text-xs font-medium"></small>
          </div>

          <!-- Password -->
          <div>
            <label class="text-sm font-semibold text-slate-600">Password</label>
            <div class="relative mt-2">
              <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-primary-lime">lock</span>
              <input type="password" id="password" name="password" placeholder="••••••••"
                class="w-full pl-10 pr-10 py-3 rounded-xl border border-slate-200 focus:border-primary-lime focus:ring-2 focus:ring-primary-lime/20 outline-none transition" />
              
              <button type="button" id="togglePassword"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-primary-lime">
                <span class="material-symbols-outlined">visibility</span>
              </button>
            </div>
            <small id="passwordError" class="text-red-500 text-xs font-medium"></small>
          </div>

          <!-- Forgot -->
          <div class="text-right text-sm">
            <a href="#" class="text-primary-lime hover:underline font-medium">Lupa password?</a>
          </div>

          <!-- Button -->
          <button type="submit"
            class="w-full py-4 rounded-xl bg-gradient-to-r from-primary-lime to-primary-forest text-white font-bold text-sm tracking-wide hover:scale-[1.02] transition-all shadow-lg shadow-primary-lime/30">
            Masuk
          </button>

        </form>
      </div>

      <!-- Footer -->
      <p class="text-xs text-slate-400 text-center font-medium">
        © 2026 PupuKita
      </p>
    </div>

    <script>
      const form = document.getElementById("loginForm");
      const nik = document.getElementById("nik");
      const password = document.getElementById("password");
      const nikError = document.getElementById("nikError");
      const passwordError = document.getElementById("passwordError");
      const toggle = document.getElementById("togglePassword");

      toggle.addEventListener("click", function () {
        password.type = password.type === "password" ? "text" : "password";
      });

      form.addEventListener("submit", function (e) {
        let valid = true;
        nikError.textContent = "";
        passwordError.textContent = "";

        if (!/^\d{16}$/.test(nik.value)) {
          nikError.textContent = "NIK harus 16 digit angka.";
          valid = false;
        }

        if (password.value.trim() === "") {
          passwordError.textContent = "Password wajib diisi.";
          valid = false;
        }

        if (!valid) {
          e.preventDefault();
        }
      });
    </script>
  </body>
</html>