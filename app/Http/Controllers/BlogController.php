<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::id();
        $blogs = Blog::where('user_id',$user_id)->get();
        if ($blogs) {
            return response()->json([
                "success" => true,
                "message" => "Blogs List",
                "data" => $blogs
            ]);
        }
        else{
            return response()->json([
                'success' => false,
                'massage' => 'Blog Not Found'
            ],404);
        }
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
        $slug = Helper::make_slug($request->title);
        $blogs = Blog::select('slug')->get();
        $request->validate([
            'title' => [
                'required',
                'unique:blogs,title',
                'max:255',
                function ($attribute, $value, $fail) use ($slug, $blogs) {
                    foreach ($blogs as $blog) {
                        if ($blog->slug == $slug) {
                            return $fail('Title already taken!');
                        }
                    }
                }
            ],
            'status' => 'required',
            'contents' => 'required',
            'category_id.*' => 'required'
        ]);

        $blog = new Blog();
        $blog->user_id = Auth::id();
        $blog->title = $request->title;
        $blog->slug = $slug;
        $blog->image_id = $request->image_id;
        $blog->contents = $request->contents;
        $blog->status = $request->status;
        $blog->meta_keywords = $request->meta_keywords;
        $blog->meta_description = $request->meta_description;
        $blog->serial_number = $request->serial_number;
        $blog->save();

        $category = Category::find($request->category_ids);
        $blog->categories()->attach($category);

        if ($blog){

            if (isset($blog->image_id)){
                $image = Image::findorfail($request->image_id);
                $image->blog_id = $blog->id;
                $image->save();
            }

            return response()->json([
                'success' => true,
                'massage' => 'Blog create successfully!',
                'data' => $blog
            ]);
        }
        else{
            if (isset($blog->image_id)){
                $image = Image::findorfail($request->image_id);
                unlink("assets/front/img/blog/".$image->image_title);
                $image->delete();
            }
            return response()->json([
                'success' => false,
                'massage' => 'Blog Not Create and Delete image',
                'data' => $image
            ]);
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
        $user_id = Auth::id();
        $blog = Blog::where('user_id',$user_id)->find($id);
        if ($blog) {
            return response()->json([
                "success" => true,
                "message" => "Blog retrieved successfully.",
                "data" => $blog
            ]);
        }
        else{
            return response()->json([
                'success' => false,
                'massage' => 'Blog Not Found'
            ],404);
        }
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

        $slug = Helper::make_slug($request->title);
        $blogs  = Blog::select('slug')->get();
        $blog = Blog::findOrFail($id);

        $request->validate([
            'title' => [
                'required',
                'unique:blogs,title',
                'max:255',
                function ($attribute, $value, $fail) use ($slug, $blogs,$blog) {
                    foreach ($blogs as $blog_item) {
                        if ($blog != $slug){
                            if ($blog_item->slug == $slug) {
                                return $fail('Title already taken!');
                            }
                        }
                    }
                }
            ],
            'status' => 'required',
            'contents' => 'required',
            'category_id.*' => 'required'
        ]);

        $user_id = Auth::id();
        $blog = Blog::where('user_id',$user_id)->find($id);

        if ($blog){
            $blog->user_id = Auth::id();
            $blog->title = $request->title;
            $blog->slug = $slug;
            $blog->image_id = $request->image_id;
            $blog->contents = $request->contents;
            $blog->status = $request->status;
            $blog->meta_keywords = $request->meta_keywords;
            $blog->meta_description = $request->meta_description;
            $blog->serial_number = $request->serial_number;
            $blog->save();

            $category = Category::find($request->category_ids);
            $blog->categories()->attach($category);

            return response()->json([
                'success' => true,
                'massage' => 'Blog update successfully!',
                'data' => $blog
            ]);
        }
        else{
            return response()->json([
                'success' => false,
                'massage' => 'Blog Not Found'
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
        $user_id = Auth::id();
        $blog = Blog::where('user_id',$user_id)->find($id);

        if ($blog) {
            $blog->delete();
            return response()->json([
                "success" => true,
                "message" => "Blog delete successfully.",
                "data" => $blog
            ]);
        }
        else{
            return response()->json([
                'success' => false,
                'massage' => 'Blog Not Found'
            ],404);
        }
    }

}
