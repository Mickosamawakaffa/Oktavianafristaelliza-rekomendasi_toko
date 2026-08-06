<?php

namespace Tests\Feature;

use App\Models\Kriteria;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserPreferensiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * Test user logged in can access preferensi page.
     */
    public function test_user_can_access_preferensi_page(): void
    {
        $user = User::where('role', 'user')->first();

        $response = $this->actingAs($user)->get(route('user.preferensi'));

        $response->assertStatus(200);
        $response->assertSee('Preferensi Kriteria Kamu');
    }

    /**
     * Test user can submit preferensi order and redirect to hasil page with store rankings.
     */
    public function test_user_can_submit_preferensi_and_see_rankings(): void
    {
        $user = User::where('role', 'user')->first();
        $kriteriaIds = Kriteria::pluck('id')->toArray();

        // Submit form
        $response = $this->actingAs($user)->post(route('user.preferensi.simpan'), [
            'urutan_kriteria' => $kriteriaIds,
        ]);

        $response->assertRedirect(route('user.hasil'));

        // Visit hasil page
        $hasilResponse = $this->actingAs($user)->get(route('user.hasil'));
        $hasilResponse->assertStatus(200);
        $hasilResponse->assertSee('Hasil Rekomendasi Toko');
        $hasilResponse->assertSee('Ranking Toko');
    }
}
