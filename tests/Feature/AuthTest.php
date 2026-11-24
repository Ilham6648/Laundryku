<?php

namespace Tests\Feature;

use App\Models\Layanan;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered_without_seeder_demo_box(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Masuk ke Akun Anda');
        $response->assertSee('Daftar akun baru');
        $response->assertDontSee('Akun Pengujian (Seeder)');
        $response->assertDontSee('fillDemo');
    }

    public function test_register_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Registrasi Akun Baru');
        $response->assertSee('Pilih Role');
    }

    public function test_user_can_register_as_admin(): void
    {
        $response = $this->post('/register', [
            'name' => 'Admin Baru',
            'email' => 'adminbaru@laundry.com',
            'role' => User::ROLE_ADMIN,
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'name' => 'Admin Baru',
            'email' => 'adminbaru@laundry.com',
            'role' => User::ROLE_ADMIN,
        ]);
    }

    public function test_user_can_register_as_kasir(): void
    {
        $response = $this->post('/register', [
            'name' => 'Kasir Baru',
            'email' => 'kasirbaru@laundry.com',
            'role' => User::ROLE_KASIR,
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'name' => 'Kasir Baru',
            'email' => 'kasirbaru@laundry.com',
            'role' => User::ROLE_KASIR,
        ]);

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertDontSee('Data Layanan');

        $this->get(route('layanans.index'))->assertForbidden();
    }

    public function test_user_cannot_register_with_invalid_role(): void
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'User Invalid',
            'email' => 'invalid@laundry.com',
            'role' => 'superadmin',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('role');
        $this->assertGuest();
    }

    public function test_admin_can_login_and_see_layanan_menu(): void
    {
        $this->seed(DatabaseSeeder::class);

        $response = $this->post('/login', [
            'email' => 'admin@laundry.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();

        $dashboard = $this->get(route('dashboard'));
        $dashboard->assertOk();
        $dashboard->assertSee('Data Layanan');
        $dashboard->assertSee('Rekap Transaksi');
    }

    public function test_kasir_can_login_and_does_not_see_layanan_menu(): void
    {
        $this->seed(DatabaseSeeder::class);

        $response = $this->post('/login', [
            'email' => 'kasir@laundry.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();

        $dashboard = $this->get(route('dashboard'));
        $dashboard->assertOk();
        $dashboard->assertDontSee('Data Layanan');
        $dashboard->assertSee('Rekap Transaksi');
    }

    public function test_user_cannot_login_with_invalid_password(): void
    {
        $user = User::factory()->kasir()->create();

        $response = $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->kasir()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_seeder_creates_expected_users_and_layanans(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseHas('users', [
            'email' => 'admin@laundry.com',
            'role' => User::ROLE_ADMIN,
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'kasir@laundry.com',
            'role' => User::ROLE_KASIR,
        ]);

        $this->assertDatabaseHas('layanans', [
            'nama_layanan' => 'Reguler',
            'harga_per_kg' => 7000,
            'estimasi_waktu' => '3-4 hari',
        ]);

        $this->assertDatabaseHas('layanans', [
            'nama_layanan' => 'Ekspres',
            'harga_per_kg' => 12000,
            'estimasi_waktu' => '1-2 hari',
        ]);

        $this->assertDatabaseHas('layanans', [
            'nama_layanan' => 'Superkilat',
            'harga_per_kg' => 25000,
            'estimasi_waktu' => '5 jam selesai',
        ]);

        $this->assertSame(3, Layanan::query()->count());
    }
}
