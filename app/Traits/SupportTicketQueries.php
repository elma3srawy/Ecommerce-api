<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;


trait SupportTicketQueries
{
    const TABLE = 'support_tickets';
    protected static function storeTicketQuery($data)
    {
        DB::table(self::TABLE)->insert($data);
    }
}
