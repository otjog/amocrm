<?php

namespace App\Services\Repositories;

use App\Services\Repositories\Contracts\EntityRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class EntityRepository implements EntityRepositoryInterface
{

    private string $entityType;

    public function __construct(string $entityType)
    {
        $this->entityType = $entityType;
    }

    public function getById(string $id): array
    {
        $path = $this->getPath($id);

        if (Storage::exists($path)) {
            return json_decode(Storage::get($path), true);
        } else {
            throw new \Exception('Contact not exists');
        }

    }

    /**
     * @throws \Exception
     */
    public function save(array $data): void
    {
        $path = $this->getPath($data['id']);

        if (!Storage::exists($path)) {
            Storage::put($path, json_encode($data));
        } else {
            throw new \Exception('Contact already exists');
        }

    }

    /**
     * @throws \Exception
     */
    public function update(array $data): void
    {
        $path = $this->getPath($data['id']);

        if (Storage::exists($path)) {
            Storage::put($path, json_encode($data));
        } else {
            throw new \Exception('Contact not exists');
        }
    }

    private function getPath(string $entityId): string
    {
        return "$this->entityType/$entityId.json";
    }
}
