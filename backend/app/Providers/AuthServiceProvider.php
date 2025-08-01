<?php

    namespace App\Providers;

    use Illuminate\Auth\Notifications\ResetPassword;
    use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

    class AuthServiceProvider extends ServiceProvider
    {
        /**
         * The policy mappings for the application.
         *
         * @var array<class-string, class-string>
         */
        protected $policies = [
            // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        ];

        /**
         * Register any authentication / authorization services.
         */
        public function boot(): void
        {
            $this->registerPolicies();

            // Opsional: Jika kamu ingin mengkustomisasi notifikasi reset password
            // ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            //     return url('/reset-password/'.$token.'?email='.$notifiable->getEmailForPasswordReset());
            // });
        }
    }
    