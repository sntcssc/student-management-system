<?php

namespace App\Http\Controllers;

use App\Services\RoleService;
use App\Http\Requests\Role\CreateRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use Illuminate\Http\Request;


class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
        // $this->middleware('role:admin');
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'sort', 'direction']);
        $roles = $this->roleService->getAllRoles($filters);
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        return view('roles.create');
    }

    public function store(CreateRoleRequest $request)
    {
        $this->roleService->createRole($request->validated());
        return redirect()->route('roles.index')->with('message', 'Role created successfully.');
    }

    public function edit($id)
    {
        $role = $this->roleService->find($id);
        return view('roles.edit', compact('role'));
    }

    public function update(UpdateRoleRequest $request, $id)
    {
        $this->roleService->updateRole($id, $request->validated());
        return redirect()->route('roles.index')->with('message', 'Role updated successfully.');
    }

    public function destroy($id)
    {
        $this->roleService->deleteRole($id);
        return redirect()->route('roles.index')->with('message', 'Role deleted successfully.');
    }
}