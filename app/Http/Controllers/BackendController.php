<?php

namespace App\Http\Controllers;

use App\Models\DosenAdmin;
use Auth;

class BackendController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public static function getNama()
    {
        // returns an instance of the authenticated user...
        $id = Auth::user()->id;

        return DosenAdmin::with('user')->firstWhere('user_id', $id)->nama;
    }
}
