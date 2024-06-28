<?php
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CandidateController;
use App\Http\Controllers\API\VoteController;
use App\Http\Controllers\API\VoterController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// user related  public routes
Route::post('admin-login', [AuthController::class, 'login']);

// voter related  public routes
Route::post('voter-register', [AuthController::class, 'voterRegister']);
Route::post('voter-login', [AuthController::class, 'voterLogin']);
Route::apiResource('candidates', CandidateController::class);

//  protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Authenticated user route
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Voter routes
    Route::apiResource('voters', VoterController::class);

    // Candidate routes

    // Vote routes
    Route::post('votes', [VoteController::class, 'store']);
    Route::get('votes', [VoteController::class, 'index']);
    Route::get('/votes/status', [VoteController::class, 'hasVoted']);
    Route::post('/votes/validate-aadhar', [VoteController::class, 'isValidAadhar']);
    // Admin routes
    Route::middleware([AdminMiddleware::class])->group(function () {
        Route::get('admin/results', [VoteController::class, 'results']);
    });
});
