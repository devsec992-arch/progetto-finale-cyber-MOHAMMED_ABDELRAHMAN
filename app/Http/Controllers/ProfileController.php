<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    //
    public function show()
    {
        $user = auth()->user();
        return view('profile.show', compact('user'));
    }

public function edit()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }





    public function update(Request $request)
    {
        $user = Auth::user();  //  l'utente autenticato
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
         }

        $user->update();
        dd($user->is_admin); // Debug: Controlla il valore di is_admin dopo l'aggiornamento

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');
    }


  

}
