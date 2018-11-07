<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Notifications\SayHello;

use App\User;

use Log;

class NotificationController extends Controller
{
    public function notify(Request $request)
    {
        // Find user to notify
        if ($request->has('username') && !is_null($request->username)) {
            $user = User::where('name', $request->username)->first();

            // get message or set to default
            $msg = $request->message ?? 'OH NO! You forgot to add a message to the notification.';

            Log::info('notify user ' . $user->id . ' with message ' . $msg);
            $user->notify(new SayHello($request->message));

            return response('Notification created, sending based on service worker setup and production run in frontend.', 200);
        }
        return response('No user found in request, can not create push notification', 422);
    }
}
