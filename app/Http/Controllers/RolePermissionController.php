<?php

namespace App\Http\Controllers;

use App\Http\Requests\RolePermissionRequest;
use App\Http\Resources\RolePermissionResource;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $roles = Role::query()
            ->when(!empty($request->search), function ($query) use ($request) {
                return $query->where('name', 'like', "%{$request->search}%");
            })
            ->paginate($request->per_page ?? 10);

        return responseJson(
            message: 'Roles retrieved successfully',
            data: RolePermissionResource::collection($roles)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RolePermissionRequest $request)
    {
        $role = Role::create([
            'name' => $request->name,
        ]);

        $role->givePermissionTo($request->permissions);

        return responseJson(
            message: 'Role created successfully',
            data: new RolePermissionResource($role)
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = Role::findOrFail($id);

        return responseJson(
            message: 'Role retrieved successfully',
            data: new RolePermissionResource($role)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RolePermissionRequest $request, string $id)
    {
        $role = Role::findOrFail($id);

        $role->update([
            'name' => $request->name,
        ]);

        $role->syncPermissions($request->permissions);

        return responseJson(
            message: 'Role updated successfully',
            data: new RolePermissionResource($role)
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);

        $role->delete();

        return responseJson(
            message: 'Role deleted successfully',
            data: new RolePermissionResource($role)
        );
    }
}
