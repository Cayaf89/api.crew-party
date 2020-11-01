<?php


namespace App\Http\Controllers\Views;


use App\Http\Controllers\Controller;

class WelcomeController extends Controller
{

    public function index() {
        return view('welcome');
    }
}
