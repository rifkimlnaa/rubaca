<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_rejects_password_shorter_than_eight_characters(): void
    {
        $this->from('/daftar')->post('/daftar', $this->registrationData('Abc1234'))
            ->assertRedirect('/daftar')
            ->assertSessionHasErrors('password');
    }

    public function test_registration_rejects_password_without_an_uppercase_letter(): void
    {
        $this->from('/daftar')->post('/daftar', $this->registrationData('ruangbaca123'))
            ->assertRedirect('/daftar')
            ->assertSessionHasErrors('password');
    }

    public function test_registration_rejects_password_without_a_number(): void
    {
        $this->from('/daftar')->post('/daftar', $this->registrationData('RuangBaca'))
            ->assertRedirect('/daftar')
            ->assertSessionHasErrors('password');
    }

    public function test_member_can_register_with_password_that_meets_the_rules(): void
    {
        $this->post('/daftar', $this->registrationData('RuangBaca8'))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHasNoErrors();

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'anggota.baru@example.test',
            'role' => 'member',
        ]);
    }

    private function registrationData(string $password): array
    {
        return [
            'name' => 'Anggota Uji',
            'email' => 'anggota.baru@example.test',
            'password' => $password,
            'password_confirmation' => $password,
        ];
    }
}
