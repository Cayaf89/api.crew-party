<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function getUser(Request $request, User $user) {
        if (empty($user)) {
            $user = auth('api')->user();
        }
        $user->load('logo');
        return new \App\Http\Resources\User($user);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function setUser(Request $request, User $user) {
        $validator = Validator::make($request->all(), [
            'username'  => 'required',
            'email'     => 'required|email',
            'logo'      => 'nullable|image',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        if (!$user->exists) {
            $user = auth('api')->user();
        }
        $user->firstname = $request->firstname;
        $user->lastname  = $request->lastname;
        $user->username  = $request->username;
        $user->email     = $request->email;
        if ($request->filled('logo')) {
            $user->logo = $request->logo;
        }
        $user->save();
        return response(['user' => new \App\Http\Resources\User($user)]);
    }

}
