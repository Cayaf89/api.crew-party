<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \App\Http\Resources\UserResource
     */
    public function getUser(Request $request) {
        return new UserResource($request->user());
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \App\Http\Resources\UserResource
     * @throws \Illuminate\Validation\ValidationException
     */
    public function setUser(Request $request) {
        Validator::make($request->all(), [
            'email' => 'email',
        ])->validate();

        /** @var \App\Models\User $user */
        $user = $request->user();
        if ($request->has('email')) {
            $user->email = $request->email;
        }
        if ($request->has('name')) {
            $user->name = $request->name;
        }
        $user->save();
        return new UserResource($user);
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Exception
     */
    public function setProfilePicture(Request $request) {
        Validator::make($request->all(), [
            'profile_picture' => 'image|max:5000|required'
        ])->validate();

        /** @var \App\Models\User $user */
        $user = $request->user();
        $user->setProfilePicture($request->profile_picture);
        $user->save();
        return response()->json([
                                    'profile_picture' => $user->getProfilePicture()
                                ]);
    }

}
