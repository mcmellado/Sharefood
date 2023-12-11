<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {

        $users = User::orderBy('id')->get();
        return view('admin.panel_admin', compact('users'));
    }


    public function validar($id)
    {
        $user = User::find($id);
        $user->validacion = !$user->validacion; 
        $user->save();

        return redirect()->route('admin.panel_admin');
    }

    public function eliminar($id)
    {
        $user = User::find($id);
        $user->delete();

        return redirect()->route('admin.panel_admin');
    }
}
