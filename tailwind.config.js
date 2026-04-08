/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./*.{html,php}", // Untuk scan index.php / index.html di luar
    "./components/**/*.php", // Untuk scan file di folder components
    "./auth/**/*.php", // Untuk scan file di folder auth
    "./su_admin/**/*.php", // Untuk scan file di folder su_admin
    "./gudang/**/*.php", // Untuk scan file di folder gudang
    "./petani/**/*.php", // Untuk scan file di folder petani
    "./assets/js/**/*.js", // Untuk scan jika ada manipulasi class di JavaScript
  ],
  theme: {
    extend: {
      colors: {
        "primary-lime": "#84cc16",
        "primary-forest": "#14532d",
        "bg-soft": "#f9fafb",
        "bg-card": "#ffffff",
      },
      fontFamily: {
        display: ["Space Grotesk", "sans-serif"],
      },
      borderRadius: {
        DEFAULT: "0.5rem",
        lg: "0.75rem",
        xl: "1rem",
        "2xl": "1.5rem",
        full: "9999px",
      },
    },
  },
  plugins: [
    require("@tailwindcss/forms"),
    require("@tailwindcss/container-queries"),
  ],
};
