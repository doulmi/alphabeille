<?php

namespace App\Http\Controllers;

use App\SubError;
use Auth;
use Illuminate\Http\Request;

use App\Http\Requests;

class SubErrorController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \illuminate\http\request  $request
     * @return \illuminate\http\response
     */
    public function store(request $request) {
        $error = suberror::create([
            'line' => $request->get('line'),
            'subtitle' => str_replace('</span>', '', str_replace('<span>','', $request->get('subtitle'))),
            'translate' => $request->get('translate'),
            'video_id' => $request->get('video_id'),
            'user_id' => auth::id()
        ]);
        if($error) {
            return response()->json([
                'status' => 200
            ]);
        } else {
            return response()->json([
                'status' => 404
            ]);
        }
    }
}
