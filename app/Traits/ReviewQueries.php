<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;


trait ReviewQueries
{
    const TABLE = 'reviews';
    protected static function storeReviewQuery($data)
    {
        DB::table(self::TABLE)->insert($data);
    }

}
