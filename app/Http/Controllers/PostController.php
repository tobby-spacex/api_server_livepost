<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Repositories\PostRepository;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
            // return Post::all();
        $pageSize = $request->page_size ?? 20;
        $posts    = Post::query()->paginate($pageSize);

        return PostResource::collection($posts);  
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request, PostRepository $repository)
    {
        // $created = DB::transaction(function () use ($request) {

        //     $created = Post::query()->create([
        //         'title' => $request->title,
        //         'body'  => $request->body,
        //     ]);

        //     if($userIds = $request->user_ids){
        //         $created->users()->sync($userIds);
        //     }
        //     return $created;
        // });
        
        $created = $repository->create($request->only([
            'title',
            'body',
            'user_ids'
        ]));

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
    public function update(UpdatePostRequest $request, Post $post, PostRepository $repository)
    {
        // $updated = $post->update([
        //     'title' => $request->title ?? $post->title,
        //     'body'  => $request->body ?? $post->body,
        // ]);

          // if(!$updated) {
          //     return new JsonResponse([
          //         'errors' => [
          //             'Failed to update model'
          //         ]
          //     ], 400);
          // }
        
        $post = $repository->update($post, $request->only([
            'title',
            'body',
            'user_ids',
        ]));

        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post, PostRepository $repository)
    {
        // $deleted = $post->forceDelete();

        // if(!$deleted){
        //     return new JsonResponse([
        //         'errors' => [
        //             'Failed to delete resource'
        //         ]
        //     ], 400);    
        // }
        
        $post = $repository->forceDelete($post);

        return new PostResource($post);
    }
}
