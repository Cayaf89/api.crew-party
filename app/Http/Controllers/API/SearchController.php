<?php


namespace App\Http\Controllers\API;


use App\Http\Resources\UserSearch;
use App\Services\UserService;
use Illuminate\Http\Request;

class SearchController
{

    public function searchUser(Request $request) {
        $users = UserService::search($request->value);
        if ($request->has('crew_id')) {
            $crewId = $request->crew_id;
            $users  = $users->transform(function ($item) use ($crewId) {
                $item->is_in_crew = $item->isInCrew($crewId);
                return $item;
            });
        }
        return response()->json(UserSearch::collection($users));
    }

    public function searchCrew(Request $request) {
        $users = UserService::search($request->value);
        if ($request->has('crew_id')) {
            $crewId = $request->crew_id;
            $users  = $users->transform(function ($item) use ($crewId) {
                $item->is_in_crew = $item->isInCrew($crewId);
                return $item;
            });
        }
        return response()->json(UserSearch::collection($users));
    }

    public function searchEvent(Request $request) {

    }
}
