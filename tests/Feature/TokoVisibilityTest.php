<?php

namespace Tests\Feature;

use App\Models\Kriteria;
use App\Models\NilaiAlternatif;
use App\Models\Toko;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TokoVisibilityTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->admin = User::where('role', 'admin')->first();
    }

    /**
     * Test public page only shows stores with complete criteria values.
     * Incomplete stores are hidden from public page.
     */
    public function test_public_page_only_shows_complete_stores(): void
    {
        // 1. Create incomplete store (without criteria values)
        $tokoIncomplete = Toko::create([
            'nama' => 'Toko Incomplete Belum Ada Nilai',
            'alamat' => 'Jl. Incomplete No 1',
        ]);

        // 2. Access public Toko list
        $response = $this->get(route('toko.index'));
        $response->assertStatus(200);

        // Incomplete store should be hidden from public page
        $response->assertDontSee('Toko Incomplete Belum Ada Nilai');

        // Complete stores from seeder should be visible
        $completeStore = Toko::lengkap()->first();
        if ($completeStore) {
            $response->assertSee($completeStore->nama);
        }
    }

    /**
     * Test admin page shows ALL stores (both complete and incomplete) with status badges.
     */
    public function test_admin_page_shows_all_stores_with_status_badges(): void
    {
        // 1. Create incomplete store
        $tokoIncomplete = Toko::create([
            'nama' => 'Toko Incomplete Belum Ada Nilai',
            'alamat' => 'Jl. Incomplete No 1',
        ]);

        // 2. Access admin Toko list
        $response = $this->actingAs($this->admin)->get(route('admin.toko.index'));
        $response->assertStatus(200);

        // Admin page should see ALL stores including incomplete store
        $response->assertSee('Toko Incomplete Belum Ada Nilai');
        $response->assertSee('Belum Lengkap');
        $response->assertSee('Lengkap');
    }

    /**
     * Test Toko model helper method isKriteriaLengkap().
     */
    public function test_toko_model_is_kriteria_lengkap_helper(): void
    {
        $kriterias = Kriteria::all();
        $tokoBaru = Toko::create([
            'nama' => 'Toko Test Helper',
            'alamat' => 'Jl. Test Helper No 1',
        ]);

        $this->assertFalse($tokoBaru->isKriteriaLengkap());

        // Fill all criteria
        foreach ($kriterias as $k) {
            NilaiAlternatif::create([
                'toko_id' => $tokoBaru->id,
                'kriteria_id' => $k->id,
                'nilai' => 80,
            ]);
        }

        // Refresh model relations
        $tokoBaru->refresh();
        $this->assertTrue($tokoBaru->isKriteriaLengkap());
    }
}
