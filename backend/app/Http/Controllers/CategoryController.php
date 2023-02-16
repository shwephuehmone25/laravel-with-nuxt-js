<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display Listing of category.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        return $categories = Category::filter(request('search'))
            ->orderBy('id', 'DESC')->get();

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return response()->json($categories);
    }

    /**
     * Store a newly created category in storage.
     *
     * @param  \Illuminate\Http\CategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $categories = Category::create([
            'name' => $request->name,
        ]);

        return response([
            "message" => "Category created successfully",
            "data" => $categories,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::find($id);

        return response([
            "message" => "Category Lists",
            "data" => $category,
        ]);
    }

    /**
     * Store updated category in database.
     *
     * @param  \Illuminate\Http\CategoryRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $category->update([
            'name' => $request->name,
        ]);

        return response([
            "category" => $category,
            "message" => "Category updated successfully",
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json(
            ["category" => $category,
                "message" => 'Category deleted successfully'],
            200
        );
    }

    /**
     * Count all Categories
     *
     * @return \Illuminate\Http\Response
     */
    public function countCategory()
    {
        $categories = Category::all()->count();
        return $categories;
    }
}