<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = User::query()
            ->when(!empty($request->search), function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%");
            })
            ->paginate($request->per_page ?? 10);
        
        return responseJson(
            message: 'Users retrieved successfully',
            data: UserResource::collection($users)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $user = User::create($request->saveChanges());

        return responseJson(
            message: 'User created successfully',
            data: new UserResource($user)
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);

        return responseJson(
            message: 'User retrieved successfully',
            data: new UserResource($user)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, string $id)
    {
        $user = User::find($id);

        $user->update($request->saveChanges());

        return responseJson(
            message: 'User updated successfully',
            data: new UserResource($user)
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        $user->delete();

        if (Storage::exists("avatar/{$user->avatar}")) {
            Storage::delete("avatar/{$user->avatar}");
        }

        return responseJson(
            message: 'User deleted successfully',
            data: new UserResource($user)
        );
    }
}
