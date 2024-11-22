<?php

namespace App\Interfaces\Repository;

use App\Interfaces\Repository\RepositoryInterface;

interface ProductRepositoryInterface extends RepositoryInterface
{
    public function getAllProducts();
    public function getProductById(string $id);
    public function getPricingHistory(string $id);
    public function changePrice($data);
    public function changeImage($data);

}
