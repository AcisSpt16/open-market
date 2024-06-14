<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use  App\Models\User;

class UserController extends Controller
{
     /**
     * Instantiate a new UserController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get the authenticated User.
     *
     * @return Response
     */
    public function profile()
    {
        return response()->json(['user' => Auth::user()], 200);
    }

    public function updateProfile(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'phone_number' => 'required|string|max:15',
            'street' => 'required|string|max:255',
            'suburb' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'zip' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $user->update($request->only('name', 'email', 'phone_number', 'street', 'suburb', 'city', 'zip'));

        return response()->json(['message' => 'Profile updated successfully', 'user' => $user]);
    }

   
}
