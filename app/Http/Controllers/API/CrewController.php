<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Crew;
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
            'name'        => 'required',
            'description' => 'required',
            'logo'        => 'required|image|max:5000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        else {
            /** @var \App\Models\User $user */
            $user = auth('api')->user();
            $crew = Crew::create([
                                     'logo'        => $request->logo,
                                     'name'        => $request->name,
                                     'description' => $request->description,
                                     'owner_id'    => $user->id,
                                 ]);
            $user->crews()->save($crew);
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
            $validator = Validator::make($request->all(), [
                'name'        => 'required',
                'description' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            if (!empty($crew)) {
                $crew->name        = $request->name;
                $crew->description = $request->description;
                $crew->save();
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
     */
    public function setLogo(Request $request, Crew $crew) {
        if (auth('api')->user()->id === $crew->owner_id) {
            $validator = Validator::make($request->all(), [
                'logo' => 'required|image|max:5000',
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
        if ($request->filled('id')) {
            $crews = $crews->where('id', $request->id);
        }
        if ($request->filled('name')) {
            $crews->whereRaw('LOWER(name) like ?', '%' . strtolower($request->name) . '%');
        }
        if ($request->filled('owner_name')) {
            $crews->whereHas('owner', function ($query) use ($request) {
                $query->whereRaw('LOWER(username) like ?', ['%' . strtolower($request->owner_name) . '%']);
            });
        }
        $crews = $crews->with('owner');
        $crews = $crews->paginate(10);
        return \App\Http\Resources\Crew::collection($crews);
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

}
