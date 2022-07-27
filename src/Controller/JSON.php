<?php
declare(strict_types=1);

namespace BARSGroupTestTask\Controller;

class JSON
{
    private $fname;
    public  $jsonObject;

    /**
     * Загрузить в файл и объект json
     * @param string $fname    Имя файла для загрузки
     */
    public function __construct($fname)
    {
        $this->load($fname);
    }

    /**
     * Загружает запрошенное имя файла, извлекает содержимое и устанавливает объект json
     */
    public function load($fname)
    {
        $this->fname = $fname;
        if (file_exists($this->fname)) {
            $contents = file_get_contents($this->fname);
            $this->jsonObject = json_decode($contents);
        } else {
            $this->jsonObject = json_decode("[]");
        }

        $data =& $this->jsonObject;
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


        return file_put_contents($this->fname, json_encode($this->jsonObject));
    }
}