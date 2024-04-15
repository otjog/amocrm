<?php

namespace App\Services\Repositories\Contracts;

interface EntityRepositoryInterface
{
    public function getById(string $id);
    public function save(array $data):void;

    public function update(array $data):void;
}
