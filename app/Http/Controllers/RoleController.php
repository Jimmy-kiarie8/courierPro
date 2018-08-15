<?php

namespace App\Http\Controllers;

use App\Role_user;
use App\Role;
use App\User;
use DB;
use Illuminate\Http\Request;

class RoleController extends Controller {
	
	public function getUsersRole() {
		$user_arr = json_decode(json_encode(User::where('branch_id', Auth::user()->branch_id)->get()), true);
		return $user_arr;

	}

	public function store(Request $request)
	{
		$role = new Role;
		$role->name = $request->name;
		$role->description = $request->description;
		$role->save();
		return $role;
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Role  $role
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Role_user $role_user, $id) {
		$role = Role::find($id);
		$role->name = $request->name;
		$role->description = $request->description;
		$role->save();
		return $role;
	}

	public function destroy(Role $role) {
		// return $role->id;
		Role::find($role->id)->delete();
	}
	
	public function getRoles()
	{
		return	Role::all();
	}
}
