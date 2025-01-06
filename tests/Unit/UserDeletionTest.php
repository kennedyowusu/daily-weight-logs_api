<?php

namespace Tests\Unit;

use App\Events\UserDeleted;
use App\Listeners\LogUserDeletion;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class UserDeletionTest extends TestCase
{
    /**
     * Test the UserDeleted event is fired correctly.
     */
    public function test_user_deleted_event_is_dispatched()
    {
        $user = User::factory()->create();

        // Mock the event
        Event::fake();

        // Dispatch the event
        event(new UserDeleted($user));

        // Assert the event was dispatched
        Event::assertDispatched(UserDeleted::class, function ($event) use ($user) {
            return $event->user->id === $user->id;
        });
    }

    /**
     * Test the LogUserDeletion listener handles the UserDeleted event.
     */
    public function test_log_user_deletion_listener_logs_data_correctly()
    {
        $user = User::factory()->create();

        Log::shouldReceive('info')
            ->once()
            ->with('User deleted', [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'deleted_at' => now()->toDateTimeString(),
            ]);

        $listener = new LogUserDeletion();
        $listener->handle(new UserDeleted($user));
    }
}
