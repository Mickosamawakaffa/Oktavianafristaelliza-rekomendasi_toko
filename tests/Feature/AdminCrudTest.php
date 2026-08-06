<?php

namespace Tests\Feature;

use App\Models\Kriteria;
use App\Models\Toko;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCrudTest extends TestCase
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
     * Test admin can access Toko CRUD list, create, edit, update, delete.
     */
    public function test_admin_can_perform_toko_crud(): void
    {
        // 1. List
        $resList = $this->actingAs($this->admin)->get(route('admin.toko.index'));
        $resList->assertStatus(200);

        // 2. Create
        $resCreate = $this->actingAs($this->admin)->post(route('admin.toko.store'), [
            'nama'            => 'Toko Baru Test Admin',
            'alamat'          => 'Jl. Mastrip Jember No 99',
            'deskripsi'       => 'Deskripsi toko baru admin',
            'media_sosial'    => '@tokotest_admin',
            'jam_operasional' => '08:00 - 20:00',
            'latitude'        => -8.1700,
            'longitude'       => 113.7100,
        ]);
        $resCreate->assertRedirect(route('admin.toko.index'));
        $this->assertDatabaseHas('toko', ['nama' => 'Toko Baru Test Admin']);

        // 3. Edit & Update
        $toko = Toko::where('nama', 'Toko Baru Test Admin')->first();
        $resUpdate = $this->actingAs($this->admin)->put(route('admin.toko.update', $toko->id), [
            'nama'   => 'Toko Updated Test Admin',
            'alamat' => 'Jl. Mastrip Jember No 99 Updated',
        ]);
        $resUpdate->assertRedirect(route('admin.toko.index'));
        $this->assertDatabaseHas('toko', ['nama' => 'Toko Updated Test Admin']);

        // 4. Delete
        $resDelete = $this->actingAs($this->admin)->delete(route('admin.toko.destroy', $toko->id));
        $resDelete->assertRedirect(route('admin.toko.index'));
        $this->assertDatabaseMissing('toko', ['id' => $toko->id]);
    }

    /**
     * Test admin can perform Kriteria CRUD.
     */
    public function test_admin_can_perform_kriteria_crud(): void
    {
        // 1. List
        $resList = $this->actingAs($this->admin)->get(route('admin.kriteria.index'));
        $resList->assertStatus(200);

        // 2. Create
        $resCreate = $this->actingAs($this->admin)->post(route('admin.kriteria.store'), [
            'nama_kriteria' => 'Kriteria Diskon',
            'jenis'         => 'benefit',
        ]);
        $resCreate->assertRedirect(route('admin.kriteria.index'));
        $this->assertDatabaseHas('kriteria', ['nama_kriteria' => 'Kriteria Diskon']);

        // 3. Update
        $kriteria = Kriteria::where('nama_kriteria', 'Kriteria Diskon')->first();
        $resUpdate = $this->actingAs($this->admin)->put(route('admin.kriteria.update', $kriteria->id), [
            'nama_kriteria' => 'Kriteria Promo Diskon',
            'jenis'         => 'benefit',
        ]);
        $resUpdate->assertRedirect(route('admin.kriteria.index'));
        $this->assertDatabaseHas('kriteria', ['nama_kriteria' => 'Kriteria Promo Diskon']);

        // 4. Delete
        $resDelete = $this->actingAs($this->admin)->delete(route('admin.kriteria.destroy', $kriteria->id));
        $resDelete->assertRedirect(route('admin.kriteria.index'));
        $this->assertDatabaseMissing('kriteria', ['id' => $kriteria->id]);
    }

    /**
     * Test admin can update Nilai Alternatif per toko.
     */
    public function test_admin_can_update_nilai_alternatif(): void
    {
        $toko = Toko::first();
        $kriteria = Kriteria::first();

        $resUpdate = $this->actingAs($this->admin)->put(route('admin.nilai-alternatif.update', $toko->id), [
            'nilai' => [
                $kriteria->id => 95,
            ],
        ]);

        $resUpdate->assertRedirect(route('admin.nilai-alternatif.index'));
        $this->assertDatabaseHas('nilai_alternatif', [
            'toko_id'     => $toko->id,
            'kriteria_id' => $kriteria->id,
            'nilai'       => 95,
        ]);
    }

    /**
     * Test form validation triggers on invalid input.
     */
    public function test_form_validation_rejects_empty_or_invalid_input(): void
    {
        // Empty store name
        $res = $this->actingAs($this->admin)->post(route('admin.toko.store'), [
            'nama' => '',
        ]);
        $res->assertSessionHasErrors(['nama', 'alamat']);

        // Invalid rating > 100
        $toko = Toko::first();
        $resRating = $this->actingAs($this->admin)->put(route('admin.nilai-alternatif.update', $toko->id), [
            'nilai' => [
                1 => 150, // exceeds max 100
            ],
        ]);
        $resRating->assertSessionHasErrors(['nilai.1']);
    }
}
