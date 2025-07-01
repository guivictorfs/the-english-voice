<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class UpdateSessionUserIdOnLogin
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $sessionId = session()->getId();
        $userId = $event->user->id;
        DB::table('sessions')
            ->where('id', $sessionId)
            ->update(['user_id' => $userId]);
    }
}
