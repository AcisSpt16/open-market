<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Store a new user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function register(Request $request)
    {
        //validate incoming request 
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'phone_number' => 'required|string|max:15',
        ]);

        try {

            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $plainPassword = $request->input('password');
            $user->password = app('hash')->make($plainPassword);

             $user->phone_number = $request->input('phone_number');
            $user->street = $request->input('street');
            $user->suburb = $request->input('suburb');
            $user->city = $request->input('city');
            $user->zip = $request->input('zip');
            $user->save();

            //return successful response
            return response()->json(['user' => $user, 'message' => 'CREATED'], 201);

        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'User Registration Failed!'], 409);
        }

    }
/**
     * Get a JWT via given credentials.
     *
     * @param  Request  $request
     * @return Response
     */
    public function login(Request $request)
    {
          //validate incoming request 
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (! $token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
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
