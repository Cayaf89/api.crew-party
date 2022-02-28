<?php


namespace App\Services;


use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserService
{

    public static function search($value, $crewId = NULL, $eventId = NULL) {
        $users = new User();
        if (!empty($value)) {
            $search          = strtolower($value);
            $search_unaccent = Str::slug($value, ' ');
            $users           = $users
                ->where(function ($query) use ($search, $search_unaccent) {
                    return $query->where(DB::raw("unaccent(concat(firstname, ' ', lastname))"), 'ilike', "'%" . $search_unaccent . "%'")
                                 ->orWhere(DB::raw("unaccent(concat(lastname, ' ', firstname))"), 'ilike', "'%" . $search_unaccent . "%'")
                                 ->orWhere(DB::raw('unaccent(firstname)'), 'ilike', "'%" . $search_unaccent . "%'")
                                 ->orWhere(DB::raw('unaccent(lastname)'), 'ilike', "'%" . $search_unaccent . "%'")
                                 ->orWhere(DB::raw('unaccent(username)'), 'ilike', "'%" . $search_unaccent . "%'")
                                 ->orWhere('email', 'ilike', "'%" . $search . "%'");
                })
                ->take(10);
        }

        return $users->get();
    }
}
