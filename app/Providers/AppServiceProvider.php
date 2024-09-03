<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Filesystem\Filesystem;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
      
  
 
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Listen to the login event
        Event::listen(Login::class, function ($event) {
            $user = $event->user;
            if ($user) {
                $user->is_active = 1;
                $user->save();
            }
        });

        // Listen to the logout event
        Event::listen(Logout::class, function ($event) {
            $user = $event->user;
            if ($user) {
                $user->is_active = 0;
                $user->save();
            }
        });

        // Check session expiration and update user status
        $this->checkExpiredSessions();
    }

    /**
     * Check for expired sessions and update 'is_active' status accordingly.
     */
    protected function checkExpiredSessions()
    {
        // Define the session lifetime in minutes
        $sessionLifetime = config('session.lifetime'); // e.g., 120 minutes

        // Calculate the expiry threshold timestamp
        $expiryThreshold = Carbon::now()->subMinutes($sessionLifetime)->timestamp;

        // Query to check for expired sessions
        $expiredSessions = DB::table('sessions')
            ->where('last_activity', '<', $expiryThreshold)
            ->get();

        // Update 'is_active' status for users with expired sessions
        foreach ($expiredSessions as $session) {
            // Assuming sessions table has user_id or some way to link to a user
            $user = DB::table('users')->where('id', $session->user_id)->first();
            if ($user) {
                $user->is_active = 0;
                DB::table('users')->where('id', $user->id)->update(['is_active' => 0]);
            }
        }
    }
}
