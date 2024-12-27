<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function login(Request $request)
    {
        //validation
        $validation = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        //attempt login, ini tanda seru 
        if (! auth()->attempt($validation)) {
            return response()->json([
                'message'=>'Invalid credentials',
            ], 401);    
        }
        //return response
        return response()->json([
            'massage'=> 'Login successeful',
            'user'=> auth()->user(),
            'token'=> auth()->user()->createToken('authToken')->plainTextToken,
            'role' => auth()->user()->getRoleNames()->first(),
        ], 200);
        
    }


    /**
     * Store a newly created resource in storage.
     */
    public function register(Request $request)
    {
        // $validation = $request->$request
        
        //validation
         $validation = $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            ]);
        

        // create user
        $user = User::create([
            'name' => $validation ['name'],
            'email' => $validation['email'],
            'password' => bcrypt($validation['password']),
        ]);

        $user->assignRole('customer');
        
        // return response
        return response()->json([
            'message'=> 'Registration succesfull',
            'user'=> $user,
        ], 201);
    }
}
