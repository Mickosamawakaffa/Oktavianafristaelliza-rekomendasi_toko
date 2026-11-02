<?php

namespace Tests\Feature;

use App\Models\Kriteria;
use App\Models\Toko;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Menguji perbaikan keamanan dan konsistensi perhitungan menjelang sidang.
 */
class KeamananDanKonsistensiTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $pemilik;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        $this->admin = User::where('role', 'admin')->first();
        $this->pemilik = User::where('role', 'pemilik_toko')->first();
        $this->user = User::where('role', 'user')->first();
    }

    private function buatToko(array $override = []): Toko
    {
        return Toko::create(array_merge([
            'nama'       => 'Toko Uji',
            'alamat'     => 'Jl. Uji No 1',
            'latitude'   => -8.17,
            'longitude'  => 113.70,
            'pemilik_id' => $this->pemilik->id,
            'status'     => 'approved',
        ], $override));
    }

    // ─── Bayesian Average ────────────────────────────────────────────────────

    public function test_rating_tanpa_jumlah_ulasan_tidak_bisa_mendongkrak_skor(): void
    {
        $toko = new Toko(['rating_ulasan' => 5, 'jumlah_ulasan' => null]);
        $this->assertSame(60.0, $toko->skor_ulasan);

        $toko = new Toko(['rating_ulasan' => 5, 'jumlah_ulasan' => 0]);
        $this->assertSame(60.0, $toko->skor_ulasan);
    }

    public function test_skor_ulasan_bayesian_untuk_ulasan_banyak_mendekati_rating(): void
    {
        $toko = new Toko(['rating_ulasan' => 4.6, 'jumlah_ulasan' => 120]);
        $this->assertSame(89.5, $toko->skor_ulasan);
    }

    // ─── Akses toko yang belum disetujui ─────────────────────────────────────

    public function test_toko_pending_tidak_bisa_dibuka_tamu_atau_user_lain(): void
    {
        $toko = $this->buatToko(['status' => 'pending']);

        $this->get(route('toko.show', $toko->id))->assertNotFound();
        $this->actingAs($this->user)->get(route('toko.show', $toko->id))->assertNotFound();
    }

    public function test_toko_pending_bisa_dibuka_admin_dan_pemiliknya(): void
    {
        $toko = $this->buatToko(['status' => 'pending']);

        $this->actingAs($this->admin)->get(route('toko.show', $toko->id))->assertOk();
        $this->actingAs($this->pemilik)->get(route('toko.show', $toko->id))->assertOk();
    }

    public function test_badge_terverifikasi_hanya_untuk_toko_approved(): void
    {
        $approved = $this->buatToko(['status' => 'approved']);
        $this->get(route('toko.show', $approved->id))->assertSee('Terverifikasi Admin');

        $pending = $this->buatToko(['nama' => 'Toko Pending', 'status' => 'pending']);
        $this->actingAs($this->admin)->get(route('toko.show', $pending->id))->assertDontSee('Terverifikasi Admin');
    }

    // ─── Verifikasi ulang saat pemilik mengubah data penting ────────────────

    public function test_pemilik_ubah_nama_toko_approved_kembali_pending(): void
    {
        $toko = $this->buatToko();

        $this->actingAs($this->pemilik)->put(route('pemilik.toko.update', $toko->id), [
            'nama'      => 'Nama Baru Toko',
            'alamat'    => $toko->alamat,
            'latitude'  => -8.17,
            'longitude' => 113.70,
        ])->assertRedirect(route('pemilik.dashboard'));

        $this->assertSame('pending', $toko->fresh()->status);
    }

    public function test_pemilik_ubah_koordinat_toko_approved_kembali_pending(): void
    {
        $toko = $this->buatToko();

        $this->actingAs($this->pemilik)->put(route('pemilik.toko.update', $toko->id), [
            'nama'      => $toko->nama,
            'alamat'    => $toko->alamat,
            'latitude'  => -8.20,
            'longitude' => 113.70,
        ]);

        $this->assertSame('pending', $toko->fresh()->status);
    }

    public function test_pemilik_ubah_deskripsi_saja_tetap_approved(): void
    {
        $toko = $this->buatToko();

        $this->actingAs($this->pemilik)->put(route('pemilik.toko.update', $toko->id), [
            'nama'            => $toko->nama,
            'alamat'          => $toko->alamat,
            'latitude'        => -8.17,
            'longitude'       => 113.70,
            'deskripsi'       => 'Deskripsi baru',
            'jam_operasional' => '09:00 - 21:00',
        ]);

        $fresh = $toko->fresh();
        $this->assertSame('approved', $fresh->status);
        $this->assertSame('Deskripsi baru', $fresh->deskripsi);
    }

    // ─── Kriteria inti dikunci ───────────────────────────────────────────────

    public function test_kriteria_inti_tidak_bisa_dihapus(): void
    {
        $lokasi = Kriteria::where('nama_kriteria', 'Lokasi')->firstOrFail();

        $this->actingAs($this->admin)
            ->delete(route('admin.kriteria.destroy', $lokasi->id))
            ->assertRedirect(route('admin.kriteria.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('kriteria', ['id' => $lokasi->id]);
    }

    public function test_kriteria_inti_tidak_bisa_diubah_nama_atau_jenisnya(): void
    {
        $ulasan = Kriteria::where('nama_kriteria', 'Ulasan')->firstOrFail();

        $this->actingAs($this->admin)
            ->put(route('admin.kriteria.update', $ulasan->id), [
                'nama_kriteria' => 'Ulasan Pelanggan',
                'jenis'         => 'cost',
            ])
            ->assertSessionHas('error');

        $this->assertDatabaseHas('kriteria', ['id' => $ulasan->id, 'nama_kriteria' => 'Ulasan', 'jenis' => 'benefit']);
    }

    public function test_kriteria_biasa_tetap_bisa_dihapus(): void
    {
        $harga = Kriteria::where('nama_kriteria', 'Harga')->firstOrFail();

        $this->actingAs($this->admin)
            ->delete(route('admin.kriteria.destroy', $harga->id))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('kriteria', ['id' => $harga->id]);
    }

    // ─── Lupa password ───────────────────────────────────────────────────────

    public function test_lupa_password_tidak_membocorkan_email_terdaftar(): void
    {
        $pesan = 'Jika email tersebut terdaftar, link reset password telah dikirim.';

        $this->post(route('password.email'), ['email' => $this->user->email])
            ->assertSessionHas('status', $pesan);

        $this->post(route('password.email'), ['email' => 'tidak.ada@contoh.com'])
            ->assertSessionHas('status', $pesan)
            ->assertSessionHasNoErrors();
    }

    // ─── Validasi preferensi ─────────────────────────────────────────────────

    public function test_preferensi_dengan_kriteria_duplikat_ditolak(): void
    {
        $id = Kriteria::first()->id;

        $this->actingAs($this->user)
            ->post(route('user.preferensi.simpan'), ['urutan_kriteria' => [$id, $id]])
            ->assertSessionHasErrors('urutan_kriteria.1');

        $this->assertDatabaseCount('preferensi_user', 0);
    }

    // ─── Keterangan lokasi default di halaman hasil ──────────────────────────

    public function test_halaman_hasil_memberi_tahu_jika_memakai_lokasi_default(): void
    {
        $this->actingAs($this->user)->post(route('user.preferensi.simpan'), [
            'urutan_kriteria' => Kriteria::pluck('id')->toArray(),
        ]);

        $this->actingAs($this->user)->get(route('user.hasil'))
            ->assertOk()
            ->assertSee('lokasi default')
            ->assertSee('garis lurus');
    }

    public function test_halaman_hasil_tanpa_peringatan_jika_gps_sudah_diatur(): void
    {
        $this->actingAs($this->user)->post(route('user.preferensi.simpan'), [
            'urutan_kriteria' => Kriteria::pluck('id')->toArray(),
            'user_lat'        => -8.17,
            'user_lng'        => 113.70,
        ]);

        $this->actingAs($this->user)->get(route('user.hasil'))
            ->assertOk()
            ->assertDontSee('Menggunakan lokasi default');
    }

    // ─── Beranda konsisten dengan GPS ────────────────────────────────────────

    public function test_beranda_memberi_label_skor_umum_tanpa_gps(): void
    {
        $this->get(route('toko.index'))
            ->assertOk()
            ->assertSee('Skor ROC-SMART umum')
            ->assertDontSee('sesuai GPS Anda');
    }

    public function test_beranda_memakai_label_gps_dan_skor_berubah_setelah_gps_diatur(): void
    {
        $ambilSkor = function (string $html): array {
            preg_match_all('/text-\[28px\][^>]*>([0-9.,]+)%/', $html, $m);

            return $m[1];
        };

        $tanpaGps = $ambilSkor($this->get(route('toko.index'))->getContent());

        $this->actingAs($this->user)->post(route('user.preferensi.simpan'), [
            'urutan_kriteria' => Kriteria::pluck('id')->toArray(),
            'user_lat'        => -8.1650,
            'user_lng'        => 113.7200,
        ]);

        $response = $this->actingAs($this->user)->get(route('toko.index'));
        $response->assertOk()->assertSee('sesuai GPS Anda');

        $this->assertNotEmpty($tanpaGps);
        $this->assertNotSame($tanpaGps, $ambilSkor($response->getContent()));
    }

    // ─── Pembatasan laju ─────────────────────────────────────────────────────

    public function test_registrasi_dibatasi_laju_permintaannya(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $this->post(route('register.post'), []);
        }

        $this->post(route('register.post'), [])->assertStatus(429);
    }

    // ─── Tabel transparansi memuat rincian u x w ─────────────────────────────

    public function test_tabel_transparansi_memuat_rincian_bobot_dan_total_satu(): void
    {
        $this->actingAs($this->user)->post(route('user.preferensi.simpan'), [
            'urutan_kriteria' => Kriteria::pluck('id')->toArray(),
        ]);

        $this->actingAs($this->user)->get(route('user.hasil'))
            ->assertOk()
            ->assertSee('Σ Bobot ROC')
            ->assertSee('= 1,0000');
    }

    // ─── Pencegahan XSS lewat nama toko di atribut JavaScript ────────────────

    public function test_nama_toko_berbahaya_tidak_menembus_atribut_onclick(): void
    {
        $payload = "x');alert(document.cookie);//";
        $this->buatToko(['nama' => $payload, 'status' => 'pending']);

        $html = $this->actingAs($this->admin)
            ->get(route('admin.toko.index'))
            ->assertOk()
            ->getContent();

        // Hanya atribut onclick yang diperiksa: di sana nama harus ter-encode sebagai literal JS aman.
        // (Di teks HTML biasa nama ter-escape oleh Blade, sehingga tidak dieksekusi.)
        preg_match_all('/onclick="(openRejectModal\([^"]*)"/', $html, $semua);
        $milikPayload = array_values(array_filter($semua[1], fn ($o) => str_contains($o, 'alert')));
        $this->assertCount(1, $milikPayload);

        $onclick = $milikPayload[0];
        $this->assertStringContainsString('\u0027', $onclick);
        $this->assertStringNotContainsString("');alert", $onclick);
        $this->assertStringNotContainsString('&#039;', $onclick);
    }
}
