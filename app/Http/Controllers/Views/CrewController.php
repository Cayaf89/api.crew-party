<?php


namespace App\Http\Controllers\Views;


use App\Http\Controllers\Controller;
use App\Models\Crew;
use Illuminate\Http\Request;

class CrewController extends Controller
{
    public function index(Request $request) {
        return view('crew.list.crew-list');
    }

    public function show(Request $request, Crew $crew) {
        return view('crew.show.crew-show', [
            'crew' => new \App\Http\Resources\Crew($crew)
        ]);
    }

    public function create(Request $request) {
        return view('crew.show.crew-show');
    }

}
