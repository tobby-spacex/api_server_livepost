<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\CommentResource;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comments = Comment::query()->get();

        return CommentResource::collection($comments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCommentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCommentRequest $request)
    {
        $created = Comment::query()->create([
            'title' => $request->title,
            'body' => $request->body,
        ]);

        return new CommentResource($created);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        return new CommentResource($comment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCommentRequest  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $updated = $comment->update([
            'title' => $request->title ?? $comment->title,
            'body' => $request->body ?? $comment->body,
        ]);

        if(!$updated){
            return new JsonResponse([
                'error' => 'Failed to update resource.'
            ]);
        }

        return new CommentResource($comment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        $deleted = $comment->forceDelete();

        if(!$deleted){
            return new JsonResponse([
                'error' => 'Failed to delete resource.'
            ]);
        }
        return new JsonResponse([
            'data' => 'success',
        ]);
    }
}
