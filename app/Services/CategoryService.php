<?php

namespace App\Services;

use App\Models\Category;
use App\Reposirtories\CategoryRepository;
use App\Utils\ImageUpload;
use Yajra\DataTables\DataTables;

class CategoryService
{
    public $categoryRepository;
    public function __construct(CategoryRepository $categoryRepository)
    {
         $this->categoryRepository = $categoryRepository;
    }

    public function getMainCategories(){
        return $this->categoryRepository->getMainCategory();
    }

    public function store($requestParam){
        if($requestParam['parent_id'] == null){
            $requestParam['parent_id'] = 0;
        }
        if (isset($requestParam['image'])){
            $requestParam['image'] = ImageUpload::uploadImage($requestParam['image']);
        }
      return $this->categoryRepository->store($requestParam);
    }

    public function getById($id, $childrenCount = false){
       return $this->categoryRepository->getById($id, $childrenCount);
    }

    public function update($id, $requestParam){
        $category = $this->categoryRepository->getById($id);
        $requestParam['parent_id'] = $requestParam['parent_id'] ?? 0;
        if (isset($requestParam['image'])){
            $requestParam['image'] = ImageUpload::uploadImage($requestParam['image']);
        }
        return $category->update($category,$requestParam);
    }

    public function delete($requestParam){
        return $this->categoryRepository->delete($requestParam);
    }


    public function dataTable(){
        $query = $this->categoryRepository->baseQuery(['parent']);
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                return $btn = '
                        <a href="' . Route('dashboard.categories.edit', $row->id) . '"  class="edit btn btn-success btn-sm" ><i class="fa fa-edit"></i></a>
                        <button type="button" id="deleteBtn"  data-id="' . $row->id . '" class="btn btn-danger mt-md-0 mt-2" data-bs-toggle="modal"
                        data-original-title="test" data-bs-target="#deletemodal"><i class="fa fa-trash"></i></button>';
            })
            ->addColumn('parent', function ($row){
                if($row->parent){
                    return '<div style="text-align: center;">' . $row->parent->name . '</div>';
                }else{
                    return '<div style="text-align: center;">main Category</div>';
                }
//                return ($row->parent ==0) ? '<div style="text-align: center;">Main Category</div>' : $row->parents->name;
            })
            ->addColumn('image', function($row){
                return '<img src="' . asset($row->image) . '" width="100px" height="100px">';
            })
            ->rawColumns(['parent', 'action', 'image'])
            ->make(true);
    }

    public function getAll(){
        return $this->categoryRepository->baseQuery(['child'])->get();
    }

}
