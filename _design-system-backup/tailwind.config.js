/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./**/*.{html,js,php,blade.php}",
  ],
  theme: {
    extend: {
      colors: {
        // Deep Forest Green representing the main party color
        primary: {
          DEFAULT: '#005B2B',
          50: '#e6f7ed',
          100: '#ccefdc',
          200: '#99dfb8',
          300: '#66cf95',
          400: '#33bf71',
          500: '#005B2B', // Main brand primary
          600: '#004d24',
          700: '#003e1c',
          800: '#002e15',
          900: '#001f0e',
        },
        // Golden Yellow representing the Kaaba door and lettering accent
        accent: {
          DEFAULT: '#D97706',
          50: '#fef3c7',
          100: '#fde68a',
          200: '#fcd34d',
          300: '#fbbf24',
          400: '#fbbf24',
          500: '#f59e0b',
          600: '#D97706', // Main brand accent
          700: '#b45309',
          800: '#92400e',
          900: '#78350f',
        },
        // Deeper contrast dark green for Sidebar
        'dark-green': {
          DEFAULT: '#00401E',
          hover: '#003318',
        },
        // Smooth neutral scale
        neutral: {
          50: '#F8FAFC',
          100: '#F1F5F9',
          200: '#E2E8F0',
          300: '#CBD5E1',
          400: '#94A3B8',
          500: '#64748B',
          600: '#475569',
          700: '#334155',
          800: '#1E293B',
          900: '#0F172A',
        }
      },
      fontFamily: {
        sans: ['"Plus Jakarta Sans"', 'Inter', 'system-ui', 'sans-serif'],
      },
      borderRadius: {
        'xl': '0.75rem',
        '2xl': '1rem',
        '3xl': '1.5rem',
      },
      boxShadow: {
        'ambient': '0px 4px 12px rgba(15, 23, 42, 0.05)',
        'premium': '0px 8px 30px rgba(15, 23, 42, 0.08)',
      },
      spacing: {
        'sidebar-width': '280px',
      }
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
