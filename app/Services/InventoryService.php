<?php

namespace App\Services;

use App\Contracts\SyncInterface;
use App\Exceptions\CredentialErrorException;
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
     * @return \App\Product
     */
    public function create(array $request)
    {
        $product = $this->client->post('products', ['product' => $request]);

        return $product;
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
    public function update(array $request, int $id)
    {
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