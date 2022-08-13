<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryContoller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = Category::all();
        return response()->json([
            "success" => true,
            "message" => "Category List",
            "data" => $categories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $slug = Helper::make_slug($request->name);
        $blogs = Category::select('slug')->get();

        $request->validate([
            'name' => [
                'required',
                'unique:categories,name',
                'max:150',
                function ($attribute, $value, $fail) use ($slug, $blogs) {
                    foreach ($blogs as $blog) {
                        if ($blog->slug == $slug) {
                            return $fail('Title already taken!');
                        }
                    }
                }
            ],
            'status' => 'required',
            'serial_number' => 'required|numeric',
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->slug = $slug;
        $category->status = $request->status;
        $category->serial_number = $request->serial_number;
        $category->save();
        return response()->json([
            'success' => true,
            'massage' => 'Category create successfully!',
            'data' => $category
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
        if (is_null($category)) {
            return $this->sendError('Category not found.');
        }
        return response()->json([
            "success" => true,
            "message" => "Category retrieved successfully.",
            "data" => $category
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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

//        return $request;

        $slug = Helper::make_slug($request->name);
        $categories  = Category::select('slug')->get();
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => [
                'required',
                'unique:categories,name',
                'max:150',
                function ($attribute, $value, $fail) use ($slug, $categories,$category) {
                    foreach ($categories as $cate_item) {
                        if ($category != $slug){
                            if ($cate_item->slug == $slug) {
                                return $fail('Title already taken!');
                            }
                        }
                    }
                }
            ],
            'status' => 'required',
            'serial_number' => 'required|numeric',
        ]);

        $category = Category::find($id);
//        return $category;

        if ($category){
            $category->name = $request->name;
            $category->slug = $slug;
            $category->status = $request->status;
            $category->serial_number = $request->serial_number;
            $category->save();

            return response()->json([
                'success' => true,
                'massage' => 'Category update successfully!',
                'data' => $category
            ]);
        }
        else{
            return response()->json([
                'success' => false,
                'massage' => 'Category Not Found'
            ]);
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
        $category = Category::find($id);
        $category->delete();
        return response()->json([
            "success" => true,
            "message" => "Category deleted successfully.",
            "data" => $category
        ]);
    }
}
