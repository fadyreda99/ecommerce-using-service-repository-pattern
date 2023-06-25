<?php

namespace App\Reposirtories;

use App\Models\Category;

class CategoryRepository implements RepositoryInterface
{
    public $category;
    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function baseQuery($relations = []){
        $query = $this->category->select('*')->with($relations);
        return $query;
    }

    public function getMainCategory(){
        return $this->category->where('parent_id', 0)->get();
    }

    public function store($requestParam){
        return $this->category->create($requestParam);
    }

    public function getById($id, $childrenCount = false){

        $query = $this->category->where('id', $id);
        if($childrenCount){
            $query->withCount('child');
        }
        return $query->firstOrFail();
    }

    public function update($category, $requestParam){
        return $category->update($requestParam);
    }

    public function delete($requestParam){
        $category = $this->getById($requestParam['id']);
        return $category->delete();
    }
}
