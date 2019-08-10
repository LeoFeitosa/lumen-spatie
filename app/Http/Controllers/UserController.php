<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends BaseController
{
    public function __construct()
    {
        $this->classe = User::class;
    }

    public function login(Request $request)
    {
        // CRIA REGRA
        // $role = Role::create(['name' => 'super-admin']);
        // $role = Role::create(['name' => 'admin']);
        // $role = Role::create(['name' => 'user']);

        // CRIA PERMISSAO
        // $permission = Permission::create(['name' => 'get']);
        // $permission = Permission::create(['name' => 'put']);
        // $permission = Permission::create(['name' => 'post']);
        // $permission = Permission::create(['name' => 'delete']);

        // VINCULA REGRA E PERMISSSAO
        // $role = Role::findById(1);
        // $permission = Permission::findById(3);
        // $role->givePermissionTo(1, 2, 3);

        // CRIA PERMISSAO
        // $permission = Permission::create(['name' => 'edit post']);

        // ADICIONA PERMISSAO AO USUARIO
        // $user = User::find(1); //id usuario
        // $user->givePermissionTo('get'); //nome da regra
        // $user->givePermissionTo('put'); //nome da regra
        // $user->givePermissionTo('post'); //nome da regra

        // ADICIONA REGRAS AO USUARIO
        // $user = User::find(1); //id usuario
        // $user->assignRole('super-admin'); //nome da permissao

        // retorna regras de um usuario
        // $user = User::find(1);
        // return $user->getAllPermissions();

        $rules = array(
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        );
        $messages = array(
            'required' => ':attribute é obrigatorio.',
            'email.email' => ':attribute é invlálido.',
            'password.min' => ':attribute precisa ter o minimo de 6 caracteres.',
        );
        $validator = \Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 409);
        }

        // $validator = \Validator::make($request->all(), [
        //     'email' => 'required|email|unique:users',
        //     'password' => 'required|min:5|confirmed']);

        // if ($validator->fails()) {
        //     return $validator->errors();
        // }

        // $this->validate($request, [
        //     'name'=>'required|max:120',
        //     'email'=>'required|email|unique:users',
        //     'password'=>'required|min:6|confirmed'
        // ]);

        $user = User::where('email', $request->email)->first();

        if (
            is_null($user)
            || !Hash::check($request->password, $user->password)
        ) {
            return response()->json(
                [
                    'error' => 'Usuário ou senha inválidos',
                ],
                401
            );
        }

        $data = $request->only('email', 'password');
        $token = JWTAuth::attempt($data);
        return $this->responseToken($token);
    }

    private function responseToken($token)
    {
        return $token ? ['token' => $token] : response()->json([
            'error' => 'Credenciais inválidas',
        ], 400);
    }

    public function store(Request $request)
    {
        $user = User::create($request->only('email', 'name', 'password'));

        $roles = $request['roles'];

        if (isset($roles)) {

            foreach ($roles as $role) {
                $role_r = Role::where('id', '=', $role)->firstOrFail();
                $user->assignRole($role_r); //Assigning role to user
            }
        }

        return response()->json('User successfully added.');
    }
}
