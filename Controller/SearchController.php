<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $flag = true;
        $responseData = [];
        $responseStatus = 400;

        $query = $_GET["q"] ?? null;

        if (!$query) {
            $flag = false;
            $message = 'query cannot be empty.';
        } else if (strlen($query) < 3) {
            $flag = false;
            $message = 'query must be minimum 3 characters.';
        } else {
            $userModel = new Models\Search();
            if (filter_var($query, FILTER_VALIDATE_EMAIL)) {
                $responseData = $userModel->searchUsers($query, 1); // search in email field
            } elseif (is_numeric($query) && strlen($query) == 10) {
                $responseData = $userModel->searchUsers($query, 2); // search in mobile field
            } else {
                $responseData = $userModel->searchUsers($query, 3); // search in name field
            }
            $responseStatus = 200;
        }

        if (!$flag) {
            $responseData['error'] = $message;
        }

        return response()->json($responseData, $responseStatus);
    }

}
