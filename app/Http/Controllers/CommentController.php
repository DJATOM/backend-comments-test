<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\StoreRequest;
use App\Http\Requests\Comment\UpdateRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
    /**
     * Display a listing of the comments.
     *
     * @return ResourceCollection
     */
    public function index(): ResourceCollection
    {
        $comments = Comment::root()
            ->oldest()
            ->get();

        return CommentResource::collection($comments);
    }

    /**
     * Store a newly created comment in storage.
     *
     * @param  StoreRequest  $request
     * @return CommentResource
     */
    public function store(StoreRequest $request): CommentResource
    {
        $comment = Comment::create($request->validated());

        return new CommentResource($comment);
    }

    /**
     * Display the specified comment.
     *
     * @param  Comment  $comment
     * @return CommentResource
     */
    public function show(Comment $comment): CommentResource
    {
        return new CommentResource($comment);
    }

    /**
     * Update the specified comment in storage.
     *
     * @param  UpdateRequest  $request
     * @param  Comment  $comment
     * @return CommentResource
     */
    public function update(UpdateRequest $request, Comment $comment): CommentResource
    {
        $comment->update($request->validated());

        return new CommentResource($comment);
    }

    /**
     * Remove the specified comment from storage.
     *
     * @param  Comment  $comment
     * @return JsonResponse
     */
    public function destroy(Comment $comment): JsonResponse
    {
        $comment->delete();

        return response()->json(null, 204);
    }
}
