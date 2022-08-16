<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blogs = Blog::all();
        return response()->json([
            "success" => true,
            "message" => "Blogs List",
            "data" => $blogs
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
        $slug = Helper::make_slug($request->title);
        $blogs = Blog::select('slug')->get();

        $request->validate([
            'image' => 'required|mimes:jpeg,jpg,png',
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
            'category_id' => 'required'
        ]);

        $blog = new Blog();

        if($request->hasFile('image')){

            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $image = time().rand().'.'.$extension;
            $file->move('assets/front/img/blog/', $image);

            $blog->image = $image;
        }

        $blog->category_id = $request->category_id;
        $blog->title = $request->title;
        $blog->slug = $slug;
        $blog->contents = $request->contents;
        $blog->status = $request->status;
        $blog->meta_keywords = $request->meta_keywords;
        $blog->meta_description = $request->meta_description;
        $blog->serial_number = $request->serial_number;
        $blog->save();

        return response()->json([
            'success' => true,
            'massage' => 'Blog create successfully!',
            'data' => $blog
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
        $blog = Blog::find($id);
        if (is_null($blog)) {
            return $this->sendError('Blog not found.');
        }
        return response()->json([
            "success" => true,
            "message" => "Blog retrieved successfully.",
            "data" => $blog
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

        $slug = Helper::make_slug($request->title);
        $blogs  = Blog::select('slug')->get();
        $blog = Blog::findOrFail($id);

        $request->validate([
            'image' => 'mimes:jpeg,jpg,png',
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
            'category_id' => 'required'
        ]);

        $blog = Blog::find($id);
//        return $blog;

        if ($blog){

            if($request->hasFile('image')){
                @unlink('assets/front/img/blog/'. $blog->image);

                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $image = time().rand().'.'.$extension;
                $file->move('assets/front/img/blog/', $image);

                $blog->image = $image;

            }

            $blog->category_id = $request->category_id;
            $blog->title = $request->title;
            $blog->slug = $slug;
            $blog->contents = $request->contents;
            $blog->status = $request->status;
            $blog->meta_keywords = $request->meta_keywords;
            $blog->meta_description = $request->meta_description;
            $blog->serial_number = $request->serial_number;
            $blog->save();

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
        $blog = Blog::find($id);
        $blog->delete();
        return response()->json([
            "success" => true,
            "message" => "Blog deleted successfully.",
            "data" => $blog
        ]);
    }

}
