<?php
namespace BARSGroupTestTask\Controller;

require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use BARSGroupTestTask\Model\Entities\LPU;


class CRUDJsonTest extends TestCase
{
    public function testAddEntry()
    {
        $jsonPath = '/var/www/phpstorm/BARSGroupTestTask/tests/tmp/test.json';
        file_put_contents($jsonPath, '{"LPU": []}');

        //Генерируем псевдо-данные
        $testData = [
            "id" => str_pad((string)rand(0, pow(10, 7) - 1), 7, '0', STR_PAD_LEFT),
            "hid" => str_pad((string)rand(0, pow(10, 5) - 1), 5, '0', STR_PAD_LEFT),
            "full_name" => bin2hex(random_bytes(13)),
            "address" => bin2hex(random_bytes(25)),
            "phone" => str_pad((string)rand(0, pow(10, 10) - 1), 10, '0', STR_PAD_LEFT)
        ];

        $testLPU = new LPU();
        $testLPU->setFields($testData);

        $dataJson = new DataJson($jsonPath);
        $CRUDJson = new CRUDJson($dataJson);

        $CRUDJson->addEntry($testLPU);

        $rez = json_decode(file_get_contents($jsonPath), true);

        $this->assertNotEmpty($rez['LPU'][0]);
        $this->assertIsArray($rez['LPU'][0]);
        $entry = $rez['LPU'][0];
        $this->assertSame($testLPU->getId(), $entry['id']);
        $this->assertSame($testLPU->getHid(), $entry['hid']);
        $this->assertSame($testLPU->getFullName(), $entry['full_name']);
        $this->assertSame($testLPU->getAddress(), $entry['address']);
        $this->assertSame($testLPU->getPhone(), $entry['phone']);
    }

    public function testGetEntryById()
    {
        //Псевдоданные
        $testData = [
            "id" => str_pad((string)rand(0, pow(10, 7) - 1), 7, '0', STR_PAD_LEFT),
            "hid" => str_pad((string)rand(0, pow(10, 5) - 1), 5, '0', STR_PAD_LEFT),
            "full_name" => bin2hex(random_bytes(13)),
            "address" => bin2hex(random_bytes(25)),
            "phone" => str_pad((string)rand(0, pow(10, 10) - 1), 10, '0', STR_PAD_LEFT)
        ];
        // Пишем псевдоданные в json
        $jsonPath = '/var/www/phpstorm/BARSGroupTestTask/tests/tmp/test.json';
        file_put_contents($jsonPath, '{"LPU": ['.json_encode($testData).']}');

        // Проверяем
        $CRUDJson = new CRUDJson(
            new DataJson($jsonPath)
        );
        $rez = $CRUDJson->getEntryById($testData['id']);

        $this->assertIsInt($rez);
        $this->assertSame(0, $rez);
    }

    public function testGetAllEntries()
    {
        //Псевдоданные
        $testData = [
            "id" => str_pad((string)rand(0, pow(10, 7) - 1), 7, '0', STR_PAD_LEFT),
            "hid" => str_pad((string)rand(0, pow(10, 5) - 1), 5, '0', STR_PAD_LEFT),
            "full_name" => bin2hex(random_bytes(13)),
            "address" => bin2hex(random_bytes(25)),
            "phone" => str_pad((string)rand(0, pow(10, 10) - 1), 10, '0', STR_PAD_LEFT)
        ];
        // Пишем псевдоданные в json
        $jsonPath = '/var/www/phpstorm/BARSGroupTestTask/tests/tmp/test.json';
        file_put_contents($jsonPath, '{"LPU": ['.json_encode($testData).']}');

        // Проверяем
        $CRUDJson = new CRUDJson(
            new DataJson($jsonPath)
        );
        $rez = $CRUDJson->getAllEntries();

        $this->assertIsArray($rez);
        $this->assertIsArray($rez[0]);

        $testObj = (object)$testData;
        $this->assertSame($testObj->id, $rez[0]['id']);
        $this->assertSame($testObj->hid, $rez[0]['hid']);
        $this->assertSame($testObj->full_name, $rez[0]['full_name']);
        $this->assertSame($testObj->address, $rez[0]['address']);
        $this->assertSame($testObj->phone, $rez[0]['phone']);

    }

    public function testUpdateEntry()
    {
        //Псевдоданные
        $testData = [
            "id" => str_pad((string)rand(0, pow(10, 7) - 1), 7, '0', STR_PAD_LEFT),
            "hid" => str_pad((string)rand(0, pow(10, 5) - 1), 5, '0', STR_PAD_LEFT),
            "full_name" => bin2hex(random_bytes(13)),
            "address" => bin2hex(random_bytes(25)),
            "phone" => str_pad((string)rand(0, pow(10, 10) - 1), 10, '0', STR_PAD_LEFT)
        ];
        // Пишем псевдоданные в json
        $jsonPath = '/var/www/phpstorm/BARSGroupTestTask/tests/tmp/test.json';
        file_put_contents($jsonPath, '{"LPU": ['.json_encode($testData).']}');

        // Проверяем
        $CRUDJson = new CRUDJson(
            new DataJson($jsonPath)
        );
        $testLPU = new LPU();
        $testData["phone"] = str_pad((string)rand(0, pow(10, 10) - 1), 10, '0', STR_PAD_LEFT);
        $testData["address"] = bin2hex(random_bytes(25));
        $testLPU->setFields($testData);

        $rez = $CRUDJson->updateEntry($testLPU);

        $jsonData = file_get_contents($jsonPath);
        $this->assertSame('{"LPU":['.json_encode($testData).']}', $jsonData);
        $this->assertSame(true, $rez);

        $testLPU->setId(null);
        $rez = $CRUDJson->updateEntry($testLPU);
        $this->assertIsObject($rez);
        $this->assertInstanceOf('BARSGroupTestTask\Lib\Message', $rez);

        $testLPU->setId(
            str_pad((string)rand(0, pow(10, 7) - 1), 7, '0', STR_PAD_LEFT)
        );
        $rez = $CRUDJson->updateEntry($testLPU);

        $this->assertIsObject($rez);
        $this->assertInstanceOf('BARSGroupTestTask\Lib\Message', $rez);
    }

    public function testDeleteEntry()
    {
        //Псевдоданные
        $testData = [
            "id" => str_pad((string)rand(0, pow(10, 7) - 1), 7, '0', STR_PAD_LEFT),
            "hid" => str_pad((string)rand(0, pow(10, 5) - 1), 5, '0', STR_PAD_LEFT),
            "full_name" => bin2hex(random_bytes(13)),
            "address" => bin2hex(random_bytes(25)),
            "phone" => str_pad((string)rand(0, pow(10, 10) - 1), 10, '0', STR_PAD_LEFT)
        ];
        // Пишем псевдоданные в json
        $jsonPath = '/var/www/phpstorm/BARSGroupTestTask/tests/tmp/test.json';
        file_put_contents($jsonPath, '{"LPU": ['.json_encode($testData).']}');

        // Проверяем
        $CRUDJson = new CRUDJson(
            new DataJson($jsonPath)
        );
        $testLPU = new LPU();
        $testLPU->setFields($testData);

        $rez = $CRUDJson->deleteEntry($testLPU);

        $jsonData = file_get_contents($jsonPath);
        $this->assertSame('{"LPU":[]}', $jsonData);
        $this->assertSame(true, $rez);

        $rez = $CRUDJson->deleteEntry($testLPU);
        $this->assertIsObject($rez);
        $this->assertInstanceOf('BARSGroupTestTask\Lib\Message', $rez);
    }


}
