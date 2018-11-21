<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\User;

class UserController extends Controller
{
    /**
     * If the request contains a username, retrieve this user from
     * the database and return it.
     * Otherwise, create a new user with a random name and return it.
     * @return new user object as json
     */
    public function createOrRetrieve(Request $request)
    {
        if ($request->has('username') && !is_null($request->username)) {
            $user = User::where(['name' => $request->username])->first();
        } else {
            // create a new user instance.
            // name of the user is just a hashed string
            $user = User::create(['name' => Str::uuid()]);
        }

        return response()->json(compact('user'));
    }
}
