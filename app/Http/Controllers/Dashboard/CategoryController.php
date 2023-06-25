<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Categories\CategoryDeleteRequest;
use App\Http\Requests\Dashboard\Categories\CategoryStoreRequest;
use App\Http\Requests\Dashboard\Categories\CategoryUpdateRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Yajra\DataTables\DataTables;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $categoriesService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoriesService = $categoryService;
    }

    public function index()
    {
        $mainCategories = $this->categoriesService->getMainCategories();
        return view('dashboard.categories.index', compact('mainCategories'));
    }

    public function getall(){
        return $this->categoriesService->dataTable();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryStoreRequest $request)
    {

        $this->categoriesService->store($request->validated());
        return redirect()->route('dashboard.categories.index')->with('success','category added');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = $this->categoriesService->getById($id,true);
        $mainCategories = $this->categoriesService->getMainCategories();
         return view('dashboard.categories.edit', compact('category','mainCategories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryUpdateRequest $request, $id)
    {
        $this->categoriesService->update($id, $request->validated());
        return redirect()->route('dashboard.categories.edit', $id)->with('success','category updated');

    }



    public function delete(CategoryDeleteRequest $request){
        $this->categoriesService->delete($request->validated());
        return redirect()->route('dashboard.categories.index');
    }
}
