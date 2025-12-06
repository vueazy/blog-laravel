<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Requests\TagRequest;
use App\Http\Resources\TagResource;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tags = Tag::query()
            ->when(!empty($request->search), function ($query) use ($request) {
                return $query->where('name', $request->search);
            })
            ->paginate($request->per_page ?? 10);

        return responseJson(
            message: 'Tags retrieved successfully',
            data: TagResource::collection($tags)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TagRequest $request)
    {
        $tag = Tag::create($request->validated());

        return responseJson(
            message: 'Tag created successfully',
            data: new TagResource($tag)
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tag = Tag::findOrFail($id);

        return responseJson(
            message: 'Tag retrieved successfully',
            data: new TagResource($tag)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TagRequest $request, string $id)
    {
        $tag = Tag::findOrFail($id);

        $tag->update($request->validated());

        return responseJson(
            message: 'Tag updated successfully',
            data: new TagResource($tag)
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tag = Tag::findOrFail($id);

        $tag->delete();

        return responseJson(
            message: 'Tag deleted successfully',
            data: new TagResource($tag)
        );
    }
}
