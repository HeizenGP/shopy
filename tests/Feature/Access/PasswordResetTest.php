<?php

use App\Access\Infrastructure\Models\AccessAuditLogModel;
use App\Access\Infrastructure\Models\UserModel;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

const PASSWORD_RESET_MESSAGE = 'Si el correo existe en nuestro sistema, recibirás un enlace para restablecer tu contraseña.';

it('renders forgot and reset password forms for guests', function (): void {
    $this->get('/admin/forgot-password')
        ->assertOk()
        ->assertSee('Recuperar contraseña');

    $this->get('/admin/reset-password/test-token?email=admin@shopy.test')
        ->assertOk()
        ->assertSee('Nueva contraseña');
});

it('sends a password reset email notification and records an audit log', function (): void {
    Notification::fake();

    $user = UserModel::query()->create([
        'name' => 'Reset User',
        'email' => 'reset@shopy.test',
        'password' => 'OldPassword2026!',
        'is_active' => true,
    ]);

    $this->post('/admin/forgot-password', [
        'email' => $user->email,
    ])->assertSessionHas('status', PASSWORD_RESET_MESSAGE);

    $this->assertDatabaseHas('password_reset_tokens', [
        'email' => $user->email,
    ]);

    Notification::assertSentTo($user, ResetPasswordNotification::class, function (ResetPasswordNotification $notification) use ($user): bool {
        $mail = $notification->toMail($user);

        expect($mail->actionUrl)->toContain('/admin/reset-password/'.$notification->token);
        expect($mail->actionUrl)->toContain('email=reset%40shopy.test');

        return true;
    });

    $log = AccessAuditLogModel::query()->where('event', 'password_reset_requested')->firstOrFail();

    expect($log->metadata)->toMatchArray([
        'email' => $user->email,
    ]);
    expect(json_encode($log->metadata))->not->toContain('OldPassword2026!');
});

it('uses the same forgot password response when the email does not exist', function (): void {
    Notification::fake();

    $this->post('/admin/forgot-password', [
        'email' => 'missing@shopy.test',
    ])->assertSessionHas('status', PASSWORD_RESET_MESSAGE);

    Notification::assertNothingSent();
    $this->assertDatabaseMissing('password_reset_tokens', [
        'email' => 'missing@shopy.test',
    ]);
    $this->assertDatabaseHas('access_audit_logs', [
        'user_id' => null,
        'event' => 'password_reset_requested',
    ]);
});

it('rejects short reset passwords', function (): void {
    $this->post('/admin/reset-password', [
        'token' => 'test-token',
        'email' => 'reset@shopy.test',
        'password' => 'short',
        'password_confirmation' => 'short',
    ])->assertSessionHasErrors('password');
});

it('rejects reset passwords without matching confirmation', function (): void {
    $this->post('/admin/reset-password', [
        'token' => 'test-token',
        'email' => 'reset@shopy.test',
        'password' => 'NewPassword2026!',
        'password_confirmation' => 'DifferentPassword2026!',
    ])->assertSessionHasErrors('password');
});

it('resets the password with a valid emailed token and records audit data', function (): void {
    Notification::fake();

    $user = UserModel::query()->create([
        'name' => 'Reset User',
        'email' => 'reset-complete@shopy.test',
        'password' => 'OldPassword2026!',
        'is_active' => true,
    ]);

    $this->post('/admin/forgot-password', [
        'email' => $user->email,
    ])->assertRedirect();

    $token = null;
    Notification::assertSentTo($user, ResetPasswordNotification::class, function (ResetPasswordNotification $notification) use (&$token): bool {
        $token = $notification->token;

        return true;
    });

    $this->post('/admin/reset-password', [
        'token' => $token,
        'email' => $user->email,
        'password' => 'NewPassword2026!',
        'password_confirmation' => 'NewPassword2026!',
    ])->assertRedirect('/admin/login');

    $this->assertGuest();

    $user->refresh();

    expect(Hash::check('NewPassword2026!', $user->password))->toBeTrue();
    expect(Hash::check('OldPassword2026!', $user->password))->toBeFalse();
    expect($user->password_changed_at)->not->toBeNull();
    $this->assertDatabaseMissing('password_reset_tokens', [
        'email' => $user->email,
    ]);

    $log = AccessAuditLogModel::query()->where('event', 'password_reset_success')->firstOrFail();

    expect($log->metadata)->toMatchArray([
        'email' => $user->email,
    ]);
    expect(json_encode($log->metadata))->not->toContain($token);
    expect(json_encode($log->metadata))->not->toContain('NewPassword2026!');
});

it('rejects invalid password reset tokens', function (): void {
    $user = UserModel::query()->create([
        'name' => 'Reset User',
        'email' => 'invalid-token@shopy.test',
        'password' => 'OldPassword2026!',
        'is_active' => true,
    ]);

    $this->post('/admin/reset-password', [
        'token' => 'invalid-token',
        'email' => $user->email,
        'password' => 'NewPassword2026!',
        'password_confirmation' => 'NewPassword2026!',
    ])->assertSessionHasErrors('email');

    $this->assertDatabaseHas('access_audit_logs', [
        'user_id' => $user->id,
        'event' => 'password_reset_failed',
    ]);
    expect(Hash::check('OldPassword2026!', $user->fresh()->password))->toBeTrue();
});
