/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./app/Views/**/*.php", // Le dice a Tailwind que escanee todas tus vistas de CodeIgniter
  ],
  theme: {
    extend: {
      colors: { // Aquí va la configuración de colores que tenías en el script
        background: '#ffffff',
        foreground: '#1f2937',
        primary: '#dc2626',
        'primary-foreground': '#ffffff',
        secondary: '#1f2937',
        'secondary-foreground': '#ffffff',
        muted: '#f3f4f6',
        'muted-foreground': '#6b7280',
        border: '#e5e7eb',
        input: '#f9fafb',
        card: '#f9fafb'
      }
    },
  },
  plugins: [],
}