<?php

namespace App\Http\Controllers;

use App\User;
use App\Accesses;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AccessesController extends BaseController
{
    public function __construct()
    {
        $this->classe = Accesses::class;
    }

    public function roles()
    {
        return Role::all();
    }

    public function storeRoles(Request $request)
    {
        return Role::create(['name' => $request->role]);
    }

    public function destroyRoles(Request $request)
    {
        return Role::all();
    }

    public function permissions()
    {
        return Permission::all();
    }

    public function storePermissions(Request $request)
    {
        return Permission::create(['name' => $request->permission]);
    }

    public function destroyPermissions(Request $request)
    {
        return Permission::all();
    }

    // public function show(Request $request)
    // {
    //     print_r($request); die();
    //     $user = User::find($request->id);
    //     return $user->getAllRoles();
    // }
}
