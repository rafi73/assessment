<?php

namespace App\Services;

use App\Contracts\SyncInterface;
use App\Exceptions\CredentialErrorException;
use App\Models\Inventory;
use Slince;

class InventoryService implements SyncInterface
{
    protected $client;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        try
        {
            $credential = new Slince\Shopify\PrivateAppCredential(env('SHOPIFY_API_KEY'), env('SHOPIFY_PASSWORD'), env('SHOPIFY_SHARED_SECRET'));
            $this->client = new Slince\Shopify\Client($credential, env('SHOPIFY_STORE_NAME'), ['metaCacheDir' => './tmp']);
        } catch (\Exception $exception) {
            throw new CredentialErrorException($exception);
        }
    }
    /**
     * Get all Products.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAll(): array
    {
        return $this->client->get('products');
    }

    /**
     * Create & store a new Product
     *
     * @param array $request
     * @return bool
     */
    public function create(array $request): bool
    {
        $master = $request['master'];
        $slave = $request['slave']['product'];

        if (count($master['variants']) != count($master['variants'])) {
            throw new CredentialErrorException('Master and slave store data count mismatched', 500);
        }

        for ($i = 0; $i < count($master['variants']); $i++) {
            $inventory = new Inventory;
            $inventory->master_store_inventory_item_id = $master['variants'][$i]['inventory_item_id'];
            $inventory->slave_store_inventory_item_id = $slave['variants'][$i]['inventory_item_id'];
            $inventory->save();
        }

        return true;
    }

    /**
     * Delete a Product by id
     *
     * @param int $id
     * @throws \App\Exceptions\ProductService\ProductNotFoundException
     * @throws \App\Exceptions\ProductService\ProductOwnerMismatchedException
     * @return bool
     */
    public function delete(int $id): bool
    {
    }

    /**
     * Update a Product.
     *
     * @param array $request
     * @param int $id
     * @throws \App\Exceptions\ProductService\ProductNotFoundException
     * @throws \App\Exceptions\ProductService\ProductOwnerMismatchedException
     * @return \App\Product
     */
    public function update(array $request)
    {
        
        $inventory = Inventory::where('master_store_inventory_item_id', $request['inventory_item_id'])->first();
        //dd($inventory);
        $slaveProduct = $this->client->getInventoryLevelManager()->adjust([
            'inventory_item_id' => $inventory->slave_store_product_id,
            'available_adjustment' => $request['available']
        ]);
        
        return $slaveProduct;
    }

    /**
     * Find a Product by id
     *
     * @param int $id
     * @throws \App\Exceptions\ProductService\ProductNotFoundException
     * @return \App\Product
     */
    public function find(int $id)
    {
    }
}
