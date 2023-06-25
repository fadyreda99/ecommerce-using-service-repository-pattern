<?php

namespace App\Reposirtories;

use App\Models\Product;
use App\Models\ProductImage;
use App\Utils\ImageUpload;

class ProductRepository implements RepositoryInterface
{
    public $product;
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function baseQuery($relations = [], $withCount = [])
    {
        $query = $this->product->select('*')->with($relations);
        foreach ($withCount as $key => $value){
            $query->withCount($value);
        }
        return $query;
    }

    public function getById($id)
    {
        return $this->product->where('id', $id)->firstOrFail();
    }

    public function store($requestParam)
    {
        $product = $this->product->create($requestParam);
        $images = $this->uploadMultipleImages($requestParam, $product);
//        ProductImage::insert($images);
        $product->images()->createMany($images);
        return $product;
    }

    private function uploadMultipleImages($requestParam, $product){
        $images = [];
        if(isset($requestParam['images'])){
            $i =0;
            foreach ($requestParam['images'] as $key => $value){
                $images[$i]['image'] = ImageUpload::uploadImage($value);
                $images[$i]['product_id'] = $product->id;
                $i++;
            }
        }
      return $images;
    }

    public function addColor($product, $requestParam){
        $product->productColor()->createMany($requestParam['colors']);
    }

    public function update($id, $requestParam)
    {

        $product = $this->getbyId($id);
        $product =($product->update($requestParam)); //by return instance after update
        if (isset($requestParam['images'])) {
            $product = $this->getbyId($id);
            $images = $this->uploadMultipleImages($requestParam, $product);
            $product->images()->createMany($images);
        }
        return $product;
    }

    public function delete($requestParam)
    {
        $product = $this->getbyId($requestParam['id']);
        return $product->delete();
    }
}
