<?php
declare(strict_types=1);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
header('Content-Type: application/json; charset=utf-8');
require_once '../vendor/autoload.php';

use BARSGroupTestTask\Controller\{CRUDJson, DataJson};
use BARSGroupTestTask\Lib\Request;
use BARSGroupTestTask\Model\Entities\LPU;
use BARSGroupTestTask\Config;
use BARSGroupTestTask\Lib\OAuth;

if (!OAuth::verificationToken(OAuth::getTokenFromHeader()))
    exit(json_encode([
        'status' => false,
        'data' => [],
        'error' => 'Передан неверный или устаревший Токен!'
    ]));


function sendJson(mixed $result, LPU $lpu): string
{
    $error = false;
    if (is_object($result))
    {
        $error = $result->getMessage();
        $result = false;
    }


    return json_encode([
        'status' => $result,
        'data' => [...$lpu->getAllFields(), ...[OAuth::getTokenFromHeader()]],
        'error' => $error
    ]);

}
function setLPU():LPU
{
    $lpu = new LPU();
    $lpu->setId((string)Request::getPost('id'));
    $lpu->setHid((string)Request::getPost('hid', null));
    $lpu->setFullName((string)Request::getPost('full_name', ''));
    $lpu->setAddress((string)Request::getPost('address', ''));
    $lpu->setPhone((string)Request::getPost('phone', ''));

    return $lpu;
}


if (Request::getPost('delete'))
{
    $CRUDJson = new CRUDJson(
        new DataJson(Config::JSON_DATA_PATH)
    );

    $lpu = new LPU();
    $lpu->setId(Request::getPost('delete'));

    exit(
        sendJson(
            $CRUDJson->deleteEntry($lpu),
            $lpu
        )
    );
}

if (Request::getPost('edit'))
{
    $CRUDJson = new CRUDJson(
        new DataJson(Config::JSON_DATA_PATH)
    );
    $lpu = setLPU();

    exit(
        sendJson(
            $CRUDJson->updateEntry($lpu),
            $lpu
        )
    );
}

if (Request::getPost('create'))
{

    $CRUDJson = new CRUDJson(
        new DataJson(Config::JSON_DATA_PATH)
    );
    $lpu = setLPU();

    exit(
        sendJson(
            $CRUDJson->addEntry($lpu),
            $lpu
        )
    );
}



