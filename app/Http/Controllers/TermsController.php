<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TermsController extends Controller
{
    public function index(Request $request) {
        $term = setting('terms');

        if (!$term) $term = '';
        if ($request->post() && $request['content']) {
            setting(['terms' => $request['content']])->save();
            return redirect(url('terms'));
        }
        return view('settings.terms.edit', compact('term'));
    }
}
