<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use NotificationChannels\WebPush\PushSubscription;

use App\User;

class SubscriptionController extends Controller
{
    /**
     * Store (or update) a subscription for a user. All information is in the $request which has the following format:
     *     $request->user: an integer, the user ID
     *     $request->subscription: an array with keys 'endpoint' and 'keys'
     *         $request->subscription['endpoint']: (string) the endpoint for the browser our user is using
     *         $request->subscription['keys']: an array with two keys 'p256dh' and 'auth', used for encrypting the notifications
     *             $request->subscription['keys']['p256dh']: 2nd argument in updatePushSubscription() below
     *             $request->subscription['keys']['auth']: 3nd argument in updatePushSubscription() below
     *
     * From the Webpush package docs: "The $key and $token are optional and are used to encrypt your notifications. Only encrypted notifications can have a payload."
     *
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request)
    {
        // get user from request
        $user = User::findOrFail($request->userId);

        // create PushSubscription and connect to this user
        $pushsub = $user->updatePushSubscription($request->subscription['endpoint'], $request->subscription['keys']['p256dh'], $request->subscription['keys']['auth']);

        return $pushsub;
    }

    /**
     * Based on the endpoint which needs to be available in the
     * $request, find the correct subscription and delete it from the DB
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function destroy(Request $request)
    {
        $this->validate($request, ['endpoint' => 'required']);

        // PushSubscription::findByEndpoint is a static function on the PushSubscription model from the package.
        // This will return the PushSubscription model instance corresponding to the given endpoint.
        // We than retrieve the user instance from the belongsTo relation also defined in the PushSubscription model
        // Note that we did add the use statement with correct namespace on top of this file.
        $user = PushSubscription::findByEndpoint($request->endpoint)->user;

        $user->deletePushSubscription($request->endpoint);

        return response()->json(null, 204);
    }
}
