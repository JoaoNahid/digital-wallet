<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\{JsonResponse, Request};

class UserSearchController extends Controller
{
    public function __invoke(Request $p_Request): JsonResponse {
        $v_Email = $p_Request->query('email');
        
        if (! $v_Email) {
            return response()->json(['user' => null]);
        }

        $v_User = User::where('email', $v_Email)
            ->where('id', '!=', $p_Request->user()->id)
            ->first(['id', 'name', 'email']);

        return response()->json(['user' => $v_User]);
    }
}
