<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SubscriberController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'email' => 'required|email|unique:subscribers,email'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first('email')
            ], 400);
        }

        $subscriber = new Subscriber;
        $subscriber->email = $request->email;
        $subscriber->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Terima kasih telah berlangganan!'
        ]);
    }
}
