<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Response;
use Storage;

class WebhookController extends Controller
{
    public function __construct()
    {
        define('SHOPIFY_APP_SECRET', env('WEBHOOK_SHARED_SECRET'));

        $hmac_header = $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];
        $data = file_get_contents('php://input');
        $verified = verify_webhook($data, $hmac_header);
        error_log('Webhook verified: ' . var_export($verified, true));
    }

    public function verify_webhook($data, $hmac_header)
    {
        $calculated_hmac = base64_encode(hash_hmac('sha256', $data, SHOPIFY_APP_SECRET, true));
        return hash_equals($hmac_header, $calculated_hmac);
    }

    /**
     * Catch webhook.
     *
     * @return \Illuminate\Http\Response
     */
    public function test(Request $request)
    {
        $mytime = Carbon::now();
        Storage::disk('local')->put('file.json', json_encode($request->all()));
        return Response::json($request->all(), 403);
    }
}
