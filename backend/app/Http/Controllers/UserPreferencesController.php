<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserPreferencesRequest;
use Illuminate\Http\JsonResponse;

class UserPreferencesController extends Controller
{
    public function update(UserPreferencesRequest $request): JsonResponse
    {
        $user = $request->user('api');
        $user->preferences = $request->validated();
        $user->save();

        return response()->json([
            'preferences' => $user->preferences,
        ]);
    }
}
