<?php
/**
 * System utilities
 *
 * @author Serhii Shkrabak
 * @package Library\Entity
 */
namespace Library;
trait Entity
{

    private array $_changed = [];

    public function set(Array $fields):self {
        foreach ($fields as $field => $value) {
            $this->_changed[$field] = gettype($value) == 'array' ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value;
            $this->$field = $value;
        }
        $this->save();
        return $this;
    }
}