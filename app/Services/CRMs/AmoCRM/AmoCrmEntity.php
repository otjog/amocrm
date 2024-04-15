<?php

namespace App\Services\CRMs\AmoCRM;

use AmoCRM\Exceptions\InvalidArgumentException;
use App\Entities\Entity;
use App\Services\CRMs\AmoCRM\Transformers\EntityTransformer;
use App\Services\CRMs\AmoCRM\Listeners\EntityListener;
use App\Services\CRMs\Contracts\CrmEntityFactory;
use App\Services\CRMs\Contracts\CrmEntityTransformerInterface;
use App\Services\CRMs\Contracts\CrmEntityListenersInterface;
use App\Services\Repositories\EntityRepository;
use App\Services\Repositories\Contracts\EntityRepositoryInterface;

class AmoCrmEntity extends CrmEntityFactory
{
    /**
     * @throws InvalidArgumentException
     */
    public function getListener(): CrmEntityListenersInterface
    {
        return new EntityListener($this->crmServiceName);
    }

    public function getTransformer(): CrmEntityTransformerInterface
    {
        return new EntityTransformer($this->crmServiceName);
    }

    public function getRepository(): EntityRepositoryInterface
    {
        return new EntityRepository($this->entity->name);
    }

    protected function getEntity(array $data): Entity
    {
        return EntityAdapter::getEntity($this->crmServiceName);
    }
}
