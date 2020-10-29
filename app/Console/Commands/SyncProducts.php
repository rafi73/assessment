<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Slince;

class SyncProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncing Master store products to slave store';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $masterCredential = new Slince\Shopify\PrivateAppCredential(env('MASTER_API_KEY'), env('MASTER_PASSWORD'), env('MASTER_SHARED_SECRET'));
        $masterClient = new Slince\Shopify\Client($masterCredential, env('MASTER_STORE_NAME'), ['metaCacheDir' => './tmp']);

        $slaveCredential = new Slince\Shopify\PrivateAppCredential(env('SHOPIFY_API_KEY'), env('SHOPIFY_PASSWORD'), env('SHOPIFY_SHARED_SECRET'));
        $slaveClient = new Slince\Shopify\Client($slaveCredential, env('SHOPIFY_STORE_NAME'), ['metaCacheDir' => './tmp']);

        $masterProducts = $masterClient->get('products');
        foreach ($masterProducts['products'] as $masterProduct) {
           
            $product = Product::where('master_store_product_id', $masterProduct['id'])->first();
            if ($product) {
                echo 'Removing product '.$masterProduct['title'].' from master store to slave store' .PHP_EOL;
                $slaveClient->getProductManager()->remove($product->slave_store_product_id);
                Product::where('master_store_product_id', $masterProduct['id'])->delete();
            }

            $slaveProduct = $slaveClient->post('products', ['product' => $masterProduct]);

            $product = new Product;
            $product->master_store_product_id = $masterProduct['id'];
            $product->slave_store_product_id = $slaveProduct['product']['id'];
            $product->save();

            echo 'Syncing product '.$masterProduct['title'].' from master store to slave store' .PHP_EOL;
        }
    }
}
