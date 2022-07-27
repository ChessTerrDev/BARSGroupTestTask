<?php
declare(strict_types=1);

namespace BARSGroupTestTask\Controller;

class DataJson extends JSON implements DataInterface
{
    public function __construct($fname)
    {
        parent::__construct($fname);
    }

    public function setData($jsonString): void
    {
        if (!is_string($jsonString))
            throw new \Exception('Несоответствие типа данных, передоваемое значение в DataJson->setData() должно быть строкой');
        $this->jsonString = $jsonString;
    }

    public function getData()
    {
        return false;
    }
}