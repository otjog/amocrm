<?php

namespace App\Services\CRMs\AmoCRM\Entities;


use AmoCRM\Models\NoteType\CommonNote as AmoCRMCommonNote;

class CommonNote
{
    public function __construct(string $text, string $entityId, string $entityType)
    {
        $commonNote = new AmoCRMCommonNote();
        $commonNote->setText($text);
        $commonNote->setEntityId($entityId);

        return $commonNote;
    }

    public function create(): AmoCRMCommonNote
    {

    }

}
