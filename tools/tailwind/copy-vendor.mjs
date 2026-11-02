// Menyalin pustaka JS/font dari node_modules ke public/ agar bisa dilayani lokal (tanpa CDN).
import { cpSync, mkdirSync, existsSync } from 'node:fs';
import { dirname, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const here = dirname(fileURLToPath(import.meta.url));
const nm = resolve(here, 'node_modules');
const pub = resolve(here, '../../public');

const salin = (dari, ke, opsi = {}) => {
    if (!existsSync(dari)) throw new Error(`File tidak ditemukan: ${dari}`);
    mkdirSync(dirname(ke), { recursive: true });
    cpSync(dari, ke, opsi);
    console.log('salin', ke.replace(pub, 'public'));
};

salin(resolve(nm, 'leaflet/dist/leaflet.js'), resolve(pub, 'vendor/leaflet/leaflet.js'));
salin(resolve(nm, 'leaflet/dist/leaflet.css'), resolve(pub, 'vendor/leaflet/leaflet.css'));
salin(resolve(nm, 'leaflet/dist/images'), resolve(pub, 'vendor/leaflet/images'), { recursive: true });
salin(resolve(nm, 'sortablejs/Sortable.min.js'), resolve(pub, 'vendor/sortable/Sortable.min.js'));
salin(resolve(nm, 'alpinejs/dist/cdn.min.js'), resolve(pub, 'vendor/alpine/alpine.min.js'));

for (const bobot of [400, 500, 600, 700, 800]) {
    const nama = `plus-jakarta-sans-latin-${bobot}-normal.woff2`;
    salin(resolve(nm, '@fontsource/plus-jakarta-sans/files', nama), resolve(pub, 'fonts', nama));
}
