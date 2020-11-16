<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Event;
use App\Http\Resources\User;
use App\Models\Crew;
use App\Services\CrewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CrewController extends Controller
{

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Crew $crew
     *
     * @return \App\Http\Resources\Crew
     */
    public function getCrew(Request $request, Crew $crew) {
        return new \App\Http\Resources\Crew($crew);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function createCrew(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'        => 'required_without_all:description,logo',
            'description' => 'required_without_all:name,logo',
            'logo'        => 'nullable|required_without_all:name,description|image',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        else {
            /** @var \App\Models\User $user */
            $user       = auth('api')->user();
            $crewParams = [
                'name'        => $request->name,
                'description' => $request->description,
                'owner_id'    => $user->id,
            ];
            if ($request->logo) {
                $crewParams['logo'] = $request->logo;
            }
            $user->crews()->create($crewParams);
        }
        return response()->json([]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Crew $crew
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function updateCrew(Request $request, Crew $crew) {
        if (auth('api')->user()->id === $crew->owner_id) {
            if (!empty($crew)) {
                $isUpdated = FALSE;
                if ($request->filled('name') && $crew->name !== $request->name) {
                    $crew->name = $request->name;
                    $isUpdated  = TRUE;
                }
                if ($request->filled('description') && $crew->description !== $request->description) {
                    $crew->description = $request->description;
                    $isUpdated         = TRUE;
                }
                if ($isUpdated) {
                    $crew->save();
                }
            }
            else {
                return response()->json(['error' => 'Le crew n\'existe pas'], 400);
            }
            return response()->json([]);
        }
        else {
            return response()->json(['error' => 'Vous n\'êtes pas autorisé à modifier ce crew'], 403);
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Crew $crew
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function deleteCrew(Request $request, Crew $crew) {
        if (auth('api')->user()->id === $crew->owner_id) {
            try {
                $crew->delete();
                return response()->json([]);
            }
            catch (\Exception $e) {
                throw $e;
            }
        }
        else {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Crew $crew
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function setLogo(Request $request, Crew $crew) {
        if (auth('api')->user()->id === $crew->owner_id) {
            $validator = Validator::make($request->all(), [
                'logo' => 'required|image',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $crew->logo = $request->logo;
            $crew->save();
            return response()->json(['logo' => $crew->getLogo()]);
        }
        else {
            return response()->json(['error' => 'Vous n\'êtes pas autorisé à modifier ce crew'], 403);
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getListCrews(Request $request) {
        /** @var \App\Models\User $user */
        $user  = auth('api')->user();
        $crews = $user->crews()->orderBy('updated_at', 'DESC');
        if ($request->filled('filter')) {
            $crews = CrewService::filterCrews($crews, $request->filter);
        }
        $crews = $crews->with('owner');
        $crews = $crews->paginate(10);
        return \App\Http\Resources\Crew::collection($crews);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getListOtherCrews(Request $request) {
        /** @var \App\Models\User $user */
        $user  = auth('api')->user();
        $crews = $user->crews()
                      ->where('owner_id', '!=', $user->id)
                      ->orderBy('updated_at', 'DESC');
        if ($request->filled('filter')) {
            $crews = CrewService::filterCrews($crews, $request->filter);
        }
        $crews = $crews->with('owner');
        $crews = $crews->paginate(10);
        return \App\Http\Resources\Crew::collection($crews);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getListMyCrews(Request $request) {
        /** @var \App\Models\User $user */
        $user  = auth('api')->user();
        $crews = $user->myCrews()->orderBy('updated_at', 'DESC');
        if ($request->filled('filter')) {
            $crews = CrewService::filterCrews($crews, $request->filter);
        }
        $crews = $crews->with('owner');
        $crews = $crews->paginate(10);
        return \App\Http\Resources\Crew::collection($crews);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Crew $crew
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getCrewUsers(Request $request, Crew $crew) {
        /** @var \App\Models\User $user */
        $users = $crew->users()->orderBy('updated_at', 'DESC');
        if ($request->filled('filter')) {
            $users = CrewService::filterUsers($users, $request->filter);
        }
        $users = $users->paginate(10);
        return User::collection($users);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Crew $crew
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getCrewEvents(Request $request, Crew $crew) {
        /** @var \App\Models\User $user */
        $events = $crew->events()->orderBy('updated_at', 'DESC');
        if ($request->filled('filter')) {
            $events = CrewService::filterEvents($events, $request->filter);
        }
        $events = $events->paginate(10);
        return Event::collection($events);
    }

}
