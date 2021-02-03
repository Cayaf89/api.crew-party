<?php


namespace App\Http\Controllers\Views;


use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{

    public function index(Request $request) {
        return view('event.list.event-list');
    }

    public function show(Request $request, Event $event) {
        return view('event.show.event-show', [
            'event' => new \App\Http\Resources\Event($event)
        ]);
    }

    public function create(Request $request) {
        return view('event.show.event-show');
    }

}
