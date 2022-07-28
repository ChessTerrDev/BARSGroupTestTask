<?php
declare(strict_types=1);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require_once '../vendor/autoload.php';

use BARSGroupTestTask\Controller\DataJson;
use BARSGroupTestTask\Controller\CRUDJson;
use BARSGroupTestTask\Model\Entities\LPU;

$jsonPath = __DIR__ . '/../data/lpu.json';


$lpu = new LPU();
$lpu->setFields([
    "id" => "1060686",
    "hid" => "10903",
    "full_name" => "  Новое здaниe",
    "address" => "г. Казань, ул. Петербургская, д. 50",
    "phone" => "1234"
]);
$dataJson = new DataJson($jsonPath);
$CRUDJson = new CRUDJson($dataJson);

var_dump($CRUDJson);
var_dump($CRUDJson->getEntryById('10604483'));

// $CRUDJson->addEntry($lpu);

//$rez = $CRUDJson->updateEntry($lpu);
//if (is_object($rez)) echo $rez->getMessage();

//$rez = $CRUDJson->deleteEntry($lpu);
//if (is_object($rez)) echo $rez->getMessage();

$list = $CRUDJson->getAllEntries();
var_dump($list);


