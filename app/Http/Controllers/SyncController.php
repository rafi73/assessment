<?php

namespace App\Http\Controllers;

use App\Models\sync;
use App\Services\SyncService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Response;
use Storage;

class SyncController extends Controller
{
    /**
     * The robot Service instance.
     *
     * @var SyncService
     */
    protected $syncService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(SyncService $syncService = null)
    {
        $this->syncService = $syncService;
    }

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

        // if (Request::header('X-Shopify-Hmac-Sha256')) {
        //     $hmac_header = Request::header('X-Shopify-Hmac-Sha256');
        //     $data = file_get_contents('php://input');
        //     $calculated_hmac = base64_encode(hash_hmac('sha256', $data, Config::get('constants.SHOPIFY_APP_SECRET'), true));
        //     if ($hmac_header != $calculated_hmac) {
        //         return Response::json(array('error' => true, 'message' => "invalid secret"), 403);
        //     }
        // } else {
        //     return Response::json(array('error' => true, 'message' => "no secret"), 403);
        // }

        $product = $this->syncService->create($request->all());
        return Response::json($product, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function test(Request $request)
    {
        $mytime = Carbon::now();
        Storage::disk('local')->put('file.json', json_encode($request->all()));
        return Response::json($request->all(), 403);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product = $this->syncService->create($request->all());
        return Response::json($product, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\sync  $sync
     * @return \Illuminate\Http\Response
     */
    public function products()
    {
        $products = $this->syncService->getAll();
        return Response::json($products, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\sync  $sync
     * @return \Illuminate\Http\Response
     */
    public function storeProduct()
    {
        $product = $this->syncService->create([]);
        return Response::json($product, 200);
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
}
