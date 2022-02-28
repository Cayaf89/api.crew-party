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
        $event->load([
                         'logo',
                         'crew',
                     ]);
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
            return response()->json(['error' => 'Vous n\'êtes pas autorisé à modifier cet évènement'], 401);
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
            return response()->json(['error' => 'Vous n\'êtes pas autorisé à modifier cet évènement'], 401);
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Event $event
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function setEventChoice(Request $request, Event $event) {
        $validator = Validator::make($request->all(), [
            'date' => 'date_format:d/m/Y|required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        /** @var \App\Models\User $auth */
        $auth = auth('api')->user();
        /** @var \App\Models\EventChoice $eventChoice */
        $eventChoice = $event->eventChoices()->firstOrCreate([
                                                                 'date' => $request->date,
                                                             ]);
        if ($eventChoice->users()
                        ->where('users.id', $auth->id)
                        ->doesntExist()) {
            $eventChoice->users()->attach($auth->id);
        }
        $eventChoice->is_user_choice = TRUE;
        return response()->json(new \App\Http\Resources\EventChoice($eventChoice));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Event $event
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws \Exception
     */
    public function getEventChoices(Request $request, Event $event) {
        /** @var \App\Models\User $auth */
        $auth         = auth('api')->user();
        $eventChoices = $event->eventChoices()
                              ->get()
                              ->transform(function ($item) use ($auth) {
                                  if ($item->users()
                                           ->where('users.id', $auth->id)
                                           ->exists()) {
                                      $item->is_user_choice = TRUE;
                                  }
                                  return $item;
                              });
        return \App\Http\Resources\EventChoice::collection($eventChoices);
    }

}
