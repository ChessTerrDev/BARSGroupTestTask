<?php
declare(strict_types=1);

namespace BARSGroupTestTask\Model\Entities;

class AbstractEntity
{
    /**
     * Выполнить все методы get|is для всех свойств объекта
     * @return array = со свойствами объекта в качестве ключей и внутренними значениями этих свойств в качестве значений
     * ключи возврата только для заданных свойств
     */
    public function getFields(): array
    {
        $vars = array_filter(get_object_vars($this), function ($a) {
            return ($a !== null);
        }); //excluding all null properties from return
        return $this->parseFields($vars);
    }

    /**
     * execute все методы get|is для всех свойств объекта
     * @return array = со всеми свойствами объекта в качестве ключей и внутренними значениями этих свойств в качестве значений
     * returns ключи для Всех свойств, в том числе и не заданных
     */
    public function getAllFields(): array
    {
        $vars = get_object_vars($this);
        return $this->parseFields($vars);
    }

    /**
     * @param $fields
     * @return array
     */
    public function parseFields($fields): array
    {
        $retFieldsArr = [];
        foreach ($fields as $key => $val) {
            $name = explode('_', $key);
            $name = implode(array_map('ucfirst', $name));
            $getMethod = 'get' . $name;
            $isMethod = 'is' . $name;
            if (method_exists($this, $getMethod)) {
                $retFieldsArr[$key] = $this->parseField($this->$getMethod());
            } elseif (method_exists($this, $isMethod)) {
                $retFieldsArr[$key] = $this->parseField($this->$isMethod());
            } else {
                $retFieldsArr[$key] = $this->parseField($val);
            }
        }
        return $retFieldsArr;
    }

    /**
     * @param $val
     * @return array|mixed
     */
    protected function parseField($val)
    {
        if (is_array($val)) {
            return $this->parseFields($val);
        } elseif (is_object($val) && method_exists($val, 'getFields')) {
            return $val->getFields();
        } else {
            return $val;
        }
    }

    /**
     * @param $fields
     * @return $this
     */
    public function setFields(array $fields): AbstractEntity
    {
        if (!empty($fields)) {
            foreach ($fields as $field => $value) {
                if (is_object($value)) {
                    $value = (array)$value;
                }
                if (!is_object($value)) {
                    $field = explode('_', $field);
                    $field = implode(array_map('ucfirst', $field));
                    $method = 'set' . $field;
                    if (method_exists($this, $method)) {
                        $this->$method($value);
                    }
                }
            }
        }
        return $this;
    }

}