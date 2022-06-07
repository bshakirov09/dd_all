<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TermsAPIController extends Controller
{
    public function index(Request $request) {
        return response()->json(setting('terms'));
    }
}
