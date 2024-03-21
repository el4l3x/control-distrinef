<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function perfil() {
        return view('profile.perfil');
    }
    
    public function perfilUpdate(Request $request) {
        $validated = $request->validate(
            [
                'nombre'    => 'required',
                'username'  => 'required|unique:users,username,'.Auth::user()->id,
                'clave'     => 'current_password|required',
                'password'  => [Password::min(8), 'nullable'],
            ],
            [
                'username.required' => 'El usuario es requerido',
                'username.unique'   => 'Este nombre de usuario ya está en uso',
            ]
        );

        try {
            DB::beginTransaction();
            
            $user = Auth::user();

            $clave = ($request->password != null) ? Hash::make($request->password) : $user->password;
            
            $user->forceFill([
                'name' => $request->nombre,
                'username' => $request->username,
                'password' => $clave,
            ])->save();

            if (isset($request->image)) {
                Storage::disk('public')->delete('img/users/'.$user->profile_photo_path);

                $name = $request->username.date('dmYGis').'.png';

                $path = $request->file('image')->storeAs('img/users', $name, 'public');

                $user->profile_photo_path = $name;
                $user->save();
            }

            DB::commit();

            return redirect(route('perfil'));
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

    }
}
