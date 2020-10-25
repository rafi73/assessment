<?php

namespace App\Http\Controllers;

use App\Models\sync;
use Illuminate\Http\Request;
use Response;
use Slince;

class SyncController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function productUpdate(Request $request)
    {
        // define('SHOPIFY_APP_SECRET', '*****************my_shopify_secret**********');

        // $hmac_header = $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];
        // $data = file_get_contents('php://input');
        // $verified = verify_webhook($data, $hmac_header);
        // error_log('Webhook verified: '.var_export($verified, true)); //check error.log to see the result*/

        if (Request::header('X-Shopify-Hmac-Sha256')) {
            $hmac_header = Request::header('X-Shopify-Hmac-Sha256');
            $data = file_get_contents('php://input');
            $calculated_hmac = base64_encode(hash_hmac('sha256', $data, Config::get('constants.SHOPIFY_APP_SECRET'), true));
            if ($hmac_header != $calculated_hmac) {
                return Response::json(array('error' => true, 'message' => "invalid secret"), 403);
            }
        } else {
            return Response::json(array('error' => true, 'message' => "no secret"), 403);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Response::json('test', 403);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\sync  $sync
     * @return \Illuminate\Http\Response
     */
    public function products()
    {
        $credential = new Slince\Shopify\PrivateAppCredential(env('SHOPIFY_API_KEY'), env('SHOPIFY_PASSWORD'), env('SHOPIFY_SHARED SECRET'));

        $client = new Slince\Shopify\Client($credential, env('SHOPIFY_STORE_NAME'), [
            'metaCacheDir' => './tmp',
        ]);

        $product = $client->get('products');
        return Response::json($product, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\sync  $sync
     * @return \Illuminate\Http\Response
     */
    public function storeProduct()
    {
        $credential = new Slince\Shopify\PrivateAppCredential(env('SHOPIFY_API_KEY'), env('SHOPIFY_PASSWORD'), env('SHOPIFY_SHARED SECRET'));

        $client = new Slince\Shopify\Client($credential, env('SHOPIFY_STORE_NAME'), [
            'metaCacheDir' => './tmp',
        ]);

        $product = $client->getProductManager()->create([
            "title" => "Burton Custom Freestyle 151",
            "body_html" => "<strong>Good snowboard!<\/strong>",
            "vendor" => "Burton",
            "product_type" => "Snowboard",
        ]);
        return Response::json($product,  200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\sync  $sync
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, sync $sync)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\sync  $sync
     * @return \Illuminate\Http\Response
     */
    public function destroy(sync $sync)
    {
        //
    }

    public function verify_webhook($data, $hmac_header)
    {
        $calculated_hmac = base64_encode(hash_hmac('sha256', $data, SHOPIFY_APP_SECRET, true));
        return hash_equals($hmac_header, $calculated_hmac);
    }
}
