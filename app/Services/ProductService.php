<?php

namespace App\Services;

use App\Reposirtories\ProductRepository;
use App\Utils\ImageUpload;
use Yajra\DataTables\DataTables;

class ProductService
{
    public $productRepository;
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getAll()
    {
        return $this->productRepository->baseQuery();
    }

    public function getById($id)
    {
        return $this->productRepository->getById($id);
    }

    public function store($requestParam)
    {
        if(isset($requestParam['image'])){
            $requestParam['image'] = ImageUpload::uploadImage($requestParam['image']);
        }

        if (isset($requestParam['colors'])){
            $requestParam['color'] = implode(',',$requestParam['colors']);
            unset($requestParam['colors']);
        }

        if (isset($requestParam['sizes'])){
            $requestParam['size'] = implode(',',$requestParam['sizes']);
            unset($requestParam['sizes']);
        }
       return $this->productRepository->store($requestParam);

    }

    public function update($id, $requestParam)
    {
        if(isset($requestParam['image'])){
            $requestParam['image'] = ImageUpload::uploadImage($requestParam['image']);
        }

        if (isset($requestParam['colors'])){
            $requestParam['color'] = implode(',',$requestParam['colors']);
            unset($requestParam['colors']);
        }

        if (isset($requestParam['sizes'])){
            $requestParam['size'] = implode(',',$requestParam['sizes']);
            unset($requestParam['sizes']);
        }
        return $this->productRepository->update($id, $requestParam);
    }

    public function delete($requestParam)
    {
        $this->productRepository->delete($requestParam);
    }

    public function datatable()
    {
        $query = $this->productRepository->baseQuery(relations:['category']);

        return DataTables::of($query)
            ->addColumn('action', function ($row) {
                return $btn = '
                <a href="' . Route('dashboard.products.edit', $row->id) . '"  class="edit btn btn-success btn-sm" ><i class="fa fa-edit"></i></a>

                <button type="button" id="deleteBtn"  data-id="' . $row->id . '" class="btn btn-danger mt-md-0 mt-2" data-bs-toggle="modal"
                data-original-title="test" data-bs-target="#deletemodal"><i class="fa fa-trash"></i></button>';
            })

            ->addColumn('category', function ($row) {
                return  $row->category->name ;
            })




            ->rawColumns(['action'])
            ->make(true);
    }

}
