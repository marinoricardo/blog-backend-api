<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    private $category;
    public function __construct(Category $category)
    {
        $this->category = $category;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $categories = $this->category->paginate(2);
        return response()->json(['data' => $categories, 'message' => "Categories retrieved successfully"]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'slug' => 'required'
        ]);

        DB::beginTransaction();

        try {
            $categories = new Category();
            $categories->title = $request->title;
            $categories->slug = $request->slug;
            $categories->save();

            DB::commit();
            return response()->json(['message' => 'Categories saved successfully']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([$e]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $categories = $this->category->find($id);
        if (empty($categories)) {
            return response()->json(['message' => "Category not found"]);
        } else {
            return response()->json(['data' => $categories, 'message' => "Category retrieved successfully"]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'slug' => 'required'
        ]);

        DB::beginTransaction();

        try {
            $categories = $this->category->find($id);
            if (empty($categories)) {

                DB::rollBack();
                return response()->json(['message' => "Category not found"]);
            } else {
                $categories->title = $request->title;
                $categories->slug = $request->slug;
                $categories->update();

                DB::commit();
                return response()->json(['message' => 'Categories updated successfully']);
            }
            
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([$e]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $categories = $this->category->find($id);
            if (empty($categories)) {

                DB::rollBack();
                return response()->json(['message' => "Category not found"]);
            } else {
                $categories->delete();

                DB::commit();
                return response()->json(['message' => 'Category removed successfully']);
            }
            
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([$e]);
        }
    }
}
