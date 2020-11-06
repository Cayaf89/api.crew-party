<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function setUser(Request $request, User $user) {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname'  => 'required',
            'username'  => 'required',
            'email'     => 'required|email',
            'logo'      => 'nullable|image',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user->firstname = $request->firstname;
        $user->lastname  = $request->lastname;
        $user->username  = $request->username;
        $user->email     = $request->email;
        if ($request->filled('logo')) {
            $user->logo     = $request->logo;
        }
        $user->save();
        return response()->json([]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function setLogo(Request $request, User $user) {
        $validator = Validator::make($request->all(), [
            'logo'      => 'image',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user->logo = $request->logo;
        $user->save();
        return response()->json(['logo' => $user->getLogo()]);
    }

}
