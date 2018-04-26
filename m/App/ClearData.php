<?php

/**
 * Предназначен для очистки данных принимаемых из форм
 */
trait App_ClearData
{

    /**
     * @param $data string выражение для проверки
     * @return string
     */
    public function clearString($data)
    {
        if(is_null($data))
        {
            return $data;
        }
        if (!is_string($data))
        {
            throw new InvalidArgumentException('Недопустимый тип параметра [data]. Ожидается [string] или NULL вместо [' . gettype($data) . ']');
        }
        $data = trim(htmlspecialchars($data));
        return $data;
    }
}
