<?php

namespace App\Listeners;

use App\Events\UserDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LogUserDeletion
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserDeleted $event): void
    {
        $user = $event->user;

        // Insert deletion log into the database
        DB::table('deletion_logs')->insert([
            'user_id' => $user->id,
            'email' => $user->email,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'deleted_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Optional: Log to the Laravel log file as well
        Log::info("User deleted", [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'deleted_at' => now()->toDateTimeString()
        ]);
    }
}
