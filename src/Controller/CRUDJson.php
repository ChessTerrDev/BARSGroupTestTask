<?php
declare(strict_types=1);

namespace BARSGroupTestTask\Controller;

use BARSGroupTestTask\Model\Entities\LPU;
use BARSGroupTestTask\Lib\Message;

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
    public function addEntry(LPU $lpu): bool | Message
    {
        if (empty($lpu->getId()))
            return (new Message('Ошибка! Нужно передать ID записи'));
        if (empty($lpu->getFullName()))
            return (new Message('Ошибка! Нужно указать Наименование записи'));
        if (empty($lpu->getAddress()))
            return (new Message('Ошибка! Нужно указать адрес ЛПУ'));

        $emptyEntry = $this->getEntryById($lpu->getId());
        if ($emptyEntry)
            $this->replaceEntry($emptyEntry, $lpu->getAllFields());


        $this->data->jsonArray['LPU'][] = $lpu->getAllFields();
        $this->data->save();

        return true;
    }

    /**
     * Возвращает все записи в виде массива
     * @return array
     */
    public function getAllEntries(): array
    {
        return (array)$this->data->jsonArray['LPU'];
    }

    /**
     * Возвращает ключ массива, если запись есть.
     * @param string $id
     * @return int|false
     */
    public function getEntryById(string $id): int | false
    {
        foreach ($this->data->jsonArray['LPU'] as $key => $entry)
        {
            if ($entry['id'] == $id)
                return (int)$key;
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
        if (empty($lpu->getId()))
            return (new Message('Ошибка! Нужно передать ID записи'));

        foreach ($this->data->jsonArray['LPU'] as $key => $entry)
        {
            if ($entry['id'] == $lpu->getId()) {

                $this->replaceEntry($key, $lpu->getAllFields());

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
        $this->data->jsonArray['LPU'][$key]  = $obj;
        $this->data->save();
    }

    /**
     * Удаление записи из json файла
     * @param \BARSGroupTestTask\Model\Entities\LPU $lpu
     * @return bool|Message
     */
    public function deleteEntry(LPU $lpu): bool|Message
    {
        if (empty($lpu->getId()))
            return (new Message('Ошибка! Нужно передать ID записи'));


        $emptyEntry = $this->getEntryById($lpu->getId());
        if ($emptyEntry !== false && is_int($emptyEntry))
        {
            unset($this->data->jsonArray['LPU'][$emptyEntry]);
            $this->data->jsonArray['LPU'] = array_values($this->data->jsonArray['LPU']);
            $this->data->save();

        } else {

            return (new Message('Нет записи с таким ID'));
        }

        return true;
    }
}