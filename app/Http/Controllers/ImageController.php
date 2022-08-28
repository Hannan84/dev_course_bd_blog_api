<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

        $request->validate([
            'image_title' => 'required|mimes:jpeg,jpg,png'
        ]);

        $blog_image = new Image();

        if($request->hasFile('image_title')){

            $file = $request->file('image_title');
            $extension = $file->getClientOriginalExtension();
            $image = time().rand().'.'.$extension;
            $file->move('assets/front/img/blog/', $image);

            $blog_image->image_title = $image;
        }
        $blog_image->alt_text = $request->alt_text;
        $blog_image->save();

        return response()->json([
            'success' => true,
            'massage' => 'Image upload successfully!',
            'data' => $blog_image
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function show(Image $image)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function edit(Image $image)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Image $image)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Image  $image_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $image  = Image::find($id);
        unlink("assets/front/img/blog/".$image->image_title);
        Image::where("id", $image->id)->delete();

        return response()->json([
            'success' => true,
            'massage' => 'Image delete successfully!',
            'data' => $image
        ]);
    }
}
