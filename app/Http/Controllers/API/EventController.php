<?php


namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Models\Crew;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Event $event
     *
     * @return \App\Http\Resources\Event
     */
    public function getEvent(Request $request, Event $event) {
        return new \App\Http\Resources\Event($event);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @param \App\Models\Crew $crew
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function createEvent(Request $request, Crew $crew) {
        $validator = Validator::make($request->all(), [
            'name'        => 'required_without_all:description,logo',
            'description' => 'required_without_all:name,logo',
            'cover'       => 'nullable|required_without_all:name,description|image',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        else {
            $eventParams = [
                'name'        => $request->name,
                'description' => $request->description,
            ];
            if ($request->cover) {
                $eventParams['cover'] = $request->cover;
            }
            $crew->events()->create($eventParams);
        }
        return response()->json([]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Event $event
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function updateEvent(Request $request, Event $event) {
        if (auth('api')->user()->id === $event->crew->owner_id) {
            if (!empty($event)) {
                $isUpdated = FALSE;
                if ($request->filled('name') && $event->name !== $request->name) {
                    $event->name = $request->name;
                    $isUpdated   = TRUE;
                }
                if ($request->filled('description') && $event->description !== $request->description) {
                    $event->description = $request->description;
                    $isUpdated          = TRUE;
                }
                if ($isUpdated) {
                    $event->save();
                }
            }
            else {
                return response()->json(['error' => 'L\'évènement n\'existe pas'], 400);
            }
            return response()->json([]);
        }
        else {
            return response()->json(['error' => 'Vous n\'êtes pas autorisé à modifier cet évènement'], 403);
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Event $event
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function deleteEvent(Request $request, Event $event) {
        if (auth('api')->user()->id === $event->crew->owner_id) {
            try {
                $event->delete();
                return response()->json([]);
            }
            catch (\Exception $e) {
                throw $e;
            }
        }
        else {
            return response()->json(['error' => 'Vous n\'êtes pas autorisé à modifier cet évènement'], 403);
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Event $event
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function setCover(Request $request, Event $event) {
        if (auth('api')->user()->id === $event->crew->owner_id) {
            $validator = Validator::make($request->all(), [
                'cover' => 'required|image',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $event->cover = $request->cover;
            $event->save();
            return response()->json(['cover' => $event->getCover()]);
        }
        else {
            return response()->json(['error' => 'Vous n\'êtes pas autorisé à modifier cet event'], 403);
        }
    }

}
