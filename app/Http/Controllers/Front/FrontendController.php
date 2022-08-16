<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    // Blog Page  Funtion
    public function blogs(Request $request){


        $categories = Category::where('status', 1)->orderBy('id', 'DESC')->get();

        $latestblogs = Blog::where('status', 1)->orderBy('id', 'DESC')->get();


        return response()->json([
            'success' => true,
            'massage' => '',
            'data' => $categories,$latestblogs
        ]);
    }

    public function blogSearch($search){

//        return $search;

        $blogs = Blog::where('title', 'like', '%'.$search.'%')->orderBy('id', 'DESC')->get();

        return response()->json([
            'success' => true,
            'massage' => '',
            'data' => $blogs
        ]);
    }


    public function blogdetails($slug) {

        $blog = Blog::where('slug', $slug)->firstOrFail();
//        $latestblogs = Blog::where('status', 1)->orderBy('id', 'DESC')->limit(4)->get();
//        $categories = Category::where('status', 1)->orderBy('id', 'DESC')->get();

        return response()->json([
            'success' => true,
            'massage' => '',
            'data' => $blog
        ]);
    }
}
