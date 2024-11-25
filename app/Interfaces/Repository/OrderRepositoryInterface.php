<?php

namespace App\Interfaces\Repository;

interface OrderRepositoryInterface extends RepositoryInterface
{
    public function cancelOrder($validated);
}
