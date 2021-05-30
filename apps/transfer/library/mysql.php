<?php
/**
 * System utilities
 * @copyright Quarnuts LLC
 * @author Serhii Shkrabak
 * @package Library\MySQL
 */

namespace Library;

class MySQL
{

    private string $sql = '';
    private string $filters = '';
    private bool $brackets = false;
    public array $result = [];
    public array $storage = [
        'password' => '',
        'inserted' => 0
    ];
    private array $queue = [];
    private array $order = ['', 'ASC'];

    static function connect(string $host, string $user, string $password)
    {

        if (!$connection = mysqli_connect($host, $user, $password)) {
            throw new \Exception('CONNECTION_ERROR', 6);
        } else
            $connection->query("SET XACT_ABORT ON");

        return $connection;
    }


    /**
     * @throws \Exception
     */
    public function request(string $sql = ''): bool
    {
        $result = false;
        // printme($sql ? $sql : $this -> sql);
        if ($query = $sql ? $sql : $this->sql)
            if ($fetched = $this->connection->query($query)) {
                if (gettype($fetched) == 'mysqli_result') {
                    $this->result = $fetched;
                } else
                    if (gettype($fetched) == 'object') {
                        $this->result = [];
                        foreach ($fetched as $row) {
                            $this->result[] = $row;
                        }
                    }
                $this->sql = '';
                $this->filters = '';
                $this->order = ['', 'ASC'];
                $result = true;
            } else {
                if (!$this->handler($query))
                    throw new \Exception('INTERNAL_ERROR');
            }
        return $result;
    }

    private function handler(string $query = ''): bool
    {
        $result = false;
        $errors = [
            1054 # Unknown column
        ];
        printme($query ? $query : $this->sql);
        printme($this->connection->error);
        if (in_array($this->connection->errno, $errors))
            $result = true;
        return $result;
    }

    public function preview(): string
    {
        return $this->sql . ($this->filters ? ' WHERE' . $this->filters : '');
    }

    public function sort($by, $direction = 'ASC'): self
    {
        $this->order = [$by, $direction];
        return $this;
    }

    public function createTable(string $name, array $columns): self
    {
        $db = $this->db;
        $primary = [];
        $secondary = [];
        $increment = '';
        $foreign = [];
        $sql = "CREATE TABLE `$db`.`$name` (";
        foreach ($columns as $title => $details) {
            $part = "`$title` {$details[ 'type' ]}";
            if (isset($details['attributes']))
                foreach ($details['attributes'] as $attribute)
                    $part .= " $attribute";
            $part .= ' ' . (isset($details['null']) ? 'NULL' : 'NOT NULL');
            if (isset($details['autoincrement']))
                $increment = $part;
            if (isset($details['foreign']))
                $foreign[$title] = $details['foreign'];
            $part .= (isset($details['default'])
                    ? (
                    in_array($details['default'], ['CURRENT_TIMESTAMP'])
                        ? " DEFAULT {$details[ 'default' ]}" : " DEFAULT '{$details[ 'default' ]}'") : '')
                . (isset($details['comment']) ? " COMMENT '{$details[ 'comment' ]}'" : '') . ',';
            if (isset($details['key'])) {
                $key = $details['key'];
                $key[] = $title;
            }
            $sql .= $part;
        }
        $sql = substr($sql, 0, -1) . ") ENGINE=InnoDB";

        $keys = '';
        if ($primary) {
            $keys = ',ADD PRIMARY KEY (';
            foreach ($primary as $key)
                $keys .= "`$key`";
            $keys .= ')';
        }
        if ($secondary) {
            $keys = ',ADD KEY (';
            foreach ($secondary as $key)
                $keys .= "`$key` (`$key`)";
            $keys .= ')';
        }

        $connection = $this->transaction();
        $connection->request($sql);
        if ($keys)
            $connection->request("ALTER TABLE `$db`.`$name` " . substr($keys, 1));
        if ($increment)
            $connection->request("ALTER TABLE `$db`.`$name`
					MODIFY $increment AUTO_INCREMENT");
        foreach ($foreign as $column => $key)
            $this->queue[] = "ALTER TABLE `$db`.`$name`
						ADD CONSTRAINT `$db" . "_$name" . "_$column` FOREIGN KEY (`$column`) REFERENCES $key";
        $connection->commit();
        return $this;
    }

    public function processQueue(): self
    {
        foreach ($this->queue as $task)
            $this->request($task);
        $this->queue = [];
        return $this;
    }

    public function createDatabase(string $name): self
    {
        $this->request("CREATE DATABASE IF NOT EXISTS `$name`");
        return $this;
    }

    public function dropDatabase(string $name): self
    {
        $this->request("DROP DATABASE IF EXISTS `$name`");
        return $this;
    }

    public function createUser(string $name): self
    {
        $password = \Core\Library\Random::generateToken(10);
        if ($this->request("CREATE USER '$name'@'%' IDENTIFIED WITH mysql_native_password BY '$password'"))
            $this->storage['password'] = $password;
        return $this;
    }

    public function dropUser(string $name): self
    {
        $this->request("DROP USER IF EXISTS `$name`");
        return $this;
    }

    public function grant(string $user, string $object = '*.*', string $permissions = 'USAGE'): self
    {
        $this->request("GRANT $permissions ON $object TO '$user'@'%'");
        return $this;
    }


    public function stack(string $modificator = 'AND'): self
    {

        $this->filters .= ($this->filters && !$this->brackets
            ? ((" $modificator ("))
            : '' . ' (');
        $this->brackets = true;
        return $this;
    }

    public function end(): self
    {
        $this->filters .= ' )';
        $this->brackets = false;
        return $this;
    }

    public function transaction(): self
    {
        $this->connection->begin_transaction();
        return $this;
    }

    public function commit(): self
    {
        if (!$this->connection->commit())
            throw new \Exception('INTERNAL_ERROR');
        return $this;
    }

    public function run(bool $details = false): self
    {

        if ($this->filters)
            $sql = ' WHERE' . $this->filters;
        $this->request($this->sql . (isset($sql) ? $sql : ''));

        if ($details)
            $this->storage['inserted'] = $this->connection->insert_id;
        return $this;
    }

    public function update(string $table, array $fields): self
    {
        /*
        'table',
        [
            'field' => value
        ]
        UPDATE `db`.`table`.`column` ...
         */
        $db = $this->db;
        $update = '';
        $table = "`$db`.`$table`";
        foreach ($fields as $key => $field)
            $update .= ",$table.`$key` = " . ($field !== null ? "'$field'" : 'null');
        if ($update)
            $update = substr($update, 1);

        $this->sql = "UPDATE $table SET $update";
        return $this;
    }

    public function insert(array $tables): self
    {
        $sql = '';
        foreach ($tables as $table => $rows) {
            $into = '';
            if (empty($rows))
                $sql .= 'DEFAULT VALUES';
            else {
                $into = '(';
                $sql .= 'VALUES (';
                foreach ($rows as $value) {
                    $sql .= (!is_null($value) ? ("'" . str_replace("'", "''", $value) . "'") : 'NULL') . ", ";
                }
                $sql = substr($sql, 0, -2) . ')';
                foreach (array_keys($rows) as $column)
                    $into .= "`$column`, ";
                $into = substr($into, 0, -2) . ')';
            }
            if ($sql)
                $this->request("INSERT INTO `{$this -> db}`.`$table` $into $sql");
        }
        return $this;
    }

    public function one(): array|null
    {
        $result = $this->many(1);
        return $result[0] ?? null;
    }

    public function many(int $limit = 0): array
    {
        $sql = $this->sql;
        if ($this->filters)
            $sql .= ' WHERE' . $this->filters;
        if ($this->order[0])
            $sql .= ' ORDER BY ' . $this->order[0];
        if ($limit)
            $sql .= " LIMIT $limit";

        $this->request($sql);
        return $this->result;
    }

    public function where(array $filters = [], string $modificator = 'AND'): self
    {
        /*
        'table' => [
            'column' => value
        ]
        ...WHERE `column` = 'value'
         */
        $db = $this->db;
        $where = '';
        foreach ($filters as $table => $fields)
            foreach ($fields as $column => $value) {
                $where .= " $modificator `$db`.`$table`.`$column` = " . ((gettype($value) == 'string' && isset($value[0]) && $value[0] == '`') ? $value : "'$value'");
            }
        if ($where)
            $this->filters .= $this->filters ? $where : substr($where, strlen($modificator) + 1);
        return $this;
    }

    public function select(array $filters): self
    {
        /*
        'table' => [
            'key' => column
        ]
        SELECT `db`.`table`.`column` FROM ...
        SELECT `db`.`table`.`column` as `key` FROM ...
         */
        $db = $this->db;
        $select = '';
        $tables = '';
        foreach ($filters as $table => $fields) {
            $tables .= ",`$db`.`$table`";
            if (empty($fields)) {
                $select .= ",`$db`.`$table`.*";
            } else
                foreach ($fields as $key => $field)
                    $select .= ",`$db`.`$table`." . (is_numeric($key) ? "`$field`" : "`$field` as `$key`");
        }
        if ($tables) {
            $tables = substr($tables, 1);
            if ($select)
                $select = substr($select, 1);
            $this->sql = "SELECT $select FROM $tables";
        }
        return $this;
    }

    public function __construct(private string $db, private \MySQLi $connection)
    {
    }
}