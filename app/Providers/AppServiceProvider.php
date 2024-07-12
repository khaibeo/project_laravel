<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Xác thực địa chỉ email')
                ->line('Click vào nút bên dưới để xác thực địa chỉ email của bạn.')
                ->action('Xác thực địa chỉ email', $url)
                ->line('Nếu bạn không phải là người tạo tài khoản, vui lòng bỏ qua email này !.');
        });
    }
}
