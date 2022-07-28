<?php
declare(strict_types=1);

namespace BARSGroupTestTask\Controller;

use BARSGroupTestTask\lib\Message;
use BARSGroupTestTask\Model\Entities\LPU;

abstract class AbstractCRUD
{
    protected DataInterface $data;

    public function __construct(DataInterface $data)
    {
        $this->data = $data;
    }

    abstract public function addEntry(LPU $lpu);
    abstract public function getAllEntries(): array;
    abstract public function getEntryById(string $id): int | false;
    abstract public function updateEntry(LPU $lpu): bool|Message;
    abstract public function deleteEntry(LPU $lpu): bool|Message;
}