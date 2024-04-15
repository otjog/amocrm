<?php

namespace App\Services\CRMs\AmoCRM\Listeners;


use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\NotesCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Models\NoteType\CommonNote;
use App\Services\CRMs\Contracts\CrmEntityListenersInterface;
use App\Utils\AmoCRM\ApiClient;

class EntityListener implements CrmEntityListenersInterface
{
    private AmoCRMApiClient $client;

    /**
     * @param string $entityService
     * @throws InvalidArgumentException
     */
    public function __construct(private string $entityService)
    {
        $this->client = ApiClient::make();
    }

    /**
     * @throws InvalidArgumentException
     * @throws AmoCRMApiException
     * @throws AmoCRMMissedTokenException
     * @throws AmoCRMoAuthApiException
     */
    public function create(array $newData): void
    {
        $notesText = "name: {$newData['name']}";
        $notesText .= ", date_create: " . date("Y-m-d H:i:s", $newData['date_create']);
        $notesText .= ", responsible_user: {$newData['responsible_user_id']}";

        $notesCollection = new NotesCollection();

        $commonNote = new CommonNote();
        $commonNote->setText($notesText);
        $commonNote->setEntityId($newData['id']);

        $notesCollection->add($commonNote);

        $contactNotesService = $this->client->notes($this->entityService);
        $contactNotesService->add($notesCollection);
    }

    /**
     * @throws InvalidArgumentException
     * @throws AmoCRMApiException
     * @throws AmoCRMMissedTokenException
     * @throws AmoCRMoAuthApiException
     */
    public function update(array $newData, array $oldData ): void
    {
        $result = $this->getDiffValues($newData, $oldData);

        $notesText = $this->getDiffString(...$result);

        $notesCollection = new NotesCollection();

        $commonNote = new CommonNote();
        $commonNote->setText($notesText);
        $commonNote->setEntityId($newData['id']);

        $notesCollection->add($commonNote);

        $contactNotesService = $this->client->notes($this->entityService);
        $contactNotesService->add($notesCollection);
    }

    private function getDiffValues(array $newData, array $oldData): array
    {
        foreach ($newData as $key => $value) {

            if(!isset($oldData[$key]))
                continue;

            if (is_array($value)) {

                $newData[$key] = array_values($value);
                $oldData[$key] = array_values($oldData[$key]);

                $indN = $indO = 0;
                $maxIndN = count($newData[$key]);
                $maxIndO = count($oldData[$key]);

                while ($indN < $maxIndN && $indO < $maxIndO) {

                    $newData[$key][$indN] = array_change_key_case($newData[$key][$indN]);
                    $oldData[$key][$indO] = array_change_key_case($oldData[$key][$indO]);

                    $comparison = $newData[$key][$indN]['id'] <=> $oldData[$key][$indO]['id'];

                    if($comparison < 0) {
                        $indN++;
                    } elseif ($comparison > 0) {
                        $indO++;
                    } else {

                        if ($newData[$key][$indN] === $oldData[$key][$indO])
                            unset($newData[$key][$indN]);

                        unset($oldData[$key][$indO]);

                        $indO++;
                        $indN++;
                    }
                }

            } else {

                if ($oldData[$key] === $value)
                    unset($newData[$key]);

                unset($oldData[$key]);
            }
        }

        return ['added' => $newData, 'removed' => $oldData];
    }

    private function getDiffString(array $added, array $removed): string
    {
        $notesText = '';

        foreach (['add' => $added, 'remove' => $removed] as $action => $data) {
            foreach ($data as $key => $value) {
                if (is_array($value)) {

                    foreach ($value as $subValue) {
                        $subValue = array_change_key_case($subValue);

                        $notesText .= "$action : $key = {$subValue['id']}";

                        if (isset($subValue['name']))
                            $notesText .= " name = {$subValue['name']}";
                        if (isset($subValue['values'])) {
                            foreach ($subValue['values'] as $itemValue) {
                                $notesText .= " value = {$itemValue['value']}";
                            }
                        }

                        $notesText .= ";\n";
                    }

                } else {

                    $match = explode('_', $key);

                    $end = end($match);

                    if ($end === 'at') {
                        $value = date("Y-m-d H:i:s", $value);
                    } elseif ($end === 'by') {
                        $value = "$value (+запрос к сущности по id)";
                    }


                    $notesText .= "$action : {$key} = {$value};\n";
                }
            }
        }

        return $notesText;
    }
}
