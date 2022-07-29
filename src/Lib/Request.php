<?php
declare(strict_types=1);

namespace BARSGroupTestTask\Lib;

class Request
{
    /**
     * Получить значение для $key в массиве $array. Если значение не существует, будет возвращено значение по умолчанию.
     *
     * <code>
     * $array = array('fruit' => 'apple', 'baz' => 'quz');
     * // Return 'apple'
     * $value = Request::get($array, 'fruit');
     *
     * // Return NULL
     * $value = Request::get($array, 'foo');
     *
     * // Return 'bar'
     * $value = Request::get($array, 'foo', 'bar');
     * </code>
     * @param array $array array
     * @param string $key key
     * @param mixed|null $defaultValue default value
     * @param mixed|null $filter filter, e.g. 'str'|'string'|'strval', 'int'|'integer'|'intval', 'float'|'floatval', 'bool'|'boolean'|'boolval', 'trim'
     * @return mixed
     */
    static public function get(array $array, string $key, mixed $defaultValue = NULL, mixed $filter = NULL)
    {
        return self::_filter(
            array_key_exists($key, $array)
                ? $array[$key]
                : $defaultValue,
            $filter
        );
    }

    /**
     * Значение фильтра
     * @param mixed $value
     * @param mixed $filter filter, e.g. 'str'|'string'|'strval', 'int'|'integer'|'intval', 'float'|'floatval', 'bool'|'boolean'|'boolval', 'trim'
     * @return mixed
     * @throws \Exception
     */
    static protected function _filter(mixed $value, mixed $filter):mixed
    {
        if (!is_null($filter))
        {
            $value = match ($filter) {
                'str', 'string', 'strval' => is_scalar($value)
                    ? strval($value)
                    : '',
                'trim' => is_scalar($value)
                    ? trim($value)
                    : '',
                'int', 'integer', 'intval' => is_scalar($value)
                    ? intval($value)
                    : 0,
                'float', 'floatval' => is_scalar($value)
                    ? floatval($value)
                    : 0.0,
                'bool', 'boolean', 'boolval' => is_scalar($value) && ((function_exists('boolval')
                        ? boolval($value)
                        : (bool)$value
                    )),
                'array' => is_array($value)
                    ? $value
                    : array(),
                default => throw new \Exception("Request wrong {$filter} filter name"),
            };
        }

        return $value;
    }

    /**
     * Установите значение $defaultValue  по умолчанию для $key в массиве $array.
     * @param array $array array
     * @param mixed $key key
     * @param mixed|null $defaultValue value
     *
     * <code>
     * $array = array('fruit' => 'apple');
     * Request::set($array, 'baz', 'quz');
     * </code>
     */
    static public function set(array $array, mixed $key, mixed $defaultValue = NULL)
    {
        $array[$key] = $defaultValue;
    }

    /**
     * Получите значение для $key в массиве $_REQUEST. Если значение не существует, будет возвращено значение по умолчанию.
     * @param $key = key
     * @param $defaultValue = default value
     * @param mixed|null $filter filter, e.g. 'str'|'string'|'strval', 'int'|'integer'|'intval', 'float'|'floatval', 'bool'|'boolean'|'boolval', 'trim'
     * <code>
     * // Возвращаемое значение для 'foo' или NULL, если $key не существует
     * $value = Request::getRequest('foo');
     * </code>
     * <code>
     * // Возвращаемое значение для 'foo' или 'bar', если $key не существует
     * $value = Request::getRequest('foo', 'bar');
     * </code>
     * @return mixed
     */
    static public function getRequest($key, $defaultValue = NULL, mixed $filter = NULL): mixed
    {
        return self::get($_REQUEST, $key, $defaultValue, $filter);
    }

    /**
     * Получите значение для $key в массиве $_SERVER. Если значение не существует, будет возвращено значение по умолчанию.
     * @param $key = key
     * @param $defaultValue = default value
     * @param mixed|null $filter filter, e.g. 'str'|'string'|'strval', 'int'|'integer'|'intval', 'float'|'floatval', 'bool'|'boolean'|'boolval', 'trim'
     * <code>
     * // Возвращаемое значение для 'foo' или NULL, если $key не существует
     * $value = Request::getRequest('foo');
     * </code>
     * <code>
     * // Возвращаемое значение для 'foo' или 'bar', если $key не существует
     * $value = Request::getRequest('foo', 'bar');
     * </code>
     * @return mixed
     */
    static public function getServer($key, $defaultValue = NULL, mixed $filter = NULL): mixed
    {
        return self::get($_SERVER, $key, $defaultValue, $filter);
    }

    /**
     * Получите значение для $key в массиве $_POST. Если значение не существует, будет возвращено значение по умолчанию.
     * @param $key = key
     * @param $defaultValue = default value
     * @param mixed|null $filter filter, e.g. 'str'|'string'|'strval', 'int'|'integer'|'intval', 'float'|'floatval', 'bool'|'boolean'|'boolval', 'trim'
     * <code>
     * // Возвращаемое значение для 'foo' или NULL, если $key не существует
     * $value = Request::getPost('foo');
     * </code>
     * <code>
     * // Возвращаемое значение для 'foo' или 'bar', если $key не существует
     * $value = Request::getPost('foo', 'bar');
     * </code>
     * <code>
     * // Возвращаемое значение для 'foo' или 'bar', если $key не существует
     * $value = Request::getPost('foo', 'bar', 'trim');
     * </code>
     * @return mixed
     */
    static public function getPost($key, $defaultValue = NULL, mixed $filter = NULL): mixed
    {
        return self::get($_POST, $key, $defaultValue, $filter);
    }

    /**
     * Получите значение для $key в массиве $_GET. Если значение не существует, будет возвращено значение по умолчанию.
     *
     * <code>
     * // Возвращаемое значение для 'foo' или NULL, если $key не существует
     * $value = Request::getGet('foo');
     * </code>
     * <code>
     * // Возвращаемое значение для 'foo' или 'bar', если $key не существует
     * $value = Request::getGet('foo', 'bar');
     * </code>
     *
     * @param string $key = key
     * @param mixed|null $defaultValue = default value
     * @param mixed|null $filter filter, e.g. 'str'|'string'|'strval', 'int'|'integer'|'intval', 'float'|'floatval', 'bool'|'boolean'|'boolval', 'trim'
     * @return mixed
     */
    static public function getGet(string $key, mixed $defaultValue = NULL, mixed $filter = NULL): mixed
    {
        return self::get($_GET, $key, $defaultValue, $filter);
    }

    /**
     * Получаем значение для $key в массиве $_COOKIE. Если значение не существует, будет возвращено значение по умолчанию.
     *
     * <code>
     * // Возвращаемое значение для 'foo' или NULL, если $key не существует
     * $value = Request::getCookie('foo');
     * </code>
     * <code>
     * // Возвращаемое значение для 'foo' или 'bar', если $key не существует
     * $value = Request::getCookie('foo', 'bar');
     * </code>
     *
     * @param string $key = key
     * @param mixed|null $defaultValue = default value
     * @param mixed|null $filter filter, e.g. 'str'|'string'|'strval', 'int'|'integer'|'intval', 'float'|'floatval', 'bool'|'boolean'|'boolval', 'trim'
     * @return mixed
     */
    static public function getCookie(string $key, mixed $defaultValue = NULL, mixed $filter = NULL): mixed
    {
        return self::get($_COOKIE, $key, $defaultValue, $filter);
    }

    /**
     * Получите значение для $key в массиве $_SESSION. Если значение не существует, будет возвращено значение по умолчанию.
     *
     * <code>
     * // Возвращаемое значение для 'foo' или NULL, если $key не существует
     * $value = Request::getSession('foo');
     * </code>
     * <code>
     * // Возвращаемое значение для 'foo' или 'bar', если $key не существует
     * $value = Request::getSession('foo', 'bar');
     * </code>
     *
     * @param string $key key
     * @param mixed|null $defaultValue default value
     * @param mixed|null $filter filter, e.g. 'str'|'string'|'strval', 'int'|'integer'|'intval', 'float'|'floatval', 'bool'|'boolean'|'boolval', 'trim'
     * @return mixed
     */
    static public function getSession(string $key, mixed $defaultValue = NULL, mixed $filter = NULL): mixed
    {
        return isset($_SESSION)
            ? self::get($_SESSION, $key, $defaultValue, $filter)
            : $defaultValue;
    }

    /**
     * Получите значение для $key в массиве $_FILES. Если значение не существует, будет возвращено значение по умолчанию.
     * @param mixed $key = key
     * @param mixed|null $defaultValue = default value
     * <code>
     * // Возвращаемое значение для 'foo' или NULL, если $key не существует
     * $value = Request::getFiles('foo');
     * </code>
     * <code>
     * // Возвращаемое значение для 'foo' или array(), если $key не существует
     * $value = Request::getFiles('foo', array());
     * </code>
     * @return mixed
     */
    static public function getFiles(mixed $key, mixed $defaultValue = NULL): mixed
    {
        return self::get($_FILES, $key, $defaultValue);
    }

    /**
     * Преобразуйте все значения массива в int
     * @param $array array
     * <code>
     * $array = [1, 2, '3', 'a' => '321']
     * $array = Request::toInt($array);
     * var_dump($array);
     * </code>
     * @return array
     */
    static public function toInt(array $array): array
    {
        if (count($array))
            foreach ($array as $key => $value)
                $array[$key] = (int)($value);

        return $array;
    }

    /**
     * Объединение массивов $array1 и $array2. Если $array1 не является массивом, будет возвращен $array2
     * @param mixed $array1
     * @param array $array2
     * @return array
     */
    static public function union(mixed $array1, array $array2): array
    {
        return is_array($array1) ? $array1 + $array2 : $array2;
    }

    /**
     * Перемешивание элементов массива. Если передан hash - перемешивание будет осуществлено в соответствии с этим значением.
     *
     * @param array $array массив
     * @param int|null $hash числовое значение
     * <code>
     * <?php
     * $array = array('field1', 'field2', 'field3');
     * $hash = 10;
     *
     * $aNew = Request::randomShuffle($array, $hash);
     *
     * print_r($aNew);
     * ?>
     * </code>
     * @return array перемешанный массив
     */
    static public function randomShuffle(array $array, int $hash = NULL): array
    {
        $hash = is_null($hash)
            ? rand()
            : $hash;

        $n = count($array);

        for ($i = 0; $i < $n; $i++)
        {
            $position = $i + $hash % ($n - $i);
            $temp = $array[$i];
            $array[$i] = $array[$position];
            $array[$position] = $temp;
        }

        return $array;
    }

    /**
     * Возвращает случайное значение из массива
     *
     * @param array $array массив
     * <code>
     * <?php
     * $array = array('field1', 'field2', 'field3');
     *
     * $value = Request::randomValue($array);
     *
     * var_dump($value);
     * ?>
     * </code>
     * @return mixed
     */
    static public function randomValue(array $array): mixed
    {
        $key = array_rand($array);
        return $array[$key];
    }

    /**
     * Получить значение последнего элемента
     *
     * @param array $array массив
     * <code>
     * <?php
     * $array = array('field1', 'field2', 'field3');
     * $lastItem = Request::end($array);
     *
     * var_dump($lastItem);
     * ?>
     * </code>
     * @return mixed
     */
    static public function end(array $array): mixed
    {
        // array needs to be a reference
        return end($array);
    }

    /**
     * Получить значение первого элемента
     *
     * @param array $array массив
     * <code>
     * <?php
     * $array = array('field1', 'field2', 'field3');
     * $firstItem = Request::first($array);
     *
     * var_dump($firstItem);
     * ?>
     * </code>
     * @return mixed
     */
    static public function first(array $array)
    {
        // array needs to be a reference
        return reset($array);
    }

    /**
     * Объединение Массивов
     * @param array $aArray
     * @param int $_iIndex index
     * <code>
     * <?php
     * $arrays = array(
     * 		0 => array('aaa', 'bbb', 'ccc'),
     * 		22 => array(111, 222, 333)
     * 	);
     * $aReturn = Request::combine($arrays);
     *
     * print_r($aReturn);
     * ?>
     * </code>
     * @return array
     */
    static public function combine(array $aArray, int $_iIndex = 0): array
    {
        static $aKeys;
        static $iCount;
        static $aTmp = array();
        static $aReturn = array();

        if (!$_iIndex)
        {
            $aKeys = array_keys($aArray);
            $iCount = count($aArray);
            // Явно очищаем массивы, назвисимо от static
            $aReturn = $aTmp = array();
        }

        if ($_iIndex < $iCount)
        {
            foreach ($aArray[$aKeys[$_iIndex]] as $xxx => $sValue)
            {
                $aTmp[$aKeys[$_iIndex]] = $sValue;
                self::combine($aArray, $_iIndex + 1);
                array_pop($aTmp);
            }
        }
        else
        {
            $aReturn[] = $aTmp;
        }
        return $aReturn;
    }

    /**
     * Объединение элементов многоуровневого массива с помощью строки
     * @param string $glue
     * @param array $array Массив строк для взрыва.
     * @return string
     */
    static public function implode(string $glue, array $array): string
    {
        $aReturn = array();
        foreach ($array as $value)
        {
            $aReturn[] = is_array($value)
                ? self::implode($glue, $value)
                : $value;
        }

        return implode($glue, $aReturn);
    }

    /**
     * Преобразуйте массив в буквальное обозначение для JS-объекта, например ['foo' => 'bar', 'baz' => true] в строку 'foo': 'bar', 'baz': true
     * @param array $array Массив строк для взрыва.
     * @return string
     */
    static public function array2jsObject(array $array): string
    {
        $aReturn = [];

        foreach ($array as $key => $value)
        {
            if ($value === TRUE)
            {
                $value = 'true';
            }
            elseif ($value === FALSE)
            {
                $value = 'false';
            }
            elseif (is_null($value))
            {
                $value = 'null';
            }
            else
            {
                $value = "'" . Request::escapeJavascriptVariable($value) . "'";
            }

            $aReturn[] = "'" . Request::escapeJavascriptVariable($key) . "': " . $value;
        }

        return implode(', ', $aReturn);
    }

    /**
     * Экранирование апострофа ('), косой черты (\), 'script' и разрывов строк.
     * @param string $str исходная строка
     * @return string
     */
    static public function escapeJavascriptVariable(string $str): string
    {
        return str_replace(
            ["\\", "'", "\r", "\n", "script"],
            ["\\\\", "\'", '\r', '\n', "scr'+'ipt"],
            $str
        );
    }

    /**
     * Поиск значения по ключу в многомерном массиве
     * @param array $array
     * @param mixed $key
     * @return NULL|mixed Null or value
     */
    static public function findByKey(array $array, mixed $key): mixed
    {
        if (isset($array[$key]))
        {
            return $array[$key];
        }
        else
        {
            foreach ($array as $subArray)
                if (is_array($subArray))
                {
                    $result = self::findByKey($subArray, $key);

                    if (!is_null($result)) return $result;

                };
        }

        return NULL;
    }
}