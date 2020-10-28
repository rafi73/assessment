<?php

namespace App\Http\Controllers;

use App\Models\sync;
use App\Services\InventoryService;
use App\Services\ProductService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Response;
use Storage;

class SyncController extends Controller
{
    /**
     * The Product Service instance.
     *
     * @var ProductService
     */
    protected $productService;

    /**
     * The Inventory Service instance.
     *
     * @var InventoryService
     */
    protected $inventoryService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ProductService $productService = null, InventoryService $inventoryService = null)
    {
        $this->productService = $productService;
        $this->inventoryService = $inventoryService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function productCreate(Request $request)
    {
        $product = $this->productService->create($request->all());
        Storage::disk('local')->put($mytime->toDateTimeString() . 'product-create.json', json_encode($request->all()));
        return Response::json($product, 200);
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
        $product = $this->productService->update($request->all());
        Storage::disk('local')->put($mytime->toDateTimeString() . 'product-update.json', json_encode($request->all()));
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
        $product = $this->productService->create($request->all());
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
        $products = $this->productService->getAll();
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
        $product = $this->productService->create([]);
        return Response::json($product, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\sync  $sync
     * @return \Illuminate\Http\Response
     */
    public function inventoryLevelUpdate(Request $request)
    {
        $mytime = Carbon::now();
        Storage::disk('local')->put($mytime->toDateTimeString() . 'inventory-level.json', json_encode($request->all()));
        $product = $this->inventoryService->update($request->all());
        return Response::json($product, 200);
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
