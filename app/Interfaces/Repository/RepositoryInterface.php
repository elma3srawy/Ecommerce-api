<?php

namespace App\Interfaces\Repository;


interface RepositoryInterface
{
    public function store($validated);
    public function update($validated);
    public function delete($validated);
}
