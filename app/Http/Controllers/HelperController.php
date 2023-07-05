<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelperController extends Controller
{
    public function getCurrentBalance($account_id)
    {
        $result = hp_current_balance($account_id); // Call the helper function
        return response()->json($result);
    }

    public function get_last_trnx_id($trnx_type_id)
    {
        $result = hp_last_trnx_custom_id($trnx_type_id); // Call the helper function
        return response()->json($result);
    }
}
