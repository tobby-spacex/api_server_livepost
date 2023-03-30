<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
          // return Post::all();
        $posts = Post::query()->get();

          // return new JsonResponse([
          //     'data' => $posts
          // ]);
        
        return PostResource::collection($posts);  
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request)
    {
        $created = DB::transaction(function () use ($request) {

            $created = Post::query()->create([
                'title' => $request->title,
                'body'  => $request->body,
            ]);

            if($userIds = $request->user_ids){
                $created->users()->sync($userIds);
            }
            return $created;
        });

        return new PostResource($created);
    }

      /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return PostResource
     */
    public function show(Post $post)
    {
          // return new JsonResponse([
          //     'data' => $post
          // ]);
        
          return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePostRequest  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $updated = $post->update([
            'title' => $request->title ?? $post->title,
            'body'  => $request->body ?? $post->body,
        ]);

        if(!$updated) {
            return new JsonResponse([
                'errors' => [
                    'Failed to update model'
                ]
            ], 400);
        }

        return new PostResource($updated);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $deleted = $post->forceDelete();

        if(!$deleted){
            return new JsonResponse([
                'errors' => [
                    'Failed to delete resource'
                ]
            ], 400);    
        }

        return new PostResource($deleted);
    }
}
