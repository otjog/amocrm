<?php

namespace App\Services\CRMs\AmoCRM;

use App\Entities\Entity;

class EntityAdapter
{
    public static function getEntity(string $entityName): Entity
    {
        return match($entityName) {
            'contacts' => Entity::Contact,
            'leads' => Entity::Deal,
        };
    }

    public static function getServiceName(Entity $entity): string
    {
        return match($entity) {
            Entity::Contact => 'contacts',
            Entity::Deal => 'leads',
        };
    }

}
