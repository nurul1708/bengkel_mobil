<?php

namespace App\Providers;

use App\Models\Chat;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

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
        view()->composer('fe.master', function ($view) {
            $unreadAdminChatsCount = 0;
            $hasChatReadAtColumn = Schema::hasColumn('chats', 'read_at');

            if (auth('client')->check() && $hasChatReadAtColumn) {
                $unreadAdminChatsCount = Chat::where('user_id', auth('client')->id())
                    ->where('pengirim', 'admin')
                    ->whereNull('read_at')
                    ->count();
            }

            $view->with('unreadAdminChatsCount', $unreadAdminChatsCount);
        });
    }
}
