<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public function __construct(public readonly string $token)
    {
    }

    /** @return array<int, string> */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        $expiration = (int) config('auth.passwords.users.expire', 60);

        return (new MailMessage)
            ->subject('Atur ulang kata sandi — Ruang Baca')
            ->greeting('Halo, '.$notifiable->name.'!')
            ->line('Kami menerima permintaan untuk mengatur ulang kata sandi akun Ruang Baca Anda.')
            ->action('Atur Ulang Kata Sandi', $url)
            ->line("Tautan ini berlaku selama {$expiration} menit dan hanya dapat digunakan satu kali.")
            ->line('Apabila Anda tidak merasa meminta pengaturan ulang kata sandi, abaikan email ini. Kata sandi Anda tidak akan berubah.')
            ->salutation('Salam, Tim Ruang Baca');
    }
}
