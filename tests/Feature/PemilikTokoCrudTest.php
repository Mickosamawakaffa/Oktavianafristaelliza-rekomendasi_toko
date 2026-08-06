<?php

namespace Tests\Feature;

use App\Models\Toko;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PemilikTokoCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $pemilikA;
    private User $pemilikB;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        // Pemilik A (from seeder)
        $this->pemilikA = User::where('email', 'pemilik@toko.com')->first();

        // Create Pemilik B
        $this->pemilikB = User::create([
            'name'     => 'Pemilik Toko B',
            'email'    => 'pemilikB@toko.com',
            'password' => bcrypt('password'),
            'role'     => 'pemilik_toko',
        ]);
    }

    /**
     * Test Pemilik Toko A can create a store for themselves.
     */
    public function test_pemilik_can_create_own_store(): void
    {
        $response = $this->actingAs($this->pemilikA)->post(route('pemilik.toko.store'), [
            'nama'            => 'Toko Thrift Milik Pemilik A',
            'alamat'          => 'Jl. Riau No 10 Jember',
            'deskripsi'       => 'Deskripsi toko milik A',
            'media_sosial'    => '@toko_pemilik_a',
            'jam_operasional' => '09:00 - 21:00',
            'latitude'        => -8.1670,
            'longitude'       => 113.7100,
        ]);

        $response->assertRedirect(route('pemilik.dashboard'));
        $this->assertDatabaseHas('toko', [
            'nama'       => 'Toko Thrift Milik Pemilik A',
            'pemilik_id' => $this->pemilikA->id,
        ]);
    }

    /**
     * Test Pemilik Toko A can edit and update their own store.
     */
    public function test_pemilik_can_edit_own_store(): void
    {
        $tokoA = Toko::where('pemilik_id', $this->pemilikA->id)->first();

        // Edit page
        $resEdit = $this->actingAs($this->pemilikA)->get(route('pemilik.toko.edit', $tokoA->id));
        $resEdit->assertStatus(200);

        // Update
        $resUpdate = $this->actingAs($this->pemilikA)->put(route('pemilik.toko.update', $tokoA->id), [
            'nama'   => 'Nama Toko A Updated',
            'alamat' => 'Alamat Toko A Updated',
        ]);

        $resUpdate->assertRedirect(route('pemilik.dashboard'));
        $this->assertDatabaseHas('toko', [
            'id'   => $tokoA->id,
            'nama' => 'Nama Toko A Updated',
        ]);
    }

    /**
     * Test Pemilik Toko B CANNOT edit or delete Pemilik Toko A's store (403 Forbidden).
     */
    public function test_pemilik_b_cannot_edit_or_delete_pemilik_a_store(): void
    {
        $tokoA = Toko::where('pemilik_id', $this->pemilikA->id)->first();

        // Pemilik B tries to edit A's store
        $resEdit = $this->actingAs($this->pemilikB)->get(route('pemilik.toko.edit', $tokoA->id));
        $resEdit->assertStatus(403);

        // Pemilik B tries to update A's store
        $resUpdate = $this->actingAs($this->pemilikB)->put(route('pemilik.toko.update', $tokoA->id), [
            'nama'   => 'Hacked By B',
            'alamat' => 'Hacked Address',
        ]);
        $resUpdate->assertStatus(403);

        // Pemilik B tries to delete A's store
        $resDelete = $this->actingAs($this->pemilikB)->delete(route('pemilik.toko.destroy', $tokoA->id));
        $resDelete->assertStatus(403);

        // Store A remains intact in DB
        $this->assertDatabaseHas('toko', ['id' => $tokoA->id, 'nama' => $tokoA->nama]);
    }

    /**
     * Test Pemilik Toko A can delete their own store.
     */
    public function test_pemilik_can_delete_own_store(): void
    {
        $tokoA = Toko::where('pemilik_id', $this->pemilikA->id)->first();

        $resDelete = $this->actingAs($this->pemilikA)->delete(route('pemilik.toko.destroy', $tokoA->id));
        $resDelete->assertRedirect(route('pemilik.dashboard'));

        $this->assertDatabaseMissing('toko', ['id' => $tokoA->id]);
    }
}
