<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CandidateController extends Controller
{
    public function index()
    {
        if(Auth::user())
        {
            if (Auth::user()->role == "admin") {
                $candidates = Candidate::latest()->get()
                ->map(function ($candidate) {
                    $baseUrl = config('my_values.my_image_url');
                    $candidate->symbol_url = $baseUrl. $candidate->symbol_url;
                    $candidate->photo_url = $baseUrl. $candidate->photo_url;

                    return $candidate;
                });
            }
        }
        else {
            $candidates = Candidate::select('id', 'name','party', 'symbol_url','photo_url')
    ->get()
    ->map(function ($candidate) {
        $baseUrl = config('my_values.my_image_url');
        $candidate->symbol_url = $baseUrl. $candidate->symbol_url;
        $candidate->photo_url = $baseUrl. $candidate->photo_url;

        return $candidate;
    });
        }
        return response()->json($candidates);
    }

    public function show($id)
    {
        $candidate = Candidate::findOrFail($id);
        if (Auth::user()->role == "admin") {
            $response = $candidate;
        } else {
            $response = [
                'id' => $candidate->id,
                'name' => $candidate->name,
                'party' => $candidate->party,
                'description' => $candidate->description,
                'photo_url' => $candidate->photo_url,
                'symbol_url' => $candidate->symbol_url,
            ];
        }

        return response()->json($response);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'party' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'photo_url' => 'nullable|url',
            'symbol_url' => 'nullable|url',
        ]);

        $candidate = Candidate::create($request->all());

        return response()->json($candidate, 201);
    }

    public function update(Request $request, $id)
    {
        $candidate = Candidate::findOrFail($id);
        $candidate->update($request->all());

        return response()->json($candidate, 200);
    }

    public function destroy($id)
    {
        Candidate::destroy($id);

        return response()->json(null, 204);
    }
}
