<?php

namespace App\Services;

class CrewService
{

    /**
     * @param $crews
     * @param $filter
     *
     * @return mixed
     */
    public static function filterCrews($crews, $filter) {
        $crews->where(function ($q) use ($filter) {
            $q->orWhereRaw('LOWER(name) like ?', '%' . strtolower($filter) . '%')
              ->orWhereHas('owner', function ($query) use ($filter) {
                  $query->whereRaw('LOWER(username) like ?', ['%' . strtolower($filter) . '%']);
              });
        });
        return $crews;
    }

    /**
     * @param $crews
     * @param $filter
     *
     * @return mixed
     */
    public static function filterUsers($crews, $filter) {
        $crews->where(function ($q) use ($filter) {
            $q->orWhereRaw('LOWER(firstname) like ?', '%' . strtolower($filter) . '%')
              ->orWhereRaw('LOWER(lastname) like ?', '%' . strtolower($filter) . '%')
              ->orWhereRaw('LOWER(username) like ?', '%' . strtolower($filter) . '%')
              ->orWhereRaw('LOWER(email) like ?', '%' . strtolower($filter) . '%');
        });
        return $crews;
    }

    /**
     * @param $crews
     * @param $filter
     *
     * @return mixed
     */
    public static function filterEvents($crews, $filter) {
        $crews->where(function ($q) use ($filter) {
            $q->orWhereRaw('LOWER(name) like ?', '%' . strtolower($filter) . '%')
              ->orWhereRaw('LOWER(description) like ?', '%' . strtolower($filter) . '%');
        });
        return $crews;
    }
}
