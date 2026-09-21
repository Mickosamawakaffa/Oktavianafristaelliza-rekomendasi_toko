<?php

namespace Tests\Feature;

use App\Models\Toko;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TokoVerificationTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $pemilik;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        $this->admin = User::where('role', 'admin')->first();
        $this->pemilik = User::where('role', 'pemilik_toko')->first();
    }

    /**
     * Test Toko yang didaftarkan pemilik toko otomatis berstatus pending.
     */
    public function test_store_created_by_pemilik_is_pending(): void
    {
        // Delete any existing stores for clean state
        Toko::where('pemilik_id', $this->pemilik->id)->delete();

        $response = $this->actingAs($this->pemilik)->post(route('pemilik.toko.store'), [
            'nama'   => 'Toko Thrift Baru',
            'alamat' => 'Jl. Jawa No 50',
        ]);

        $response->assertRedirect(route('pemilik.dashboard'));
        $this->assertDatabaseHas('toko', [
            'nama'   => 'Toko Thrift Baru',
            'status' => 'pending',
        ]);
    }

    /**
     * Test Toko yang dibuat oleh admin otomatis berstatus approved.
     */
    public function test_store_created_by_admin_is_approved(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.toko.store'), [
            'nama'   => 'Toko Thrift Admin',
            'alamat' => 'Jl. Kalimantan No 99',
        ]);

        $response->assertRedirect(route('admin.toko.index'));
        $this->assertDatabaseHas('toko', [
            'nama'   => 'Toko Thrift Admin',
            'status' => 'approved',
        ]);
    }

    /**
     * Test Admin dapat menyetujui (approve) toko yang pending.
     */
    public function test_admin_can_approve_pending_store(): void
    {
        $toko = Toko::create([
            'nama'       => 'Toko Pending Verifikasi',
            'alamat'     => 'Jl. Mastrip Jember',
            'pemilik_id' => $this->pemilik->id,
            'status'     => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.toko.approve', $toko->id));
        $response->assertRedirect();

        $this->assertDatabaseHas('toko', [
            'id'     => $toko->id,
            'status' => 'approved',
            'catatan_verifikasi' => null,
        ]);
    }

    /**
     * Test Admin dapat menolak (reject) toko dengan catatan/alasan.
     */
    public function test_admin_can_reject_store_with_reason(): void
    {
        $toko = Toko::create([
            'nama'       => 'Toko Fiktif',
            'alamat'     => 'Jl. Tidak Jelas',
            'pemilik_id' => $this->pemilik->id,
            'status'     => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.toko.reject', $toko->id), [
            'catatan_verifikasi' => 'Foto plang toko tidak jelas dan koordinat GPS tidak valid.',
        ]);
        $response->assertRedirect();

        $this->assertDatabaseHas('toko', [
            'id'     => $toko->id,
            'status' => 'rejected',
            'catatan_verifikasi' => 'Foto plang toko tidak jelas dan koordinat GPS tidak valid.',
        ]);
    }

    /**
     * Test Toko yang statusnya rejected akan kembali pending saat diedit dan diajukan ulang oleh pemilik.
     */
    public function test_rejected_store_becomes_pending_when_resubmitted_by_owner(): void
    {
        $toko = Toko::create([
            'nama'               => 'Toko Perlu Revisi',
            'alamat'             => 'Jl. Jawa No 1',
            'pemilik_id'         => $this->pemilik->id,
            'status'             => 'rejected',
            'catatan_verifikasi' => 'Foto belum diunggah.',
        ]);

        $response = $this->actingAs($this->pemilik)->put(route('pemilik.toko.update', $toko->id), [
            'nama'   => 'Toko Perlu Revisi (Revisi)',
            'alamat' => 'Jl. Jawa No 1 Updated',
        ]);

        $response->assertRedirect(route('pemilik.dashboard'));
        $this->assertDatabaseHas('toko', [
            'id'                 => $toko->id,
            'status'             => 'pending',
            'catatan_verifikasi' => null,
        ]);
    }

    /**
     * Test Anti-Spam: Pemilik tidak dapat membuat toko baru jika masih ada toko miliknya yang berstatus pending.
     */
    public function test_anti_spam_prevents_multiple_pending_stores(): void
    {
        Toko::create([
            'nama'       => 'Toko Pending 1',
            'alamat'     => 'Jl. Danau Toba',
            'pemilik_id' => $this->pemilik->id,
            'status'     => 'pending',
        ]);

        // Coba buat toko ke-2 saat toko ke-1 masih pending
        $response = $this->actingAs($this->pemilik)->post(route('pemilik.toko.store'), [
            'nama'   => 'Toko Pending 2 (Spam)',
            'alamat' => 'Jl. Danau Toba 2',
        ]);

        $response->assertRedirect(route('pemilik.dashboard'));
        $this->assertDatabaseMissing('toko', [
            'nama' => 'Toko Pending 2 (Spam)',
        ]);
    }
}
