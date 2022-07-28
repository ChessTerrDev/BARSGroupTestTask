<?php

namespace BARSGroupTestTask\Model\Entities;

use PHPUnit\Framework\TestCase;

class LPUTest extends TestCase
{

    public function testGetAllFields()
    {
        $testData = [
            "id" => "1060686",
            "hid" => null,
            "full_name" => "Новое здaниe",
            "address" => "г. Казань, ул. Петербургская, д. 50",
            "phone" => null
        ];
        $lpu = new LPU();
        $lpu->setFields($testData);

        $this->assertSame($testData, $lpu->getAllFields());
    }


    public function testGetFields()
    {
        $testData = [
            "id" => "1060686",
            "hid" => null,
            "full_name" => "Новое здaниe",
            "address" => "г. Казань, ул. Петербургская, д. 50",
            "phone" => null
        ];
        $lpu = new LPU();
        $lpu->setFields($testData);

        $this->assertSame([
            "id" => "1060686",
            "full_name" => "Новое здaниe",
            "address" => "г. Казань, ул. Петербургская, д. 50",
        ], $lpu->getFields());
    }

    public function testSetFields()
    {
        $testData = [
            "id" => "1060686",
            "hid" => null,
            "full_name" => "Новое здaниe",
            "address" => "г. Казань, ул. Петербургская, д. 50",
            "phone" => null
        ];
        $lpu = new LPU();
        $lpu->setFields($testData);

        $this->assertSame("1060686", $lpu->getId());
        $this->assertSame(null, $lpu->getHid());
        $this->assertSame("Новое здaниe", $lpu->getFullName());
        $this->assertSame("г. Казань, ул. Петербургская, д. 50", $lpu->getAddress());
        $this->assertSame(null, $lpu->getPhone());
    }
}
