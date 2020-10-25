<?php

namespace App\Services;

use App\Contracts\SyncInterface;
use App\Exceptions\CredentialErrorException;
use Slince;

class SyncService implements SyncInterface
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
            $credential = new Slince\Shopify\PrivateAppCredential(env('SHOPIFY_API_KEY'), env('SHOPIFY_PASSWORD'), env('SHOPIFY_SHARED SECRET'));
            $this->client = new Slince\Shopify\Client($credential, env('SHOPIFY_STORE_NAME'), ['metaCacheDir' => './tmp']);
        } catch (\Exception $exception) {
            throw new CredentialErrorException($exception);
        }

    }
    /**
     * Get all Robots.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAll(): array
    {
        return $this->client->get('products');
    }

    /**
     * Create & store a new robot
     *
     * @param array $request
     * @return \App\Robot
     */
    public function create(array $request)
    {
        return $this->client->getProductManager()->create([
            "title" => "Burton Custom Freestyle 151",
            "body_html" => "<strong>Good snowboard!<\/strong>",
            "vendor" => "Burton",
            "product_type" => "Snowboard",
        ]);
    }

    /**
     * Delete a Robot by id
     *
     * @param int $id
     * @throws \App\Exceptions\RobotService\RobotNotFoundException
     * @throws \App\Exceptions\RobotService\RobotOwnerMismatchedException
     * @return bool
     */
    public function delete(int $id): bool
    {
        // $robot = Robot::find($id);
        // if (!$robot) {
        //     throw new RobotNotFoundException();
        // }
        // if ($robot->user_id != Auth::id()) {
        //     throw new RobotOwnerMismatchedException();
        // }

        // return Robot::destroy($id);
    }

    /**
     * Update a Robot.
     *
     * @param array $request
     * @param int $id
     * @throws \App\Exceptions\RobotService\RobotNotFoundException
     * @throws \App\Exceptions\RobotService\RobotOwnerMismatchedException
     * @return \App\Robot
     */
    public function update(array $request, int $id)
    {
        // $robot = Robot::find($id);
        // if (!$robot) {
        //     throw new RobotNotFoundException();
        // }
        // if ($robot->user_id != Auth::id()) {
        //     throw new RobotOwnerMismatchedException();
        // }

        // return tap(Robot::findOrFail($id))->update($request)->fresh();
    }

    /**
     * Find a Robot by id
     *
     * @param int $id
     * @throws \App\Exceptions\RobotService\RobotNotFoundException
     * @return \App\Robot
     */
    public function find(int $id)
    {
        // $robot = Robot::find($id);
        // if (!$robot) {
        //     throw new RobotNotFoundException();
        // }
        // return $robot;
    }

    /**
     * Create & store robots from CSV
     *
     * @param array $request
     * @throws \App\Exceptions\RobotService\RobotBulkStructureException
     * @throws \App\Exceptions\RobotService\RobotBulkDataErrorException
     * @return bool
     */
    public function createBulk(array $request): bool
    {
        // $requiredStructure = ['name', 'power', 'speed', 'weight'];
        // $file = $request['file'];
        // $lines = explode("\n", file_get_contents($file));
        // $head = str_getcsv(array_shift($lines));
        // sort($head);

        // if ($head !== $requiredStructure) {
        //     throw new RobotBulkStructureException();
        // }

        // $robots = [];
        // for ($i = 0; $i < count($lines); $i++) {
        //     if (!strlen($lines[$i])) {
        //         continue;
        //     }

        //     $robot = array_combine($head, str_getcsv($lines[$i]));
        //     $robot['created_by'] = $robot['updated_by'] = $robot['user_id'] = Auth::id();
        //     $robot['created_at'] = $robot['updated_at'] = now();
        //     $robots[] = $robot;
        // }

        // try
        // {
        //     Robot::insert($robots);
        // } catch (QueryException $exception) {
        //     throw new RobotBulkDataErrorException();
        // }
        // return true;
    }
}
