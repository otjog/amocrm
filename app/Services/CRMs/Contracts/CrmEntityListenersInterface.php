<?php

namespace App\Services\CRMs\Contracts;

interface CrmEntityListenersInterface
{
    public function create(array $newData): void;

    public function update(array $newData, array $oldData ): void;


}
