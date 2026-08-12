<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthenticateAndLogUser
{
    /**
     * Handle the custom authentication tracking process.
     */
    public function __invoke(Request $request): ?User
    {
        $user = User::where('email', $request->email)->first();

        // 1. Successful authentication condition
        if ($user && Hash::check($request->password, $user->password)) {
            Log::info("SUCCESSFUL LOGIN: Email [{$request->email}] from IP [{$request->ip()}]");
            return $user;
        }

        // 2. Failed authentication condition
        Log::warning("FAILED LOGIN ATTEMPT: Email [{$request->email}] from IP [{$request->ip()}]");
        return null;
    }
}
