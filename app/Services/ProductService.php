<?php

namespace App\Services;

use App\Contracts\SyncInterface;
use App\Exceptions\CredentialErrorException;
use App\Models\Product;
use Slince;

class ProductService implements SyncInterface
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
        $slaveProduct = $this->client->post('products', ['product' => $request]);

        $product = new Product;
        $product->master_store_product_id = $request['id'];
        $product->slave_store_product_id = $slaveProduct['product']['id'];
        $product->save();

        return $slaveProduct;
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
    public function update(array $request): array
    {
        $skus = [];
        foreach ($request['variants'] as $varient) {
            $skus[$varient['sku']] = $varient['price'];
        }

        $product = Product::where('master_store_product_id', $varient['product_id'])->firstOrFail();
        $slaveProduct = $this->client->get('products/' . $product->slave_store_product_id);

        foreach ($slaveProduct['product']['variants'] as $varient) {
            $slaveProduct = $this->client->getProductVariantManager()->update($varient['id'], [
                "price" => $skus[$varient['sku']],
            ]);
        }

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
