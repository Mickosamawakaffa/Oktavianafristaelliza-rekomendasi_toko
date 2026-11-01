<?php

namespace Tests\Feature;

use App\Models\Toko;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Menguji perilaku tampilan yang memengaruhi kemudahan penggunaan:
 * notifikasi global, navigasi sesuai peran, halaman error, dan tampilan kartu toko.
 */
class UiFeedbackTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_flash_message_is_shown_on_home_page_after_login(): void
    {
        $response = $this->followingRedirects()->post(route('login.post'), [
            'email'    => 'user@gmail.com',
            'password' => 'password',
        ]);

        $response->assertOk();
        $response->assertSee('Selamat datang kembali');
    }

    public function test_flash_message_is_shown_after_logout(): void
    {
        $user = User::where('role', 'user')->first();

        $response = $this->actingAs($user)->followingRedirects()->post(route('logout'));

        $response->assertOk();
        $response->assertSee('Anda telah berhasil keluar');
    }

    public function test_navbar_links_follow_user_role(): void
    {
        $this->get(route('toko.index'))
            ->assertSee('Masuk')
            ->assertDontSee('Preferensi Saya');

        $user = User::where('role', 'user')->first();
        $this->actingAs($user)->get(route('toko.index'))
            ->assertSee('Preferensi Saya')
            ->assertSee('Hasil Rekomendasi')
            ->assertDontSee('Dashboard Admin');

        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin)->get(route('toko.index'))
            ->assertSee('Dashboard Admin')
            ->assertDontSee('Preferensi Saya');

        $pemilik = User::where('role', 'pemilik_toko')->first();
        $this->actingAs($pemilik)->get(route('toko.index'))
            ->assertSee('Dashboard Pemilik');
    }

    public function test_custom_404_page_is_rendered(): void
    {
        $this->get('/halaman-yang-tidak-ada')
            ->assertStatus(404)
            ->assertSee('Halaman Tidak Ditemukan')
            ->assertSee('Kembali ke Beranda');
    }

    public function test_custom_403_page_shows_the_reason(): void
    {
        $user = User::where('role', 'user')->first();

        $this->actingAs($user)->get(route('admin.dashboard'))
            ->assertStatus(403)
            ->assertSee('Akses Ditolak')
            ->assertSee('tidak memiliki hak akses');
    }

    public function test_store_card_does_not_show_made_up_data_when_fields_are_empty(): void
    {
        // Kosongkan semua toko agar tidak ada data seeder yang kebetulan memuat teks yang diperiksa.
        Toko::query()->update([
            'rating_ulasan'   => null,
            'jumlah_ulasan'   => null,
            'jam_operasional' => null,
            'deskripsi'       => null,
            'gambar'          => null,
        ]);

        $response = $this->get(route('toko.index'));

        $response->assertOk();
        $response->assertSee('Belum ada ulasan');
        // Teks karangan lama yang dulu ditampilkan saat data kosong tidak boleh muncul lagi.
        $response->assertDontSee('Toko thrift fashion secondhand berkualitas di Jember dengan koleksi pakaian pilihan.');
        $response->assertDontSee('10:00 - 21:00');
        $response->assertDontSee('Jember, Jawa Timur');
    }
}
