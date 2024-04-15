<?php

namespace App\Http\Controllers\Hooks;


use App\Http\Controllers\Controller;
use App\Services\CRMs\AmoCRM\AmoCrmEntity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AmoCrmController extends Controller
{
    /**
     * @throws \Exception
     */
    public function create(Request $request): void
    {

        $entityData = $request->all();

        $crmEntity = new AmoCrmEntity($entityData);

        $data = $crmEntity->getTransformer()->transform($entityData, 'add');

        $crmEntity->getRepository()->save($data);

        $crmEntity->getListener()->create($data);

    }

    /**
     * @throws \Exception
     */
    public function update(Request $request): void
    {
        $entityData = $request->all();

        $crmEntity = new AmoCrmEntity($entityData);

        $newData = $crmEntity->getTransformer()->transform($entityData, 'update');

        $oldData = $crmEntity->getRepository()->getById($newData['id']);

        $crmEntity->getListener()->update($newData, $oldData);

        $crmEntity->getRepository()->update($newData);
    }

}
