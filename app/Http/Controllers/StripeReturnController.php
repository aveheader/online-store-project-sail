<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class StripeReturnController extends Controller
{
    public function success(Request $request): View
    {
        return view('payments.stripe-success', [
            'sessionId' => $request->query('session_id'),
        ]);
    }

    public function cancel(Request $request): View
    {
        return view('payments.stripe-cancel', [
            'sessionId' => $request->query('session_id'),
        ]);
    }
}
