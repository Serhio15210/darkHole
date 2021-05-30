<?php
/**
 * Service entity
 *
 * @author Serhii Shkrabak
 * @global object $CORE->model
 * @package Model\Entities\Service
 */
namespace Model\Entities;

class Service
{
    use \Library\Shared;
    use \Library\Entity;

    public static function search(String $title = '', String $description = '', Int $id = 0, ?String $code = '',
                                  Int $status = 0, String $webhook = '', ?String $updated = '',
                                  ?String $office = '', ?String $signature = '', ?Int $user = 0, Int $limit = 0):self|array|null {
        $result = [];
        $db = self::getDB();
        $services = $db -> select(['Services' => []]);

        foreach (['id', 'signature', 'code'] as $var)
            if ($$var)
                $filters[$var] = $$var;
        if(!empty($filters))
            $services->where(['Services'=> $filters]);

        foreach ($services->many($limit) as $service) {
            $class = __CLASS__;
            $result[] = new $class($service['title'], $service['description'], $service['id'], $service['code'],
                $service['status'], $service['webhook'], $service['updated'], $service['office'], $service['user']);
        }
        return $limit == 1 ? (isset($result[0]) ? $result[0] : null) : $result;
    }

    public function save():self {
        $db = $this->db;
        if (!$this->id) {
            $insert = [
                'title' => $this->title,
                'description' => $this->description,
                'user' => $this->user,
            ];
            if ($this->token) {
                $insert['token'] = $this->token;
                $insert['signature'] = $this->signature;
            }
            $this->id = $db -> insert([
                'Services' => $insert
            ])->run(true)->storage['inserted'];
        }

        if ($this->_changed)
            $db -> update('Services', $this->_changed )
                -> where(['Services'=> ['id' => $this->id]])
                -> run();
        return $this;
    }


    public function __construct(public String $title, public String $description = '', public Int $id = 0, public ?String $code = '',
                                public Int $status = 0, public ?String $webhook = '', public ?String $updated = '',
                                public ?String $office = '', public ?Int $user = 0, public ?String $token = '' , public ?String $signature = '', ) {
        $this->db = $this->getDB();
    }
}