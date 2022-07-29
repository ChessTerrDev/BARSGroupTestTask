<?php
declare(strict_types=1);

namespace BARSGroupTestTask\Controller;

interface DataInterface
{
    public function setData($data): void;
    public function getData();
}