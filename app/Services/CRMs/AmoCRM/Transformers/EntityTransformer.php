<?php

namespace App\Services\CRMs\AmoCRM\Transformers;

use App\Entities\Entity;
use App\Services\CRMs\Contracts\CrmEntityTransformerInterface;

class EntityTransformer implements CrmEntityTransformerInterface
{
    public function __construct(private string $crmServiceName)
    {

    }
    public function transform(array $data, string $action): array
    {
        return $data[$this->crmServiceName][$action][0];
    }
}
