<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class TrenmartResetPasswordNotification extends ResetPassword
{
    /**
     * Build the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Reset Password Akun Trenmart')
            ->greeting('Halo ' . ($notifiable->name ?? 'Pelanggan Trenmart') . ',')
            ->line('Kami menerima permintaan reset password untuk akun Trenmart Anda.')
            ->line('Klik tombol di bawah ini untuk membuat password baru:')
            ->action('Reset Password', $url)
            ->line('Link ini hanya berlaku sementara demi keamanan akun Anda.')
            ->line('Jika Anda tidak merasa meminta reset password, abaikan email ini.')
            ->salutation('Salam hangat, Tim Trenmart');
    }
}
