<?php
/**
 * Message entity
 *
 * @author Serhii Shkrabak
 * @global object $CORE->model
 * @package Model\Entities\Message
 */
namespace Model\Entities;

class Message
{
    use \Library\Shared;
    use \Library\Entity;

    public static function search(Int $id = 0, ?Int $parent = 0, Int $type = 0, ?String $guid = null,
                                  ?String $code = null, ?String $title = null, ?String $text = null,
                                  ?String $entrypoint = null, Int $position = 0, ?Int $service = null,
                                  ?Bool $reload = null, Int $limit = 0):self|array|null {
        $result = [];
        $db = self::getDB();
        $messages = $db -> select(['Messages' => []]);
        $filters = [];
        foreach (['id', 'entrypoint', 'parent', 'service'] as $var)
            if ($$var)
                $filters[$var] = $$var;
        if(!empty($filters))
            $messages->where(['Messages'=> $filters]);
        foreach ($messages->many($limit) as $message) {
            $class = __CLASS__;
            $result[] = new $class($message['id'], $message['parent'], $message['type'], $message['guid'],
                $message['code'], $message['title'], $message['text'], $message['entrypoint'],
                $message['position'], $message['service'], $message['reload']);
        }
        return $limit == 1 ? ($result[0] ?? null) : $result;
    }

    public function save():self {
        $db = $this->db;
        if (!$this->id) {}
        return $this;
    }

    public function getChildren($limit = 0):array|self {
        return $this::search(parent: $this->id, limit: $limit);
    }

    public function getKeyboard():array {

        $buttons = [];
        $back = null;

        foreach ( $this->getChildren() as $button) {
            $entrypoint = '';
            if ($button->entrypoint) {
                $entrypoint = (! is_string($button->entrypoint))
                    ? $button->entrypoint
                    : 0;
                $back = [['text' => '◀️ Попереднє меню', 'callback_data' => '9']];
            }

            if ($button->title)
                $buttons[] = ['text' => $button->title, 'callback_data' => json_encode([
                    'id' => $button->id,
                    'type' => $button->type,
                    'reload' => $button->reload
                ])];
        }

        return $buttons ? ($back ? [$buttons, $back] : [$buttons]) : [];
    }

    public function __construct(public Int $id = 0, public ?Int $parent = 0, public Int $type = 0, public ?String $guid = null,
                                public ?String $code = null, public ?String $title = null, public ?String $text = null,
                                public ?String $entrypoint = null, public Int $position = 0, public ?Int $service = null,
                                public ?Bool $reload = null) {
        $this->db = $this->getDB();
    }



}