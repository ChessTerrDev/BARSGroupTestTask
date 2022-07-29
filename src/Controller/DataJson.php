<?php
declare(strict_types=1);

namespace BARSGroupTestTask\Controller;

class DataJson implements DataInterface
{
    private string $fname;
    public array  $jsonArray;

    /**
     * Загрузить в файл и объект json
     * @param string $fname    Имя файла для загрузки
     */
    public function __construct($fname)
    {
        $this->load($fname);
    }

    public function setData($data): void
    {
        $this->jsonArray = $data;
    }

    public function getData()
    {
        return $this->jsonArray;
    }

    /**
     * Загружает запрошенное имя файла, извлекает содержимое и устанавливает объект json
     */
    public function load($fname)
    {
        $this->fname = $fname;
        if (file_exists($this->fname)) {
            $contents = file_get_contents($this->fname);
            $this->jsonArray = json_decode($contents, true);
        } else {
            $this->jsonArray = json_decode("[]", true);
        }

        $data =& $this->jsonArray;
        return $data;
    }

    /**
     * Записывает измененный объект обратно в файл
     */
    public function save()
    {
        // Создает каталог, если это необходимо
        $info = pathinfo($this->fname);
        $dir  = $info['dirname'];

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        //var_dump($this->jsonObject);
        return file_put_contents($this->fname, json_encode($this->jsonArray));
    }
}