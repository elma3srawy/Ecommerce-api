<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;


trait CategoryQueries
{
    protected function storeCategory($data)
    {
        DB::table('categories')->insert($data);
    }

    protected function updateCategoryById($id , $data)
    {
        DB::table('categories')->whereId($id)->update($data);
    }
    protected function getImagePathByCategoryId(string $id)
    {
        return DB::table('categories')->whereId($id)->value('image_url');
    }
    protected function deleteCategoryById(string $id)
    {
        return DB::table('categories')->delete($id);
    }

    protected function getAllCategoriesQuery()
    {
        return DB::table('categories')->paginate(PAGINATE);
    }

	protected function getSubCategoriesQuery()
    {
        return DB::table('categories')->whereNotNull('parent_id')->paginate(PAGINATE);
    }

	protected function getParentCategoriesQuery()
    {
        return DB::table('categories')->whereNull('parent_id')->paginate(PAGINATE);
    }

	protected function getCategoryByIdQuery(string $id)
    {
        return DB::table('categories')->where('id' , '=' ,$id)->first();
    }

	protected function getActiveCategoryQuery()
    {
        return DB::table('categories')->where('status' , '=' ,1)->paginate(PAGINATE);;
    }

	protected function getInActiveCategoryQuery()
    {
        return DB::table('categories')->where('status' , '=' ,0)->paginate(PAGINATE);;
    }
}
