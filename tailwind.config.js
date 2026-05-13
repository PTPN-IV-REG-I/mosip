/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  darkMode: 'class',
  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter', 'ui-sans-serif', 'system-ui'],
      },
      colors: {
        primary: {
          50:  '#eef2ff',
          100: '#e0e7ff',
          200: '#c7d2fe',
          300: '#a5b4fc',
          400: '#818cf8',
          500: '#6366f1',
          600: '#4f46e5',
          700: '#4338ca',
          800: '#3730a3',
          900: '#312e81',
        },
        accent: {
          from: '#6366f1',
          to:   '#a855f7',
        },
        surface: {
          DEFAULT: '#ffffff',
          50:  '#f8fafc',
          100: '#f1f5f9',
          200: '#e2e8f0',
        },
        dark: {
          bg:      '#0f172a',
          surface: '#1e293b',
          border:  '#334155',
          muted:   '#475569',
        },
      },
      boxShadow: {
        glass:  '0 8px 32px 0 rgba(99,102,241,0.12)',
        card:   '0 4px 24px -4px rgba(15,23,42,0.08)',
        'card-hover': '0 16px 48px -8px rgba(99,102,241,0.22)',
        glow:   '0 0 24px rgba(99,102,241,0.35)',
      },
      backgroundImage: {
        'gradient-primary':  'linear-gradient(135deg, #6366f1 0%, #a855f7 100%)',
        'gradient-surface':  'linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(241,245,249,0.6) 100%)',
        'gradient-dark':     'linear-gradient(135deg, #1e293b 0%, #0f172a 100%)',
        'gradient-sidebar':  'linear-gradient(180deg, #4f46e5 0%, #312e81 100%)',
      },
      transitionTimingFunction: {
        'bounce-soft': 'cubic-bezier(0.34, 1.56, 0.64, 1)',
        'smooth':      'cubic-bezier(0.4, 0, 0.2, 1)',
      },
      keyframes: {
        'fade-in': {
          '0%':   { opacity: '0', transform: 'translateY(12px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        },
        'slide-in-left': {
          '0%':   { opacity: '0', transform: 'translateX(-20px)' },
          '100%': { opacity: '1', transform: 'translateX(0)' },
        },
        'scale-in': {
          '0%':   { opacity: '0', transform: 'scale(0.95)' },
          '100%': { opacity: '1', transform: 'scale(1)' },
        },
        shimmer: {
          '0%':   { backgroundPosition: '-200% 0' },
          '100%': { backgroundPosition: '200% 0' },
        },
        pulse: {
          '0%, 100%': { opacity: '1' },
          '50%':      { opacity: '0.4' },
        },
        'ripple': {
          '0%':   { transform: 'scale(0)', opacity: '0.6' },
          '100%': { transform: 'scale(4)', opacity: '0' },
        },
        'float': {
          '0%, 100%': { transform: 'translateY(0px)' },
          '50%':      { transform: 'translateY(-6px)' },
        },
      },
      animation: {
        'fade-in':       'fade-in 0.4s cubic-bezier(0.4,0,0.2,1) both',
        'slide-in-left': 'slide-in-left 0.35s cubic-bezier(0.4,0,0.2,1) both',
        'scale-in':      'scale-in 0.3s cubic-bezier(0.34,1.56,0.64,1) both',
        shimmer:         'shimmer 1.6s linear infinite',
        pulse:           'pulse 2s ease-in-out infinite',
        float:           'float 3s ease-in-out infinite',
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ],
}
