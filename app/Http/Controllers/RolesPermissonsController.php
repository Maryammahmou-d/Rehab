<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesPermissonsController extends Controller
{
    /**
     * Display Roles&PermissionS
     */
    public function index()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        return response()->json(['roles' => $roles, 'permissions' => $permissions]);
    }

    /**
     * Store a new Role.
     */

    public function store_role(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'role' => 'required|unique:roles,name',
            ]);
            $role = Role::create(['name' => $request->role, 'guard_name' => 'web']);
            return response()->json(['Role Created']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        }
    }
    /**
     * Store a New permission.
     */

    public function store_permission(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'permission' => 'required|unique:permissions,name',
            ]);
            $permission = Permission::create(['name' => $request->permission, 'guard_name' => 'web']);
            return response()->json(['Permission Created']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        }
    }
    /**
     * Assign permission To Role.
     */

    public function assign_permission(Request $request)
    {
        $validatedData = $request->validate([
            'role' => 'required|exists:roles,name',
            'permission' => 'required|exists:permissions,name',
        ]);

        $role = Role::findByName($validatedData['role'], 'web');
        $permission = Permission::findByName($validatedData['permission'], 'web');

        if (!$role) {
            return response()->json(['error' => 'Role not found'], 404);
        }

        if (!$permission) {
            return response()->json(['error' => 'Permission not found'], 404);
        }

        $role->givePermissionTo($permission);

        return response()->json(['message' => 'Permission assigned to role successfully']);
    }

    /**
     * Add role to user
     */

    public function assign_role(Request $request)
    {
        $validatedData = $request->validate([
            'role' => 'required|exists:roles,name',
            'user_id' => 'required|exists:users,id',
        ]);

        $role = Role::findByName($validatedData['role'], 'web');
        $user = User::find($validatedData['user_id']);

        if (!$role) {
            return response()->json(['error' => 'Role not found.'], 404);
        } else {
            $user->assignRole($role);
            return response()->json(['Role was added Successfully']);
        }
    }

    /**
     * Show Role.
     */
    public function edit($id)
    {
        $role = Role::find($id);
        if (!$role) {
            return response()->json(['error' => 'Role not found'], 404);
        }
        return response()->json(['role' => $role]);
    }

    /**
     * Show the permission.
     */
    public function edit_permission($id)
    {
        $permission = Permission::find($id);
        if (!$permission) {
            return response()->json(['error' => 'Permission not found'], 404);
        }
        return response()->json(['permission' => $permission]);
    }

    /**
     * Update Role.
     */
    public function update(Request $request, $id)
    {
        $role = Role::find($id);
        if (!$role) {
            return response()->json(['error' => 'Role not found'], 404);
        }

        $role->name = $request->name;
        $role->save();

        return response()->json(['role' => $role]);
    }

    /**
     * Update permission.
     */
    public function update_permission(Request $request, $id)
    {
        $permission = Permission::find($id);
        if (!$permission) {
            return response()->json(['error' => 'Permission not found'], 404);
        }

        $permission->name = $request->name;
        $permission->save();

        return response()->json(['permission' => $permission]);
    }

    /**
     * Delete Role.
     */
    public function destroy($id)
    {
        $role = Role::find($id);
        if (!$role) {
            return response()->json(['error' => 'Role not found'], 404);
        }
        $role->delete();
        return response()->json(['message' => 'Role deleted successfully']);
    }
    /**
     * Delete permission .
     */
    public function destroy_permission($id)
    {
        $permission = Permission::find($id);
        if (!$permission) {
            return response()->json(['error' => 'Permission not found'], 404);
        }
        $permission->delete();
        return response()->json(['message' => 'Permission deleted successfully']);
    }
}
