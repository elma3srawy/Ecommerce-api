<?php

namespace App\Repository;

use Carbon\Carbon;
use App\Traits\FileManager;
use Illuminate\Http\Request;
use App\Traits\ProductQueries;
use App\Events\ProductPriceChanged;
use App\Http\Resources\Products\ProductPricingHistoryResource;
use App\Http\Resources\Products\ProductResource;
use App\Interfaces\Repository\ProductRepositoryInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductRepository implements ProductRepositoryInterface
{
    use FileManager, ProductQueries;

    private Carbon $now;
    private Request $request;

    public function __construct()
    {
        $this->now = now();
        $this->request = request();
    }
    public function store($data)
    {
        $product = collect($data)->except('product_attributes')
        ->put('created_at', $this->now)
        ->put('updated_at', $this->now);

        if($this->request->hasFile('image'))
        {
            $path = $this->uploadFile($this->request->file('image') , 'products')->path;
            $product->put('image' , $path);
        }

        $id = $this->storeProductGetIdQuery($product->toArray());

       $this->storeAttributeProduct($id , $data);

    }
    private function storeAttributeProduct($id, $data)
    {
        collect($data)
            ->only('product_attributes')
            ->flatten(1)
            ->each(function($attributeProduct) use ($id){
                $attributeProduct['product_id'] = $id;
                $this->storeAttributeProductQuery($attributeProduct);
            });
    }
    public function update($data)
    {

        $product = collect($data)->except(['product_attributes', 'product_id'])
        ->put('updated_at', $this->now);

        $this->deleteFile($this->getImageByIdQuery($data['product_id']));

        if($this->request->hasFile('image'))
        {
            $path = $this->uploadFile($this->request->file('image') , 'products')->path;
            $product->put('image' , $path);
        }else{
            $product->put('image' , NULL);
        }

        $this->changePrice(['product_id' => $data['product_id'] , 'new_price' => $data['price']]);

        $product->except('price');

        $this->updateProductQuery($data["product_id"] , $product->toArray());

        $this->updateAttributeProduct($data);
    }
    private function updateAttributeProduct($data)
    {
        collect($data)
        ->only('product_attributes')
        ->flatten(1)
        ->each(function($attributeProduct)use($data){
            $product_id = $attributeProduct['product_id'] = $data['product_id'];
            $this->updateAttributeProductQuery($product_id , $attributeProduct);
        });
    }
    public function changePrice($data)
    {
        $old_price = $this->getPriceByProductIdQuery($data['product_id']);
        if(($data['old_price'] = $old_price) <> $data['new_price'])
        {
            event(new ProductPriceChanged($data));
            return true;
        }
        return false;
    }

    public function changeImage($data)
    {
        $path =  $this->getImageByIdQuery($data['product_id']);
        $this->deleteFile($path);
        $new_path = $this->uploadFile($this->request->file('image') , 'products')->path;
        $this->changeImageByIdQuery($data['product_id'] , $new_path);
    }
    public function delete($product_id)
    {
        $this->deleteFile($this->getImageByIdQuery($product_id));
        $this->deleteProductByProductIdQuery($product_id);
    }

	public function getAllProducts(): AnonymousResourceCollection
    {
        return ProductResource::collection($this->getAllProductsQuery());
    }

	public function getProductById(string $id)
    {
        return ($product = $this->getProductByIdQuery($id)) ? new ProductResource($product) : [];
    }
	public function getPricingHistory(string $id)
    {
        return ProductPricingHistoryResource::collection($this->getPricingHistoryByProductIdQuery($id));
    }

}
