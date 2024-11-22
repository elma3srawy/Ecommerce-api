<?php

namespace App\Http\Controllers\Products;

use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Interfaces\Repository\ProductRepositoryInterface;

class ProductController extends Controller
{
    use ResponseTrait;

    public function __construct(private ProductRepositoryInterface $product){}
    public function store(ProductRequest $request)
    {
        $validated = $request->onStore();
        try {
            DB::beginTransaction();

            $this->product->store($validated);

            DB::commit();

            return $this->successResponse('Product created successfully');

        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorResponse(message:'Failed to create product', statusCode:500);
        }
    }

    public function update(ProductRequest $request)
    {
        $validated = $request->onUpdate();

        try {
            DB::beginTransaction();

            $this->product->update($validated);

            DB::commit();

            return $this->successResponse('Product updated successfully');

        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorResponse(message:'Failed to update product', statusCode:500);
        }
    }

    public function destroy(ProductRequest $request)
    {
        $validate = $request->onDelete();
        try {
            DB::beginTransaction();
            $this->product->delete($validate['product_id']);
            DB::commit();
            return $this->successResponse('Product deleted successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorResponse(message:'Failed to delete product', statusCode:500);
        }
    }

    public function getAllProducts()
    {
        return $this->successResponse(
            $this->product->getAllProducts()->resource,
            "Products retrieved successfully."
        );
    }
    public function getProductById(string $id)
    {
        if($product = $this->product->getProductById($id))
        {
            return $this->successResponse(
                $product,
                "Product retrieved successfully."
            );
        }
        return $this->successResponse(message:"No product found with the given ID.");
    }

    public function getPricingHistory(string $id)
    {
        return $this->successResponse(
            $this->product->getPricingHistory($id),
            "Product pricing history retrieved successfully."
        );
    }

    public function changePrice(ProductRequest $request)
    {
        $data = $request->onChangePrice();
        try {
            DB::beginTransaction();
            if($this->product->changePrice($data))
            {
                DB::commit();
                return $this->successResponse(message: 'Price has been successfully updated.');
            }
            return $this->errorResponse(message:'The old price is the same as the new price; no changes were made.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorResponse(message: 'Failed to change the price. Please try again later.');
        }

    }

    public function changeImage(ProductRequest $request)
    {
        $data = $request->onChangeImage();
        $this->product->changeImage($data);
        return $this->successResponse(message:'Image from product updated successfully');
    }
    
}
