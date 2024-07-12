<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
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

        ResetPassword::toMailUsing(function (object $notifiable, string $token) {
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));
            return (new MailMessage)
                ->subject('[Unicode Academy] Yêu cầu đặt lại mật khẩu')
                ->line('Hãy click vào nút bên dưới để đặt lại mật khẩu tài khoản của bạn')
                ->line('Chúng tôi nhận được yêu cầu đặt lại mật khẩu của bạn')
                ->action('Đặt lại mật khẩu', $url)
                ->line('Nếu bạn không gửi yêu cầu thì không cần làm gì cả');
        });
    }
}
