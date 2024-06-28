<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Voter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /* public function register(Request $request)
    {
    $request->validate([
    'name' => 'required|string|max:255',
    'email' => 'required|string|email|max:255|unique:users',
    'password' => 'required|string|min:8|confirmed',
    ]);

    $user = User::create([
    'name' => $request->name,
    'email' => $request->email,
    'password' => Hash::make($request->password),
    'role' => 'user',
    ]);

    return response()->json(['message' => 'User registered successfully!']);
    }
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(["success" => false, "message" => "The provided credentials are incorrect."], 422);

        }

        $token = $user->createToken('authToken')->plainTextToken;
        $response = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'token' => $token,
        ];

        return response()->json($response);

    }

    public function voterRegister(Request $request)
    {
        $messages = [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name may not be greater than 255 characters.',

            'email.required' => 'The email field is required.',
            'email.string' => 'The email must be a string.',
            'email.email' => 'The email must be a valid email address.',
            'email.max' => 'The email may not be greater than 255 characters.',
            'email.unique' => 'The email has already been taken.',

            'password.required' => 'The password field is required.',
            'password.string' => 'The password must be a string.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.confirmed' => 'The password confirmation does not match.',

            'password_confirmation.required' => 'The password confirmation field is required.',
            'password_confirmation.string' => 'The password confirmation must be a string.',
            'password_confirmation.min' => 'The password confirmation must be at least 8 characters.',

            'dob.required' => 'The date of birth field is required.',
            'dob.date' => 'The date of birth is not a valid date.',
            'dob.before_or_equal' => 'You must be 18 years or older to register.',

            'address.required' => 'The address field is required.',
            'address.string' => 'The address must be a string.',
            'address.max' => 'The address may not be greater than 255 characters.',

            'phone.required' => 'The phone field is required.',
            'phone.string' => 'The phone must be a string.',
            'phone.max' => 'The phone may not be greater than 15 characters.',

            'aadhar_no.required' => 'The Aadhar Number field is required.',
            'aadhar_no.integer' => 'The Aadhar Number must be a number.',
            'aadhar_no.min' => 'Invalid Aadhar Number.',
            'aadhar_no.max' => 'The Aadhar Number must be exactly 12 numbers.',
            'aadhar_no.unique' => 'The Aadhar Number has already been taken.',
        ];

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:voters',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
            'dob' => 'required|date|before_or_equal:' . Carbon::now()->subYears(18)->format('Y-m-d'),
            'aadhar_no' => 'required|string|min:12|max:12|unique:voters',
            'phone' => 'required|string',
            'address' => 'required|string',
        ], $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422);
        }

        // dd($request);
        $voter = Voter::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'dob' => $request->dob,
            'aadhar_no' => $request->aadhar_no,
            'address' => $request->address,
            'phone' => $request->phone,
        ]);

        return response()->json(['message' => 'Voter Registration Completed!'], 201);
    }

    public function voterLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $voter = Voter::where('email', $request->email)->first();

        if (!$voter || !Hash::check($request->password, $voter->password)) {
            return response()->json(["success" => false, "message" => "The provided credentials are incorrect."], 422);

        }

        $response = [
            'id' => $voter->id,
            'name' => $voter->name,
            'email' => $voter->email,
            'dob' => $voter->dob,
            'address' => $voter->address,
            'phone' => $voter->phone,
            'token' => $voter->createToken('VoterToken')->plainTextToken,
        ];

        return response()->json($response);
    }
}
