<?php

namespace App\Services\CRMs\Contracts;

use App\Entities\Entity;

abstract class CrmEntityFactory
{
    public Entity $entity;

    public string $crmServiceName;

    /**
     * @throws \Exception
     */
    public function __construct(array $data)
    {
        $this->crmServiceName = $this->getServiceName($data);

        $this->entity = $this->getEntity($data);
    }
    abstract protected function getEntity(array $data): Entity;
    abstract public function getListener(): CrmEntityListenersInterface;
    abstract public function getTransformer(): CrmEntityTransformerInterface;

    /**
     * @throws \Exception
     */
    private function getServiceName($data): string
    {
        foreach ($data as $key => $value) {
            if ($key !== 'account')
                return $key;
        }

        throw new \Exception('Не смогли определить переданный тип сущности');
    }
}
