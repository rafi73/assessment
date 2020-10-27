<?php

namespace App\Contracts;

/*
 * Repository Interface
 */
interface SyncInterface
{
    public function getAll();

    public function find(int $id);

    public function create(array $request);

    public function update(array $request);

    public function delete(int $id);
}