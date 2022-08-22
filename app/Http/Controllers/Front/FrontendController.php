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


        $category = $request->category;

//        return $category;

        $catid = null;
        if (!empty($category)) {
            $data['category'] = Category::where('slug', $category)->firstOrFail();
            $catid = $data['category']->id;
        }
//        return $catid;

        $term = $request->term;

//        return $term;

        $blogs = Blog::where('status', 1)
            ->when($catid, function ($query, $catid) {
                return $query->where('category_id', $catid);
            })
            ->when($term, function ($query, $term) {
                return $query->where('title', 'like', '%'.$term.'%');
            })->simplePaginate(3);
            return response()->json([
                'success' => true,
                'massage' => '',
                'data' => $blogs
            ]);

    }

    public function blogdetails($slug) {

        $blog = Blog::where('slug', $slug)->firstOrFail();

        return response()->json([
            'success' => true,
            'massage' => '',
            'data' => $blog
        ]);
    }
}
