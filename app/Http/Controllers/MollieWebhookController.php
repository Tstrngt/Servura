<?php

namespace App\Http\Controllers;

use App\Services\MolliePaymentService;
use Illuminate\Http\Request;

class MollieWebhookController extends Controller
{
    public function __invoke(Request $request, MolliePaymentService $mollieService)
    {
        if (!$request->has('id')) {
            return response('', 400);
        }

        $mollieService->handleWebhook($request->input('id'));

        return response('', 200);
    }
}
