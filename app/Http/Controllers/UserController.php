<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->paginate(10);
        return view('usuarios.usuarios', compact('users'));
    }

    public function create()
    {
        return view('usuarios.create');
    }

    public function store(Request $request)
    {
        // Validación de datos
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            
        ]);

        // Crear el usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Asignar roles si se han proporcionado en el formulario
        if ($request->roles) {
            $user->syncRoles($request->roles);
        }

        return redirect()->route('usuarios.usuarios')->with('success', 'Usuario creado exitosamente.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::pluck('name', 'id'); // Obtener todos los roles como un array asociativo de id => name
        $userRoles = $user->roles()->pluck('id')->toArray(); // Obtener los IDs de los roles asignados al usuario
    
        return view('usuarios.edit', compact('user', 'roles', 'userRoles'));
    }
    

    public function update(Request $request, $id)
    {
        // Validación de datos
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            // Puedes agregar más validaciones según sea necesario
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;

        // Actualizar la contraseña si se ha proporcionado en el formulario
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        // Actualizar roles si se han proporcionado en el formulario
        if ($request->roles) {
            $user->syncRoles($request->roles);
        }

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado exitosamente.');
    }
}
