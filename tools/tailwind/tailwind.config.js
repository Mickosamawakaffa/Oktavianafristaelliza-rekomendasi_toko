/** Konfigurasi ini identik dengan tailwind.config di resources/views/layouts/partials/head.blade.php (versi CDN). */
module.exports = {
    content: [
        '../../resources/views/**/*.blade.php',
        '../../app/**/*.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', 'system-ui', '-apple-system', 'sans-serif'],
            },
            colors: {
                ink: '#0F172A',
                body: '#334155',
                muted: '#526071',
                primary: {
                    DEFAULT: '#0F766E',
                    dark: '#0B5C56',
                    tint: '#E6F4F1',
                },
                hero: {
                    DEFAULT: '#0E2A3B',
                    accent: '#5EEAD4',
                },
                page: '#F3F5F8',
                surface: '#FFFFFF',
                border: {
                    DEFAULT: '#E2E8F0',
                    strong: '#CBD5E1',
                },
                star: '#F59E0B',
                success: {
                    bg: '#DCFCE7',
                    text: '#166534',
                    dot: '#16A34A',
                },
                gold: {
                    bg: '#FEF3C7',
                    text: '#92400E',
                },
                silver: {
                    bg: '#E2E8F0',
                    text: '#334155',
                },
                bronze: {
                    bg: '#FFEDD5',
                    text: '#9A3412',
                },
            },
            boxShadow: {
                soft: '0 1px 2px rgba(15, 23, 42, 0.05)',
                card: '0 8px 24px rgba(15, 23, 42, 0.05)',
                teal: '0 8px 24px rgba(15, 118, 110, 0.12)',
            },
            borderRadius: {
                card: '16px',
                btn: '10px',
            },
        },
    },
    plugins: [],
};
