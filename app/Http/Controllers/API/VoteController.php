<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Vote;
use App\Models\Voter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VoteController extends Controller
{
    public function index()
    {
        return Vote::all();
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'voter_id' => 'required|exists:voters,id',
            'candidate_id' => 'required|exists:candidates,id',
        ], [
            'voter_id.required' => 'Voter ID is required.',
            'voter_id.exists' => 'The selected voter ID is invalid.',
            'candidate_id.required' => 'Please select one candidate.',
            'candidate_id.exists' => 'The selected candidate ID is invalid.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $currentYear = Carbon::now()->year;
        $existingVote = Vote::where('voter_id', $request->voter_id)
            ->where('election_year', $currentYear)
            ->exists();

        if ($existingVote) {

            return response()->json(["success" => false, "message" => "You have already cast your vote. Thank you for participating!."], 422);

        }

        $requestData = $request->all();
        $requestData['election_year'] = $currentYear;
        $vote = Vote::create($requestData);

        return response()->json(['message' => 'Thank you for voting!'], 201);
    }

    public function hasVoted(Request $request)
    {
        $user = $request->user();
        $hasVoted = Vote::where('voter_id', $user->id)
            ->whereYear('election_year', now()->year)
            ->exists();

        return response()->json(['status' => $hasVoted]);
    }

    public function isValidAadhar(Request $request)
    {
        $user = $request->user();
        $hasValid = Voter::where('id', $user->id)
            ->where('aadhar_no', $request->aadharNumber)
            ->exists();

        return response()->json($hasValid);
    }

    public function results()
    {

        $candidates = Candidate::all();

        $voteResults = Vote::select('candidate_id', 'election_year', DB::raw('count(*) as total_votes'))
            ->groupBy('candidate_id', 'election_year')
            ->get();

        $formattedResults = [];
        $baseUrl = config('my_values.my_image_url');

        $voteMap = [];
        foreach ($voteResults as $vote) {
            $voteMap[$vote->candidate_id][$vote->election_year] = $vote->total_votes;
        }

        foreach ($candidates as $candidate) {
            foreach ($voteMap[$candidate->id] ?? [null] as $election_year => $total_votes) {
                $formattedResults[] = [
                    'candidate_name' => $candidate->name,
                    'party' => $candidate->party,
                    'photo_url' => $baseUrl . $candidate->photo_url,
                    'symbol' => $baseUrl . $candidate->symbol_url,
                    'election_year' => $election_year ?? 'N/A',
                    'total_votes' => $total_votes ?? 0,
                ];
            }
        }

        return response()->json(['results' => $formattedResults]);
    }

}
