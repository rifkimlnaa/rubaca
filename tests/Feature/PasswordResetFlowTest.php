<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password_page_is_available(): void
    {
        $this->get(route('password.request'))->assertOk();
    }

    public function test_registered_user_receives_a_reset_notification(): void
    {
        Notification::fake();
        $user = $this->makeUser();

        $this->from(route('password.request'))
            ->post(route('password.email'), ['email' => $user->email])
            ->assertRedirect(route('password.request'))
            ->assertSessionHas('status');

        Notification::assertSentTo($user, ResetPasswordNotification::class);
    }

    public function test_unknown_email_gets_the_same_public_confirmation_message(): void
    {
        $this->from(route('password.request'))
            ->post(route('password.email'), ['email' => 'tidak-ada@example.test'])
            ->assertRedirect(route('password.request'))
            ->assertSessionHas('status');
    }

    public function test_reset_link_changes_password_and_preserves_user_role(): void
    {
        Notification::fake();
        $user = $this->makeUser(role: 'admin');
        $this->post(route('password.email'), ['email' => $user->email])->assertSessionHas('status');

        $token = null;
        Notification::assertSentTo($user, ResetPasswordNotification::class, function (ResetPasswordNotification $notification) use (&$token): bool {
            $token = $notification->token;
            return true;
        });

        $this->assertNotEmpty($token);

        $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'KataBaru123',
            'password_confirmation' => 'KataBaru123',
        ])->assertRedirect(route('login'))->assertSessionHas('success');

        $user->refresh();
        $this->assertTrue(Hash::check('KataBaru123', $user->password));
        $this->assertSame('admin', $user->role);
    }

    public function test_reset_password_enforces_the_same_password_rules_as_registration(): void
    {
        Notification::fake();
        $user = $this->makeUser();
        $this->post(route('password.email'), ['email' => $user->email]);

        $token = null;
        Notification::assertSentTo($user, ResetPasswordNotification::class, function (ResetPasswordNotification $notification) use (&$token): bool {
            $token = $notification->token;
            return true;
        });

        $this->from(route('password.reset', ['token' => $token, 'email' => $user->email]))
            ->post(route('password.update'), [
                'token' => $token,
                'email' => $user->email,
                'password' => 'tidakvalid123',
                'password_confirmation' => 'tidakvalid123',
            ])
            ->assertRedirect();

        $this->assertFalse(Hash::check('tidakvalid123', $user->fresh()->password));
    }

    private function makeUser(string $role = 'member'): User
    {
        return User::create([
            'name' => 'Pengguna Percobaan',
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('KataLama123'),
            'role' => $role,
            'member_code' => 'TEST-'.strtoupper(bin2hex(random_bytes(3))),
            'is_active' => true,
        ]);
    }
}
