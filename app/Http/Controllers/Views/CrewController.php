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
            'crew_id' => $crew->id
        ]);
    }

    public function create(Request $request) {
        return view('crew.show.crew-show');
    }

    public function createEvent(Request $request, Crew $crew) {
        return view('crew.show.event-show', [
            'crew_id' => $crew->id
        ]);
    }

}
