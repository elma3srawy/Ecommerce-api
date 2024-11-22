<?php

namespace App\Repository;

use App\Traits\FileManager;
use Illuminate\Http\Request;
use App\Traits\CategoryQueries;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\Repository\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    use CategoryQueries , FileManager;
    private $request;
    private $now;
    private $auth;

    public function __construct()
    {
        $this->request = request();
        $this->now = now();
        $this->auth = Auth::user();
    }
    public function store($validated)
    {
        $validated['created_by']   = $this->auth->id;
        $validated['created_at'] = $this->now;
        $validated['updated_at'] = $this->now;

        if($this->request->hasFile('image'))
        {
            $path = $this->UploadFile($this->request->file('image') , 'categories')->path;
            unset($validated['image']);
            $validated['image_url'] = $path;
        }

        $this->storeCategory($validated);
    }

    public function update($validated)
    {

        $validated['updated_by']  = $this->auth->id;
        $validated['updated_at'] = $this->now;

        $old_path = $this->getImagePathByCategoryId($this->request->id);

        $this->deleteFile($old_path);

        if($this->request->hasFile('image'))
        {
            $path = $this->uploadFile($this->request->file('image'), 'categories')->path;

            unset($validated['image']);

            $validated['image_url'] = $path;
        }else
        {
            $validated['image_url'] = null;
        }

        $this->updateCategoryById($this->request->id , $validated);
    }

    public function delete($category_id)
    {

        $this->deleteFile($this->getImagePathByCategoryId($category_id));
       
        $this->deleteCategoryById($category_id);
    }

	public function getAllCategories()
    {
        return $this->getAllCategoriesQuery();
    }

	public function getSubCategories()
    {
        return $this->getSubCategoriesQuery();
    }

	public function getParentCategories()
    {
        return $this->getParentCategoriesQuery();
    }

	public function getCategoryById(string $category_id)
    {
        return $this->getCategoryByIdQuery($category_id);
    }

	public function getActiveCategory()
    {
        return $this->getActiveCategoryQuery();
    }

	public function getInActiveCategory()
    {
        return $this->getInActiveCategoryQuery();
    }
}
