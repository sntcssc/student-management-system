<?php

namespace App\Http\Controllers;

use App\Services\PermissionService;
use App\Http\Requests\Permission\CreatePermissionRequest;
use App\Http\Requests\Permission\UpdatePermissionRequest;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
        // $this->middleware('role:admin');
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'sort', 'direction']);
        $permissions = $this->permissionService->getAllPermissions($filters);
        return view('permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('permissions.create');
    }

    public function store(CreatePermissionRequest $request)
    {
        $this->permissionService->createPermission($request->validated());
        return redirect()->route('permissions.index')->with('message', 'Permission created successfully.');
    }

    public function edit($id)
    {
        $permission = $this->permissionService->find($id);
        return view('permissions.edit', compact('permission'));
    }

    public function update(UpdatePermissionRequest $request, $id)
    {
        $this->permissionService->updatePermission($id, $request->validated());
        return redirect()->route('permissions.index')->with('message', 'Permission updated successfully.');
    }

    public function destroy($id)
    {
        $this->permissionService->deletePermission($id);
        return redirect()->route('permissions.index')->with('message', 'Permission deleted successfully.');
    }
}