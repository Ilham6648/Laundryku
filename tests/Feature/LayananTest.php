<?php

namespace Tests\Feature;

use App\Models\Layanan;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LayananTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_layanans(): void
    {
        $this->get(route('layanans.index'))->assertRedirect(route('login'));
    }

    public function test_kasir_cannot_access_layanan_crud(): void
    {
        $kasir = User::factory()->kasir()->create();
        $layanan = Layanan::factory()->create();

        $this->actingAs($kasir)->get(route('layanans.index'))->assertForbidden();
        $this->actingAs($kasir)->get(route('layanans.create'))->assertForbidden();
        $this->actingAs($kasir)->post(route('layanans.store'), [
            'nama_layanan' => 'Kilat',
            'harga_per_kg' => 15000,
            'estimasi_waktu' => '1 hari',
        ])->assertForbidden();
        $this->actingAs($kasir)->get(route('layanans.edit', $layanan))->assertForbidden();
        $this->actingAs($kasir)->put(route('layanans.update', $layanan), [
            'nama_layanan' => 'Diubah',
            'harga_per_kg' => 10000,
            'estimasi_waktu' => '2 hari',
        ])->assertForbidden();
        $this->actingAs($kasir)->delete(route('layanans.destroy', $layanan))->assertForbidden();
    }

    public function test_admin_can_create_update_and_delete_unused_layanan(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get(route('layanans.index'))->assertOk();

        $storeResponse = $this->actingAs($admin)->post(route('layanans.store'), [
            'nama_layanan' => 'Cuci Bed Cover',
            'harga_per_kg' => 18000,
            'estimasi_waktu' => '2 hari',
        ]);

        $storeResponse->assertRedirect(route('layanans.index'));
        $this->assertDatabaseHas('layanans', [
            'nama_layanan' => 'Cuci Bed Cover',
            'harga_per_kg' => 18000,
            'estimasi_waktu' => '2 hari',
        ]);

        $layanan = Layanan::query()->where('nama_layanan', 'Cuci Bed Cover')->firstOrFail();

        $updateResponse = $this->actingAs($admin)->put(route('layanans.update', $layanan), [
            'nama_layanan' => 'Cuci Bed Cover Premium',
            'harga_per_kg' => 20000,
            'estimasi_waktu' => '1-2 hari',
        ]);

        $updateResponse->assertRedirect(route('layanans.index'));
        $this->assertDatabaseHas('layanans', [
            'id' => $layanan->id,
            'nama_layanan' => 'Cuci Bed Cover Premium',
            'harga_per_kg' => 20000,
            'estimasi_waktu' => '1-2 hari',
        ]);

        $deleteResponse = $this->actingAs($admin)->delete(route('layanans.destroy', $layanan));
        $deleteResponse->assertRedirect(route('layanans.index'));
        $this->assertDatabaseMissing('layanans', ['id' => $layanan->id]);
    }

    public function test_admin_cannot_delete_layanan_with_existing_transaksis(): void
    {
        $admin = User::factory()->admin()->create();
        $layanan = Layanan::factory()->create(['harga_per_kg' => 7000]);
        Transaksi::factory()->create(['layanan_id' => $layanan->id]);

        $response = $this->actingAs($admin)->delete(route('layanans.destroy', $layanan));

        $response->assertRedirect(route('layanans.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('layanans', ['id' => $layanan->id]);
    }
}
