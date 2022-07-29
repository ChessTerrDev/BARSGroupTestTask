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





