<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Auth\Events\Logout;

class UserLoggedOut
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
    public function handle(object $event): void
    {
        /**
         * $event->user => [2023-12-07 16:49:54] local.INFO: {"id":1,"name":"David","email":"ps.shim87@gmail.com","email_verified_at":null,"two_factor_confirmed_at":null,"current_team_id":null,"profile_photo_path":null,"created_at":"2023-12-07T15:39:19.000000Z","updated_at":"2023-12-07T15:39:19.000000Z","profile_photo_url":"https:\/\/ui-avatars.com\/api\/?name=D&color=7F9CF5&background=EBF4FF"}  
         */

    }
}
