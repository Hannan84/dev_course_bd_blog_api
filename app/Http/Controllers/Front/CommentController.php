<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$id)
    {

//        $id = $request->blog_id;

        $getComment = Blog::find($id)->comments;

        foreach ($getComment as $comment){

            $comment['replies'] = Comment::find($comment->id)->replies;
        }
        return response()->json([
            'success' => true,
            'massage' => 'Comment view',
            'comments' =>  $getComment,
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

        $id = $request->blog_id;

        $request->validate([
            'body' => ['required']
        ]);

        $comment = new Comment();
        $comment->body = $request->body;
        $comment->user_id = Auth::id();
        $blog = Blog::find($id);
        $blog->comments()->save($comment);

        return response()->json([
            'success' => true,
            'massage' => 'Comment accepted successfully!',
            'data' => $comment
        ]);
    }

    public function replyStore(Request $request)
    {

        $id = $request->blog_id;
        $comment_id = $request->comment_id;

        $request->validate([
            'body' => ['required']
        ]);

        $reply = new Comment();
        $reply->body = $request->body;
        $reply->user_id = Auth::id();
        $reply->parent_id = $comment_id;
        $blog = Blog::find($id);
        $blog->comments()->save($reply);

        return response()->json([
            'success' => true,
            'massage' => 'Reply accepted successfully!',
            'data' => $reply
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Front\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Front\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Front\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Front\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        //
    }
}
