<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use stdClass;

class Search extends Model
{

    /*$name : to store input in search bar
    query : Select data from 'table_name' where data like '%name%';
    $query = DB::table('table_name')->where('data', 'like', '%name%)->get();
    */
    public function searchUsers($q, $type, $page = 1)
    {
        $resultsPerPage = 30;
        try {
            $results = DB::table('user_login')
                ->join('corp_master', 'user_login.corp_id', '=', 'corp_master.corp_id')
                ->select('user_login.name', 'user_login.uid', 'corp_master.corp_name', 'user_login.email', 'user_login.mobile');

            switch ($type) {
                case 1:
                    $results->where('email', $q);
                    break;
                case 2:
                    $results->where('mobile', $q);
                    break;
                case 3:
                    $results->where('name', 'like', '%' . $q . '%');
                    break;
            }

            return $results
                ->offset(($page - 1) * $resultsPerPage)
                ->limit($resultsPerPage)
                ->get()
                ->toArray();
        } catch (\Throwable $e) {
            // log exception here
            return [];
        }
    }
}
