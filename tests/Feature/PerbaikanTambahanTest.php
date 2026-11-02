<?php

namespace Tests\Feature;

use App\Models\Kriteria;
use App\Models\NilaiAlternatif;
use App\Models\Toko;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Regresi untuk perbaikan: zona waktu, validasi GPS, urutan kriteria ganda,
 * validasi ID kriteria, jumlah ulasan 0, status toko, dan toko tanpa koordinat.
 */
class PerbaikanTambahanTest extends TestCase
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

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
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

    // ─── Zona waktu ──────────────────────────────────────────────────────────

    public function test_zona_waktu_aplikasi_adalah_wib_dan_status_buka_mengikutinya(): void
    {
        $this->assertSame('Asia/Jakarta', config('app.timezone'));

        // 03:00 UTC = 10:00 WIB -> toko 09:00-21:00 sedang buka
        Carbon::setTestNow(Carbon::parse('2026-01-01 03:00:00', 'UTC'));
        $toko = new Toko(['jam_operasional' => '09:00 - 21:00']);
        $this->assertTrue($toko->is_buka);

        // 15:00 UTC = 22:00 WIB -> sudah tutup
        Carbon::setTestNow(Carbon::parse('2026-01-01 15:00:00', 'UTC'));
        $this->assertFalse($toko->is_buka);
    }

    // ─── Validasi koordinat GPS di halaman hasil ─────────────────────────────

    public function test_hasil_menolak_koordinat_non_numerik_tanpa_error_500(): void
    {
        $this->actingAs($this->user)->post(route('user.preferensi.simpan'), [
            'urutan_kriteria' => Kriteria::pluck('id')->toArray(),
        ]);

        $this->actingAs($this->user)
            ->get(route('user.hasil', ['user_lat' => 'abc', 'user_lng' => '113.7']))
            ->assertSessionHasErrors('user_lat');
    }

    // ─── kriteria_order ganda di beranda ─────────────────────────────────────

    public function test_kriteria_order_ganda_tetap_menghasilkan_bobot_total_satu(): void
    {
        $ids = Kriteria::orderBy('id')->pluck('id')->toArray();
        $order = $ids[0] . ',' . $ids[0] . ',' . implode(',', array_slice($ids, 1));

        $response = $this->get(route('toko.index', ['kriteria_order' => $order]))->assertOk();

        $weights = $response->viewData('weights');
        $this->assertCount(count($ids), $weights);
        $this->assertEqualsWithDelta(1.0, array_sum($weights), 1e-9);
    }

    // ─── Validasi ID kriteria pada nilai alternatif ──────────────────────────

    public function test_id_kriteria_tidak_valid_ditolak_di_admin_nilai_alternatif(): void
    {
        $toko = $this->buatToko();

        $this->actingAs($this->admin)
            ->put(route('admin.nilai-alternatif.update', $toko->id), ['nilai' => [999999 => 50]])
            ->assertSessionHasErrors('nilai');

        $this->assertDatabaseMissing('nilai_alternatif', ['kriteria_id' => 999999]);
    }

    public function test_id_kriteria_tidak_valid_ditolak_di_halaman_detail_toko(): void
    {
        $toko = $this->buatToko();

        $this->actingAs($this->admin)
            ->post(route('toko.nilai-kriteria.update', $toko->id), ['nilai' => [999999 => 50]])
            ->assertSessionHasErrors('nilai');

        $this->assertDatabaseMissing('nilai_alternatif', ['kriteria_id' => 999999]);
    }

    public function test_jumlah_ulasan_nol_tersimpan_sebagai_nol(): void
    {
        $toko = $this->buatToko();
        $nilai = Kriteria::pluck('id')->mapWithKeys(fn ($id) => [$id => 50])->toArray();

        $this->actingAs($this->admin)
            ->put(route('admin.nilai-alternatif.update', $toko->id), [
                'nilai'         => $nilai,
                'rating_ulasan' => 4.5,
                'jumlah_ulasan' => 0,
            ])
            ->assertSessionHasNoErrors();

        $toko->refresh();
        $this->assertSame(0, $toko->jumlah_ulasan);
        $this->assertEquals(4.5, (float) $toko->rating_ulasan);
    }

    // ─── Status toko oleh admin ──────────────────────────────────────────────

    public function test_admin_update_dengan_status_kosong_tidak_menghapus_status(): void
    {
        $toko = $this->buatToko(['status' => 'approved']);

        $this->actingAs($this->admin)
            ->put(route('admin.toko.update', $toko->id), [
                'nama'   => 'Toko Uji Ubah',
                'alamat' => 'Jl. Uji No 1',
                'status' => '',
            ])
            ->assertSessionHasNoErrors();

        $toko->refresh();
        $this->assertSame('Toko Uji Ubah', $toko->nama);
        $this->assertSame('approved', $toko->status);
    }

    // ─── Pencarian: wildcard LIKE dianggap teks biasa ────────────────────────

    public function test_pencarian_toko_tidak_menganggap_wildcard_sebagai_pola(): void
    {
        $this->buatToko(['nama' => 'Toko Alpha', 'alamat' => 'Jl. A']);
        $this->buatToko(['nama' => 'Toko 100% Murah', 'alamat' => 'Jl. B']);
        $this->buatToko(['nama' => 'Toko_Garis', 'alamat' => 'Jl. C']);

        $cari = fn (string $kata) => Toko::cari($kata)->pluck('nama')->all();

        // '%' dan '_' sendirian tidak boleh cocok dengan semua toko
        $this->assertSame(['Toko 100% Murah'], $cari('%'));
        $this->assertSame(['Toko_Garis'], $cari('_'));
        $this->assertSame(['Toko 100% Murah'], $cari('100%'));
        $this->assertContains('Toko Alpha', $cari('alpha'));
        $this->assertSame([], $cari('!'));
    }

    public function test_halaman_admin_dan_beranda_menerima_pencarian_dengan_karakter_khusus(): void
    {
        $this->actingAs($this->admin)->get(route('admin.toko.index', ['search' => '100%_!']))->assertOk();
        $this->get(route('toko.index', ['search' => '100%_!']))->assertOk();
    }

    // ─── Toko tanpa koordinat ────────────────────────────────────────────────

    public function test_hasil_menangani_toko_tanpa_koordinat_tanpa_lokasi_palsu(): void
    {
        $tanpaKoordinat = $this->buatToko(['nama' => 'Toko Tanpa Koordinat', 'latitude' => null, 'longitude' => null]);
        foreach (Kriteria::all() as $k) {
            NilaiAlternatif::create(['toko_id' => $tanpaKoordinat->id, 'kriteria_id' => $k->id, 'nilai' => 50]);
        }

        $this->actingAs($this->user)->post(route('user.preferensi.simpan'), [
            'urutan_kriteria' => Kriteria::pluck('id')->toArray(),
            'user_lat'        => -8.17,
            'user_lng'        => 113.70,
        ]);

        $response = $this->actingAs($this->user)->get(route('user.hasil'))
            ->assertOk()
            ->assertSee('Toko Tanpa Koordinat');

        $item = collect($response->viewData('rankings'))
            ->first(fn ($r) => $r['toko']->id === $tanpaKoordinat->id);

        $this->assertNotNull($item);
        $this->assertNull($item['jarak_km']);
    }
}
