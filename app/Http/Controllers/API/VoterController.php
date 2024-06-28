<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Voter;
use Auth;
use Illuminate\Http\Request;

class VoterController extends Controller
{
    public function index()
    {
        if (Auth::user()->role == "admin") {
            $voters = Voter::select('id', 'name', 'email', 'dob', 'address', 'phone','created_at','updated_at')->latest()->get();
        } else {
            $voters = Voter::select('id', 'name', 'email', 'dob', 'address', 'phone')->get();
        }
        return response()->json($voters);
    }

    public function show($id)
    {
        $voter = Voter::findOrFail($id);
        if (Auth::user()->role == "admin") {
            $response = $voter;
        } else {
            $response = [
                'id' => $voter->id,
                'name' => $voter->name,
                'email' => $voter->email,
                'dob' => $voter->dob,
                'address' => $voter->address,
                'phone' => $voter->phone,
            ];
        }

        return response()->json($response);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:voters',
            'password' => 'required|string|min:8',
            'dob' => 'required|date',
        ]);

        $voter = Voter::create($request->all());

        return response()->json($voter, 201);
    }

    public function update(Request $request, $id)
    {
        $voter = Voter::findOrFail($id);
        $voter->update($request->all());

        return response()->json($voter, 200);
    }

    public function destroy($id)
    {
        Voter::destroy($id);

        return response()->json(null, 204);
    }


}
