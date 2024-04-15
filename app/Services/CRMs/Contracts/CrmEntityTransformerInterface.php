<?php

namespace App\Services\CRMs\Contracts;

use App\Entities\Entity;

interface CrmEntityTransformerInterface
{
    public function transform(array $data, string $action): array;
}
