<?php
declare(strict_types=1);

namespace BARSGroupTestTask\Controller;

use BARSGroupTestTask\Model\Entities\LPU;
use BARSGroupTestTask\Controller\Message;

class CRUDJson extends AbstractCRUD
{
    public function __construct(DataJson $data)
    {
        parent::__construct($data);
    }

    /**
     * Добавляет запись в json файл или если таковая уже есть, обновляет её
     * @param \BARSGroupTestTask\Model\Entities\LPU $lpu
     * @return void
     */
    public function addEntry(LPU $lpu):void
    {
        $emptyEntry = $this->getEntryById($lpu->getId());
        if (!empty($lpu->getId()) && $emptyEntry)
        {
            $this->replaceEntry($emptyEntry, (object)$lpu->getAllFields());
        }

        $this->data->jsonObject->LPU[] = (object)$lpu->getAllFields();
        $this->data->save();
    }

    /**
     * Возвращает все записи в виде массива
     * @return array
     */
    public function getAllEntries(): array
    {
        return (array)$this->data->jsonObject->LPU;
    }

    /**
     * Возвращает ключ массива, если запись есть.
     * @param string $id
     * @return int|false
     */
    public function getEntryById(string $id): int | false
    {
        foreach ($this->data->jsonObject->LPU as $key => $entry)
        {
            if ($entry->id == $id)
                return $key;
        }

        return false;
    }

    /**
     * Обновление записи в json файле, если такая запись есть
     * @param \BARSGroupTestTask\Model\Entities\LPU $lpu
     * @return bool|Message
     */
    public function updateEntry(LPU $lpu): bool | Message
    {
        if (empty($lpu->getId())) return false;

        foreach ($this->data->jsonObject->LPU as $key => $entry)
        {
            if ($entry->id == $lpu->getId()) {

                $this->replaceEntry($key, (object)$lpu->getAllFields());

                return true;
            }
        }

        return (new Message('Нет записи с таким ID'));
    }

    /**
     * Обновление записи в json файле по ключу массива
     * @param int $key
     * @param $obj
     * @return void
     */
    private function replaceEntry(int $key, $obj): void
    {
        $this->data->jsonObject->LPU[$key]  = $obj;
        $this->data->save();
    }

    /**
     * Удаление записи из json файла
     * @param \BARSGroupTestTask\Model\Entities\LPU $lpu
     * @return bool|Message
     */
    public function deleteEntry(LPU $lpu): bool|Message
    {
        $emptyEntry = $this->getEntryById($lpu->getId());
        if (!empty($lpu->getId()) && $emptyEntry)
        {
            unset($this->data->jsonObject->LPU[$emptyEntry]);
            $this->data->save();

            return true;
        }

        return (new Message('Нет записи с таким ID'));
    }
}