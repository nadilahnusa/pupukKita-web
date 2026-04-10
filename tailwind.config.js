/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./*.{html,php}",
    "./components/**/*.php",
    "./auth/**/*.php",
    "./su_admin/**/*.{php,html}",
    "./gudang/**/*.php",
    "./petani/**/*.php",
    "./assets/js/**/*.js",
  ],
  darkMode: "class",
  theme: {
    extend: {
      colors: {
        // Sesuai CDN config-mu sebelumnya
        primary: "#86bd05",
        secondary: "#4d7c0f",
        accent: "#d9f99d",

        // Light/dark background
        "background-light": "#f7f8f5",
        "background-dark": "#1d230f",

        // Tambahan milik config versi kamu
        "primary-lime": "#84cc16",
        "primary-forest": "#14532d",
        "bg-soft": "#f9fafb",
        "bg-card": "#ffffff",
      },
      fontFamily: {
        display: ["Work Sans", "Space Grotesk", "sans-serif"],
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