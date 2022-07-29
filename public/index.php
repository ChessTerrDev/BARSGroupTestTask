<?php
declare(strict_types=1);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require_once '../vendor/autoload.php';

use BARSGroupTestTask\Controller\DataJson;
use BARSGroupTestTask\Controller\CRUDJson;
use BARSGroupTestTask\Model\Entities\LPU;
use BARSGroupTestTask\Lib\OAuth;

$jsonPath = __DIR__ . '/../data/lpu.json';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Лечебно профилактические учреждения</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
</head>
<body>
<input id="authToken" type="hidden" name="token" value="<?=OAuth::generationToken()?>"/>
<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol id="bootstrap" viewBox="0 0 118 94">
        <title>Bootstrap</title>
        <path fill-rule="evenodd" clip-rule="evenodd"
              d="M24.509 0c-6.733 0-11.715 5.893-11.492 12.284.214 6.14-.064 14.092-2.066 20.577C8.943 39.365 5.547 43.485 0 44.014v5.972c5.547.529 8.943 4.649 10.951 11.153 2.002 6.485 2.28 14.437 2.066 20.577C12.794 88.106 17.776 94 24.51 94H93.5c6.733 0 11.714-5.893 11.491-12.284-.214-6.14.064-14.092 2.066-20.577 2.009-6.504 5.396-10.624 10.943-11.153v-5.972c-5.547-.529-8.934-4.649-10.943-11.153-2.002-6.484-2.28-14.437-2.066-20.577C105.214 5.894 100.233 0 93.5 0H24.508zM80 57.863C80 66.663 73.436 72 62.543 72H44a2 2 0 01-2-2V24a2 2 0 012-2h18.437c9.083 0 15.044 4.92 15.044 12.474 0 5.302-4.01 10.049-9.119 10.88v.277C75.317 46.394 80 51.21 80 57.863zM60.521 28.34H49.948v14.934h8.905c6.884 0 10.68-2.772 10.68-7.727 0-4.643-3.264-7.207-9.012-7.207zM49.948 49.2v16.458H60.91c7.167 0 10.964-2.876 10.964-8.281 0-5.406-3.903-8.178-11.425-8.178H49.948z"></path>
    </symbol>
</svg>
<main>
    <div class="container">
        <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
            <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
                <svg class="bi me-2" width="40" height="32">
                    <use xlink:href="#bootstrap"></use>
                </svg>
                <span class="fs-4">Лечебно профилактические учреждения</span>
            </a>
            <ul class="nav nav-pills">
                <li class="nav-item"><a href="#" class="nav-link active" aria-current="page" data-bs-toggle="modal"
                                        data-bs-target="#ModalCreate">Добавить запись</a></li>
            </ul>
        </header>
    </div>
    <div class="b-example-divider"></div>
    <div class="container">
        <div class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
            <table class="table table-striped table-sm">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Hid</th>
                    <th scope="col">Наименование</th>
                    <th scope="col">Адрес</th>
                    <th scope="col">Телефон</th>
                    <th scope="col">#</th>
                    <th scope="col">#</th>
                </tr>
                </thead>
                <tbody id="listLPUtbody">
                <?php
                $CRUDJson = new CRUDJson(
                    new DataJson($jsonPath)
                );
                $list = $CRUDJson->getAllEntries();
                $arrayHidNull = [];
                $arrayHidNotNull = [];
                foreach ($list as $item)
                {
                    if ($item['hid']) {
                        $arrayHidNotNull[(int)$item['id']] = $item;
                    } else {
                        $arrayHidNull[(int)$item['id']] = $item;
                    }
                }
                foreach ([...$arrayHidNull, ...$arrayHidNotNull] as $item) {
                    ?>
                    <tr id="id_<?= $item['id'] ?>">
                        <td class="id"><?= strip_tags((string)$item['id']) ?></td>
                        <td class="hid"><?= strip_tags((string)$item['hid']) ?></td>
                        <td class="full_name"><?= strip_tags((string)$item['full_name']) ?></td>
                        <td class="address"><?= strip_tags((string)$item['address']) ?></td>
                        <td class="phone"><?= strip_tags((string)$item['phone']) ?></td>
                        <td>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                    data-bs-target="#ModalEdit" data-id="<?= strip_tags((string)$item['id']) ?>"
                                    data-hid="<?= strip_tags((string)$item['hid']) ?>"
                                    data-full_name="<?= strip_tags((string)$item['full_name']) ?>"
                                    data-address="<?= strip_tags((string)$item['address']) ?>"
                                    data-phone="<?= strip_tags((string)$item['phone']) ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                     class="bi bi-pencil" viewBox="0 0 16 16">
                                    <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"></path>
                                </svg>
                            </button>
                        </td>
                        <td>
                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                                    data-bs-target="#ModalDeleted" data-bs-id="<?= $item['id'] ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                     class="bi bi-trash" viewBox="0 0 16 16">
                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"></path>
                                    <path fill-rule="evenodd"
                                          d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"></path>
                                </svg>
                            </button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
<div class="modal fade" id="ModalDeleted" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Удалить запись?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <form class="modal-form">
                <input type="hidden" name="delete" value=""/>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary" id="ButtonDeleted">Да, удалить</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalEdit" tabindex="-1" aria-labelledby="ModalEditLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalEditLabel">Редактирование записи</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" id="ModalEditForm">
                    <input type="hidden" name="edit" value="true"/>
                    <div class="row g-3">
                        <input type="hidden" name="id" value="0">
                        <div class="col-12">
                            <label for="Hid" class="form-label">Hid <span class="text-muted"></span></label>
                            <input type="text" class="form-control" name="hid" placeholder="10903">
                        </div>
                        <div class="col-12">
                            <label for="full_name" class="form-label">Наименование <span class="text-muted">(Обязательно)</span></label>
                            <input type="text" class="form-control" name="full_name" placeholder="ООО «ВОДОЛЕЙ»">
                            <div class="invalid-feedback">
                                Пожалуйста, введите наименование для отправки обновления.
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="address" class="form-label">Адрес <span class="text-muted">(Обязательно)</span></label>
                            <input type="text" class="form-control" name="address"
                                   placeholder="г. Казань, ул. Петербургская">
                            <div class="invalid-feedback">
                                Пожалуйста, введите адрес для отправки обновления.
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="phone" class="form-label">Телефон <span class="text-muted">(Обязательно)</span></label>
                            <input type="text" class="form-control" name="phone" placeholder="8 800 808 88 00">
                            <div class="invalid-feedback">
                                Пожалуйста, заполните поле телефон для отправки обновления.
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary" id="ButtonEdit">Сохранить</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalCreate" tabindex="-1" aria-labelledby="ModalCreateLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalCreateLabel">Добавление записи</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" id="ModalCreateForm">
                    <input type="hidden" name="create" value="true"/>
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="id" class="form-label">Id <span class="text-muted"></span></label>
                            <input type="text" class="form-control" name="id" placeholder="10900003">
                        </div>
                        <div class="col-12">
                            <label for="Hid" class="form-label">Hid <span class="text-muted"></span></label>
                            <input type="text" class="form-control" name="hid" placeholder="10903">
                        </div>
                        <div class="col-12">
                            <label for="full_name" class="form-label">Наименование <span class="text-muted">(Обязательно)</span></label>
                            <input type="text" class="form-control" name="full_name" placeholder="ООО «ВОДОЛЕЙ»">
                            <div class="invalid-feedback">
                                Пожалуйста, введите наименование для отправки обновления.
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="address" class="form-label">Адрес <span class="text-muted">(Обязательно)</span></label>
                            <input type="text" class="form-control" name="address"
                                   placeholder="г. Казань, ул. Петербургская">
                            <div class="invalid-feedback">
                                Пожалуйста, введите адрес для отправки обновления.
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="phone" class="form-label">Телефон <span class="text-muted">(Обязательно)</span></label>
                            <input type="text" class="form-control" name="phone" placeholder="8 800 808 88 00">
                            <div class="invalid-feedback">
                                Пожалуйста, заполните поле телефон для отправки обновления.
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary" id="ButtonCreate">Добавить</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalErrorMessage" tabindex="-1" aria-labelledby="ModalErrorMessageLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ошибка!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body">
                <p id="ModalErrorMessageText"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
        crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script src="assets/scripts.js"></script>
</body>
</html>
