<?php


namespace App\Http\Controllers\Views;


use App\Http\Controllers\Controller;

class UserController extends Controller
{

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function myParameters() {
        return view('my-parameters.my-parameters');
    }
}
