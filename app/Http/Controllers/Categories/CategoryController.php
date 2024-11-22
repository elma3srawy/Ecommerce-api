<?php

namespace App\Http\Controllers\Categories;

use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Interfaces\Repository\CategoryRepositoryInterface;

class CategoryController extends Controller
{
    use ResponseTrait;
    public function __construct(private CategoryRepositoryInterface $category)
    {}

    public function store(CategoryRequest $request)
    {
       $validated = $request->onStore();
        try {
            $this->category->store($validated);

            return $this->successResponse(
                message: "Category created successfully!",
                statusCode: 201
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                message: "Failed to create category. ",
                statusCode: 500
            );
        }
    }
    public function update(CategoryRequest $request)
    {
        $validated = $request->onUpdate();
        try {
            $this->category->update($validated);

            return $this->successResponse(
                message: "Category updated successfully!",
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                message: "Failed to create category. ",
            );
        }
    }
    public function destroy(CategoryRequest $request)
    {
        $validated = $request->onDelete(); 
        try {
            $this->category->delete($validated["category_id"]);
            return $this->successResponse(
                message: "Category Deleted successfully!",
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                message: "Failed to Delete category.",
            );
        }
    }
    public function getAllCategories()
    {
        return $this->category->getAllCategories();
    }

	public function getSubCategories()
    {
        return $this->category->getSubCategories();
    }

	public function getParentCategories()
    {
        return $this->category->getParentCategories();

    }

	public function getCategoryById(string $category_id)
    {
        return $this->category->getCategoryById($category_id);
    }

	public function getActiveCategory()
    {
        return $this->category->getActiveCategory();
    }

	public function getInActiveCategory()
    {
        return $this->category->getInActiveCategory();
    }

}
