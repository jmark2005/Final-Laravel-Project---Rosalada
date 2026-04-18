<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Changed from getUsers to index
    public function index(){
        $users = User::with('role', 'userStatus')->get();
        return response()->json(['users' => $users]);
    }

    // Changed from addUser to store
    public function store(Request $request){
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'role_id' => ['required', 'exists:roles,id'],
            'user_status_id' => ['required', 'exists:user_statuses,id'],
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'user_status_id' => $request->user_status_id,
        ]);

        return response()->json(['message' => 'User successfully created!', 'user' => $user], 201);
    }

    public function show($id)
    {
        $user = User::with('role', 'userStatus')->find($id);
        if (!$user) return response()->json(['message' => 'User not found!'], 404);

        return response()->json(['user' => $user]);
    }

	public function update(Request $request, $id) {
		// 1. Find the user
		$user = User::find($id);

		// 2. If not found, return the ID received to help debug
		if (!$user) {
			return response()->json([
				'message' => 'User not found!',
				'debug_id_received' => $id 
			], 404);
		}

		// 3. Validation (Crucial: ignore current user ID for email unique check)
		$request->validate([
			'first_name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'email', 'unique:users,email,' . $id],
			'role_id' => ['required', 'exists:roles,id'],
			'user_status_id' => ['required', 'exists:user_statuses,id'],
		]);

		// 4. Update
		$user->update($request->only([
			'first_name', 'middle_name', 'last_name', 'email', 'role_id', 'user_status_id'
		]));

		return response()->json([
			'message' => 'User successfully updated!',
			'user' => $user->load('role', 'userStatus') // Reload relationships for the response
		]);
	}

    // Changed from deleteUser to destroy
    public function destroy($id){
        $user = User::find($id);
        if(!$user) return response()->json(['message' => 'User not found!'], 404);

        $user->delete();
        return response()->json(['message' => 'User successfully deleted!']);
    }
}
