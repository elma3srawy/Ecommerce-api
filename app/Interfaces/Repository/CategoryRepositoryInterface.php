<?php

namespace App\Interfaces\Repository;

use App\Interfaces\Repository\RepositoryInterface;

interface CategoryRepositoryInterface extends RepositoryInterface
{
    public function getAllCategories();
    public function getSubCategories();
    public function getParentCategories();
    public function getCategoryById(string $id);
    public function getActiveCategory();
    public function getInActiveCategory();
}
