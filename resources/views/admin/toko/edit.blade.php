@extends('layouts.app')

@section('title', 'Edit Toko - Admin')

@section('content')
@include('admin.partials.nav')

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">Edit Data Toko</h1>
            <p class="text-xs text-slate-500">Ubah informasi untuk toko <strong class="text-emerald-700">{{ $toko->nama }}</strong></p>
        </div>
        <a href="{{ route('admin.toko.index') }}" class="text-xs text-slate-500 hover:text-slate-800 font-semibold flex items-center gap-1">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="mb-5 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
            <p class="font-bold flex items-center gap-1.5"><i class="fa-solid fa-triangle-exclamation"></i> Terdapat kesalahan validasi:</p>
            @foreach($errors->all() as $error)
                <p>- {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
        <form action="{{ route('admin.toko.update', $toko->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Nama Toko --}}
            <div>
                <label for="nama" class="block text-xs font-semibold text-slate-700 mb-1">Nama Toko <span class="text-rose-500">*</span></label>
                <input 
                    type="text" 
                    id="nama" 
                    name="nama" 
                    value="{{ old('nama', $toko->nama) }}" 
                    required 
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500"
                />
            </div>

            {{-- Alamat --}}
            <div>
                <label for="alamat" class="block text-xs font-semibold text-slate-700 mb-1">Alamat Lengkap <span class="text-rose-500">*</span></label>
                <textarea 
                    id="alamat" 
                    name="alamat" 
                    rows="2" 
                    required 
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500"
                >{{ old('alamat', $toko->alamat) }}</textarea>
            </div>

            {{-- Deskripsi --}}
            <div>
                <label for="deskripsi" class="block text-xs font-semibold text-slate-700 mb-1">Deskripsi Toko</label>
                <textarea 
                    id="deskripsi" 
                    name="deskripsi" 
                    rows="3" 
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500"
                >{{ old('deskripsi', $toko->deskripsi) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Media Sosial --}}
                <div>
                    <label for="media_sosial" class="block text-xs font-semibold text-slate-700 mb-1">Media Sosial (Instagram)</label>
                    <input 
                        type="text" 
                        id="media_sosial" 
                        name="media_sosial" 
                        value="{{ old('media_sosial', $toko->media_sosial) }}" 
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    />
                </div>

                {{-- Jam Operasional --}}
                <div>
                    <label for="jam_operasional" class="block text-xs font-semibold text-slate-700 mb-1">Jam Operasional</label>
                    <input 
                        type="text" 
                        id="jam_operasional" 
                        name="jam_operasional" 
                        value="{{ old('jam_operasional', $toko->jam_operasional) }}" 
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    />
                </div>
            </div>

            {{-- Section Koordinat Lokasi Toko & GPS Detection --}}
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-800 flex items-center gap-1.5">
                            <i class="fa-solid fa-location-dot text-emerald-600"></i>
                            Koordinat Lokasi Toko (GPS)
                        </label>
                        <p class="text-[11px] text-slate-500">Gunakan tombol di sebelah kanan atau atur koordinat secara manual.</p>
                    </div>
                    <button
                        type="button"
                        id="btn-detect-gps"
                        onclick="detectStoreLocation()"
                        class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center gap-1.5 shrink-0"
                    >
                        <i class="fa-solid fa-location-crosshairs"></i>
                        Deteksi Lokasi Saya
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    {{-- Latitude --}}
                    <div>
                        <label for="latitude" class="block text-[11px] font-semibold text-slate-600 mb-1">Latitude</label>
                        <input 
                            type="text" 
                            id="latitude" 
                            name="latitude" 
                            value="{{ old('latitude', $toko->latitude) }}" 
                            placeholder="-8.1695" 
                            oninput="updateMapFromInputs()"
                            class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-mono font-bold focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        />
                    </div>

                    {{-- Longitude --}}
                    <div>
                        <label for="longitude" class="block text-[11px] font-semibold text-slate-600 mb-1">Longitude</label>
                        <input 
                            type="text" 
                            id="longitude" 
                            name="longitude" 
                            value="{{ old('longitude', $toko->longitude) }}" 
                            placeholder="113.7152" 
                            oninput="updateMapFromInputs()"
                            class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-mono font-bold focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        />
                    </div>
                </div>

                <p id="gps-status" class="text-[11px] text-slate-500 italic">
                    Klik "Deteksi Lokasi Saya" untuk mendeteksi koordinat otomatis dari perangkat, atau klik/geser marker pada peta.
                </p>

                {{-- Map Preview --}}
                <div class="mt-2">
                    <div class="flex justify-between items-center mb-1 text-[11px]">
                        <span class="font-semibold text-slate-600">Preview Lokasi Peta (OpenStreetMap):</span>
                        <span id="map-coords-label" class="font-mono text-slate-400 font-bold"></span>
                    </div>
                    <div id="map-preview" class="w-full h-56 rounded-xl border border-slate-300 overflow-hidden shadow-inner z-0"></div>
                </div>
            </div>

            {{-- Pemilik Toko --}}
            <div>
                <label for="pemilik_id" class="block text-xs font-semibold text-slate-700 mb-1">Pemilik Toko (User Terdaftar)</label>
                <select 
                    id="pemilik_id" 
                    name="pemilik_id" 
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500"
                >
                    <option value="">-- Belum ada pemilik --</option>
                    @foreach($pemiliks as $p)
                        <option value="{{ $p->id }}" {{ old('pemilik_id', $toko->pemilik_id) == $p->id ? 'selected' : '' }}>
                            {{ $p->name }} ({{ $p->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('admin.toko.index') }}" class="px-4 py-2.5 border border-slate-300 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let map, marker;

    function initMapPreview() {
        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');

        let lat = parseFloat(latInput.value) || -8.1695;
        let lng = parseFloat(lngInput.value) || 113.7152;

        map = L.map('map-preview').setView([lat, lng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a> contributors'
        }).addTo(map);

        marker = L.marker([lat, lng], { draggable: true }).addTo(map);
        updateCoordsLabel(lat, lng);

        // Saat marker digeser
        marker.on('dragend', function (e) {
            const coord = marker.getLatLng();
            latInput.value = coord.lat.toFixed(6);
            lngInput.value = coord.lng.toFixed(6);
            updateCoordsLabel(coord.lat, coord.lng);
        });

        // Saat peta diklik
        map.on('click', function (e) {
            marker.setLatLng(e.latlng);
            latInput.value = e.latlng.lat.toFixed(6);
            lngInput.value = e.latlng.lng.toFixed(6);
            updateCoordsLabel(e.latlng.lat, e.latlng.lng);
        });
    }

    function updateMapFromInputs() {
        const lat = parseFloat(document.getElementById('latitude').value);
        const lng = parseFloat(document.getElementById('longitude').value);

        if (!isNaN(lat) && !isNaN(lng) && map && marker) {
            const newLatLng = new L.LatLng(lat, lng);
            marker.setLatLng(newLatLng);
            map.panTo(newLatLng);
            updateCoordsLabel(lat, lng);
        }
    }

    function updateCoordsLabel(lat, lng) {
        const label = document.getElementById('map-coords-label');
        if (label) {
            label.textContent = `${parseFloat(lat).toFixed(6)}, ${parseFloat(lng).toFixed(6)}`;
        }
    }

    function detectStoreLocation() {
        const status = document.getElementById('gps-status');
        const btn = document.getElementById('btn-detect-gps');

        if (!navigator.geolocation) {
            status.textContent = 'Geolocation tidak didukung oleh browser ini.';
            status.className = 'text-[11px] text-rose-500 font-semibold';
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mendeteksi...';
        status.textContent = 'Meminta izin lokasi GPS perangkat...';
        status.className = 'text-[11px] text-amber-600 italic font-semibold';

        navigator.geolocation.getCurrentPosition(
            (position) => {
                const lat = position.coords.latitude.toFixed(6);
                const lng = position.coords.longitude.toFixed(6);

                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;

                updateMapFromInputs();

                status.textContent = `Lokasi GPS berhasil dideteksi! (${lat}, ${lng})`;
                status.className = 'text-[11px] text-emerald-600 font-bold';

                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-check"></i> Terdeteksi';
            },
            (error) => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-location-crosshairs"></i> Coba Lagi';

                let msg = 'Gagal mendeteksi lokasi. Pastikan izin lokasi browser diaktifkan, atau isi koordinat secara manual.';
                if (error.code === error.PERMISSION_DENIED) {
                    msg = 'Gagal mendeteksi lokasi. Pastikan izin lokasi browser diaktifkan, atau isi koordinat secara manual.';
                } else if (error.code === error.POSITION_UNAVAILABLE) {
                    msg = 'Informasi lokasi tidak tersedia pada perangkat. Silakan isi koordinat secara manual.';
                } else if (error.code === error.TIMEOUT) {
                    msg = 'Waktu permintaan lokasi habis. Silakan coba lagi atau isi koordinat secara manual.';
                }

                status.textContent = msg;
                status.className = 'text-[11px] text-rose-600 font-semibold';
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    }

    document.addEventListener('DOMContentLoaded', initMapPreview);
</script>
@endpush
