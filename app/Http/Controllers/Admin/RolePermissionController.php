<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Notify;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:access management']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $roles = Role::all();
        return view('admin.access-management.role.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $permissions = Permission::all()->groupBy('group');
        return view('admin.access-management.role.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate(
            [
                'name' => ['required', 'max:50', 'unique:roles,name'],
            ]
        );

        // Create Role
        $role = Role::create(['guard_name' => 'admin', 'name' => $request->name]);

        // Assign permissions to the role
        $role->syncPermissions($request->permissions);

        Notify::createdNotification();

        return to_route('admin.role.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all()->groupBy('group');
        $rolePermissions = $role->permissions;
        $rolePermissions = $rolePermissions->pluck('name')->toArray();

        return view('admin.access-management.role.edit', compact('permissions', 'role', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $request->validate(
            [
                'name' => ['required', 'max:50', 'unique:roles,name,' . $id],
            ]
        );

        // Create Role
        $role = Role::findOrFail($id);
        $role->update(['guard_name' => 'admin', 'name' => $request->name]);

        // Assign permissions to the role
        $role->syncPermissions($request->permissions);

        Notify::updatedNotification();

        return to_route('admin.role.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): Response
    {
        try {
            Role::findOrFail($id)->delete();
            Notify::deletedNotification();
            return response(['message' => 'success'], 200);
        } catch (Exception $e) {
            logger($e);
            return response(['message' => 'Something went wrong, please try again'], 500);
        }
    }
}
