<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Models\Crew;
use App\Models\Event;
use App\Models\Image;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{


    /**
     * @param \Illuminate\Http\Request $request
     * @param $type
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function setLogo(Request $request, $type, $id) {
        $validator = Validator::make($request->all(), [
            'logo' => 'image',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $typeClass = Image::TYPES[$type];
        /** @var User|Crew|Event $typeObject */
        $typeObject = call_user_func_array([ $typeClass, "find" ], [$id]);
        if ($typeObject->logo()->exists()) {
            $typeObject->logo->delete();
        }

        /** @var Image $image */
        $image      = $typeObject->logo()->create([]);
        $image->url = $request->logo;
        $image->save();
        return response()->json(['logo' => $image->getUrl()]);
    }
}
