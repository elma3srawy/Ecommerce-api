<?php

namespace App\Http\Controllers\Supports;

use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Traits\SupportTicketQueries;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Supports\TicketStoreRequest;
use App\Http\Requests\Supports\TicketUpdateRequest;

class TicketController extends Controller
{
    use SupportTicketQueries, ResponseTrait;
    private $now;
    private $user;
    public function __construct()
    {
        $this->now = now();
        $this->user = Auth::user();
    }
    public function store(TicketStoreRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = $this->user->id;
        $validated['created_at'] = $this->now;
        $this->storeTicketQuery($validated);

        return $this->successResponse(message: 'Ticket stored successfully');
    }
    public function update(TicketUpdateRequest $request)
    {
        DB::table('support_tickets')
        ->where('id' , '=', $request->ticket_id)
        ->update(['status' => $request->status]);

        return $this->successResponse(message: 'Ticket updated successfully');
    }
}
