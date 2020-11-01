<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

class UserController extends Controller
{

    public function getUser(Request $request) {
        return $request->user();
    }

}
