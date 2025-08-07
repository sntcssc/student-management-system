<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        // $this->middleware('role:admin');
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'sort', 'direction']);
        $users = $this->userService->getAllUsers($filters);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(CreateUserRequest $request)
    {
        $this->userService->createUser($request->validated());
        return redirect()->route('users.index')->with('message', __('User created successfully.'));
    }

    public function edit($id)
    {
        $user = $this->userService->find($id);
        return view('users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $this->userService->updateUser($id, $request->validated());
        return redirect()->route('users.index')->with('message', __('User updated successfully.'));
    }

    public function destroy($id)
    {
        $this->userService->deleteUser($id);
        return redirect()->route('users.index')->with('message', __('User deleted successfully.'));
    }

    public function restore($id)
    {
        $this->userService->restoreUser($id);
        return redirect()->route('users.index')->with('message', __('User restored successfully.'));
    }

    public function exportPdf()
    {
        $users = $this->userService->getAllUsers([]);
        $pdf = Pdf::loadView('users', ['users' => $users]);
        return $pdf->download('users.pdf');
    }
}