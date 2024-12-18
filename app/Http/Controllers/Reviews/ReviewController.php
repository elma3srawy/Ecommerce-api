<?php

namespace App\Http\Controllers\Reviews;

use App\Traits\ResponseTrait;
use App\Traits\ReviewQueries;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Reviews\StoreReviewRequest;

class ReviewController extends Controller
{
    use ReviewQueries , ResponseTrait;
    private $now ;
    private $user;
    public function __construct()
    {
        $this->now = now();
        $this->user = Auth::user();
    }
    public function store(StoreReviewRequest $request)
    {
        $validated = $request->validated();
        $validated['created_at'] = $this->now;
        $validated['user_id'] = $this->user->id;
        $this->storeReviewQuery($validated);

        return $this->successResponse(message: 'Review stored successfully');
    }
}
