@extends('layouts.app')

@section('title', 'Edit Toko Saya - Pemilik Toko')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-[#0F172A]">Edit Toko Saya</h1>
            <p class="text-xs sm:text-sm text-[#526071]">Ubah informasi untuk toko <strong class="text-[#0F766E]">{{ $toko->nama }}</strong></p>
        </div>
        <a href="{{ route('pemilik.dashboard') }}" class="text-xs font-semibold text-[#526071] hover:text-[#0F172A] flex items-center gap-1.5 min-h-[44px]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Dashboard</span>
        </a>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="mb-5 p-4 rounded-[12px] bg-[#FEF2F2] border border-[#FECACA] text-[#991B1B] text-xs space-y-1">
            <p class="font-bold flex items-center gap-1.5">
                <svg class="w-4 h-4 text-[#DC2626] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>Terdapat kesalahan validasi:</span>
            </p>
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-[16px] border border-[#E2E8F0] p-6 shadow-soft">
        <form action="{{ route('pemilik.toko.update', $toko->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Nama Toko --}}
            <div>
                <label for="nama" class="block text-xs font-semibold text-[#0F172A] mb-1.5">
                    Nama Toko <span class="text-[#991B1B]">*</span>
                </label>
                <input 
                    type="text" 
                    id="nama" 
                    name="nama" 
                    value="{{ old('nama', $toko->nama) }}" 
                    required 
                    class="w-full px-3.5 py-2.5 rounded-[10px] border border-[#CBD5E1] bg-white text-xs text-[#0F172A] focus:outline-none focus:border-[#0F766E] focus:ring-1 focus:ring-[#0F766E] min-h-[44px]"
                />
            </div>

            {{-- Foto/Gambar Toko --}}
            <div>
                <label for="gambar" class="block text-xs font-semibold text-[#0F172A] mb-1.5">Foto / Gambar Toko</label>
                @if($toko->gambar)
                    <div class="mb-3">
                        <p class="text-[11px] text-[#526071] mb-1 font-medium">Gambar saat ini:</p>
                        <img src="{{ asset('storage/' . $toko->gambar) }}" alt="{{ $toko->nama }}" class="h-36 w-auto max-w-full object-cover rounded-[10px] border border-[#E2E8F0] shadow-soft" />
                    </div>
                @endif
                <input 
                    type="file" 
                    id="gambar" 
                    name="gambar" 
                    accept="image/jpeg,image/png,image/jpg,image/webp"
                    onchange="previewImage(this, 'image-preview-edit')"
                    class="w-full px-3.5 py-2 rounded-[10px] border border-[#CBD5E1] text-xs text-[#526071] file:mr-3 file:py-1.5 file:px-3 file:rounded-[8px] file:border-0 file:text-xs file:font-semibold file:bg-[#E6F4F1] file:text-[#0F766E] hover:file:bg-[#d0ece5] cursor-pointer focus:outline-none focus:border-[#0F766E]"
                />
                <p class="text-[11px] text-[#526071] mt-1">Format: JPG, PNG, WEBP. Maksimal 2MB. Biarkan kosong jika tidak ingin mengubah gambar.</p>
                <div id="image-preview-edit" class="mt-3 hidden">
                    <p class="text-[11px] text-[#0F766E] font-semibold mb-1">Preview gambar baru:</p>
                    <img src="#" alt="Preview Baru" class="h-36 w-auto max-w-full object-cover rounded-[10px] border border-[#0F766E] shadow-soft" />
                </div>
            </div>

            {{-- Alamat --}}
            <div>
                <label for="alamat" class="block text-xs font-semibold text-[#0F172A] mb-1.5">
                    Alamat Lengkap Toko <span class="text-[#991B1B]">*</span>
                </label>
                <textarea 
                    id="alamat" 
                    name="alamat" 
                    rows="2" 
                    required 
                    class="w-full px-3.5 py-2.5 rounded-[10px] border border-[#CBD5E1] bg-white text-xs text-[#0F172A] focus:outline-none focus:border-[#0F766E] focus:ring-1 focus:ring-[#0F766E]"
                >{{ old('alamat', $toko->alamat) }}</textarea>
            </div>

            {{-- Deskripsi --}}
            <div>
                <label for="deskripsi" class="block text-xs font-semibold text-[#0F172A] mb-1.5">Deskripsi Toko</label>
                <textarea 
                    id="deskripsi" 
                    name="deskripsi" 
                    rows="3" 
                    class="w-full px-3.5 py-2.5 rounded-[10px] border border-[#CBD5E1] bg-white text-xs text-[#0F172A] focus:outline-none focus:border-[#0F766E] focus:ring-1 focus:ring-[#0F766E]"
                >{{ old('deskripsi', $toko->deskripsi) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Media Sosial --}}
                <div>
                    <label for="media_sosial" class="block text-xs font-semibold text-[#0F172A] mb-1.5">Media Sosial (Instagram)</label>
                    <input 
                        type="text" 
                        id="media_sosial" 
                        name="media_sosial" 
                        value="{{ old('media_sosial', $toko->media_sosial) }}" 
                        placeholder="@nama_toko"
                        class="w-full px-3.5 py-2.5 rounded-[10px] border border-[#CBD5E1] bg-white text-xs text-[#0F172A] focus:outline-none focus:border-[#0F766E] focus:ring-1 focus:ring-[#0F766E] min-h-[44px]"
                    />
                </div>

                {{-- Jam Operasional --}}
                <div>
                    <label for="jam_operasional" class="block text-xs font-semibold text-[#0F172A] mb-1.5">Jam Operasional</label>
                    <input 
                        type="text" 
                        id="jam_operasional" 
                        name="jam_operasional" 
                        value="{{ old('jam_operasional', $toko->jam_operasional) }}" 
                        placeholder="Contoh: 10:00 - 21:00 WIB"
                        class="w-full px-3.5 py-2.5 rounded-[10px] border border-[#CBD5E1] bg-white text-xs text-[#0F172A] focus:outline-none focus:border-[#0F766E] focus:ring-1 focus:ring-[#0F766E] min-h-[44px]"
                    />
                </div>
            </div>

            {{-- Section Koordinat Lokasi Toko & GPS Detection --}}
            <div class="p-4 rounded-[12px] bg-[#F8FAFC] border border-[#E2E8F0] space-y-3">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                    <div>
                        <label class="block text-xs font-bold text-[#0F172A] flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-[#0F766E]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Koordinat Lokasi Toko (GPS)</span>
                        </label>
                        <p class="text-[11px] text-[#526071]">Gunakan tombol di sebelah kanan atau atur koordinat secara manual.</p>
                    </div>
                    <button
                        type="button"
                        id="btn-detect-gps"
                        onclick="detectStoreLocation()"
                        class="px-3.5 py-2 bg-[#E6F4F1] hover:bg-[#d0ece5] text-[#0F766E] font-bold text-xs rounded-[10px] border border-[#0F766E]/20 transition flex items-center gap-1.5 shrink-0 min-h-[44px]"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Deteksi Lokasi Saya</span>
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    {{-- Latitude --}}
                    <div>
                        <label for="latitude" class="block text-[11px] font-semibold text-[#526071] mb-1">Latitude</label>
                        <input 
                            type="text" 
                            id="latitude" 
                            name="latitude" 
                            value="{{ old('latitude', $toko->latitude) }}" 
                            placeholder="-8.1695" 
                            oninput="updateMapFromInputs()"
                            class="w-full px-3 py-2 rounded-[10px] border border-[#CBD5E1] bg-white text-xs font-mono font-bold text-[#0F172A] focus:outline-none focus:border-[#0F766E] focus:ring-1 focus:ring-[#0F766E] min-h-[44px]"
                        />
                    </div>

                    {{-- Longitude --}}
                    <div>
                        <label for="longitude" class="block text-[11px] font-semibold text-[#526071] mb-1">Longitude</label>
                        <input 
                            type="text" 
                            id="longitude" 
                            name="longitude" 
                            value="{{ old('longitude', $toko->longitude) }}" 
                            placeholder="113.7152" 
                            oninput="updateMapFromInputs()"
                            class="w-full px-3 py-2 rounded-[10px] border border-[#CBD5E1] bg-white text-xs font-mono font-bold text-[#0F172A] focus:outline-none focus:border-[#0F766E] focus:ring-1 focus:ring-[#0F766E] min-h-[44px]"
                        />
                    </div>
                </div>

                <p id="gps-status" class="text-[11px] text-[#526071] italic">
                    Klik "Deteksi Lokasi Saya" untuk mendeteksi koordinat otomatis dari perangkat, atau klik/geser marker pada peta.
                </p>

                {{-- Map Preview --}}
                <div class="mt-2">
                    <div class="flex justify-between items-center mb-1 text-[11px]">
                        <span class="font-semibold text-[#526071]">Preview Lokasi Peta (OpenStreetMap):</span>
                        <span id="map-coords-label" class="font-mono text-[#0F766E] font-bold"></span>
                    </div>
                    <div id="map-preview" class="w-full h-56 rounded-[12px] border border-[#CBD5E1] overflow-hidden shadow-inner z-0"></div>
                </div>
            </div>

            <div class="pt-4 border-t border-[#E2E8F0] flex justify-end gap-3">
                <a href="{{ route('pemilik.dashboard') }}" class="px-4 py-2.5 border border-[#CBD5E1] rounded-[10px] text-xs font-bold text-[#526071] hover:bg-[#F3F5F8] transition flex items-center min-h-[44px]">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-[#0F766E] hover:bg-[#0B5C56] text-white text-xs font-bold rounded-[10px] shadow-soft transition flex items-center min-h-[44px]">
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

        marker.on('dragend', function (e) {
            const coord = marker.getLatLng();
            latInput.value = coord.lat.toFixed(6);
            lngInput.value = coord.lng.toFixed(6);
            updateCoordsLabel(coord.lat, coord.lng);
        });

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
            status.className = 'text-[11px] text-[#991B1B] font-semibold';
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-[#0F766E] inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Mendeteksi...';
        status.textContent = 'Meminta izin lokasi GPS perangkat...';
        status.className = 'text-[11px] text-[#0F766E] italic font-semibold';

        navigator.geolocation.getCurrentPosition(
            (position) => {
                const lat = position.coords.latitude.toFixed(6);
                const lng = position.coords.longitude.toFixed(6);

                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;

                updateMapFromInputs();

                status.textContent = `Lokasi GPS berhasil dideteksi! (${lat}, ${lng})`;
                status.className = 'text-[11px] text-[#166534] font-bold';

                btn.disabled = false;
                btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg> Terdeteksi';
            },
            (error) => {
                btn.disabled = false;
                btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> Coba Lagi';

                let msg = 'Gagal mendeteksi lokasi. Pastikan izin lokasi browser diaktifkan, atau isi koordinat secara manual.';
                if (error.code === error.PERMISSION_DENIED) {
                    msg = 'Gagal mendeteksi lokasi. Pastikan izin lokasi browser diaktifkan, atau isi koordinat secara manual.';
                } else if (error.code === error.POSITION_UNAVAILABLE) {
                    msg = 'Informasi lokasi tidak tersedia pada perangkat. Silakan isi koordinat secara manual.';
                } else if (error.code === error.TIMEOUT) {
                    msg = 'Waktu permintaan lokasi habis. Silakan coba lagi atau isi koordinat secara manual.';
                }

                status.textContent = msg;
                status.className = 'text-[11px] text-[#991B1B] font-semibold';
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    }

    function previewImage(input, previewContainerId) {
        const container = document.getElementById(previewContainerId);
        if (!container) return;
        const img = container.querySelector('img');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                container.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            container.classList.add('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', initMapPreview);
</script>
@endpush
