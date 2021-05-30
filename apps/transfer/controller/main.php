<?php
/**
 * User Controller
 *
 * @author Serhii Shkrabak
 * @global object $CORE
 * @package Controller\Main
 */
namespace Controller;
class Main
{
    use \Library\Shared;

    private $model;

    private function prepare(Mixed $var, Array $param):mixed {
        $result = null;
        if ($var) {
            $result = $var;
        } else {
            if (!isset($param['required'])) {
                if (isset($param['default']))
                    $result = $param['default'];
                else
                    throw new \Exception($param['name'] . ' has no default value', 6);
            }
            else
                throw new \Exception($param['name'], 1);
        }
        return $result;
    }

    private function run($method, $data):?array { //Вызов методов
        $result = null;
        if (method_exists($this->model, $method)) {
            $callback = [$this->model, $method];
            $result = call_user_func_array($callback, $data);
        } else throw new \Exception($method, 5);
        return $result;
    }

    /**
     * @throws \Exception
     */
    public function exec():?array {
        $result = null;
        $url = $this->getVar('REQUEST_URI', 'e');
        $url = explode('?', $url)[0];
        $path = explode('/', $url);

        if (isset($path[2]) && !strpos($path[1], '.')) { // Disallow directory changing
            $file = ROOT . 'model/config/methods/' . $path[1] . '.php';
            $method = $path[1] . $path[2];
            if (file_exists($file)) {
                include $file;
                if (isset($methods[$path[2]])) {
                    $details = $methods[$path[2]];

                    $uniget = $this->getVar('uniroad');

                    if ($uniget) {
                        $GLOBALS['uni.user'] = $this->getVar('user');
                        $result = ($method == 'uniwebhook') ? [
                            'callback' => []
                        ] : [];
                        $query = $this->getVar('query', 'p');
                        if (gettype($query) == 'string')
                            $query = json_decode($query, true);
                        $data = [];
                        foreach ($query as $task) {

                            foreach ($details['params'] as $param) {
                                $var = $this->getVar($param['name'], $param['source'] ?? 'p', from: $task);
                                $data[$param['name']] = $this->prepare($var, $param);
                            }
                            if (isset($result['callback'])) {
                                $callback = $this->run($method, $data);
                                if ($callback)
                                    $result['callback'][] = $callback;
                            }
                            else
                                $result[] = $this->run($method, $data);

                        }
                    } else {
                        $data = [];
                        foreach ($details['params'] as $param) {
                            $var = $this->getVar($param['name'], $param['source'] ?? 'p');
                            $data[$param['name']] = $this->prepare($var, $param);
                        }
                        $result = $this->run($method, $data);
                    }

                }

            }
            else
                throw new \Exception("{$path[1]}.{$path[2]}", 5);
        }

        return $result;
    }

    /**
     * @throws \Exception
     */
    public function __construct() {
        // CORS configuration
        $origin = $this -> getVar('HTTP_ORIGIN', 'e');
        $front = $this -> getVar('FRONT', 'e');

        foreach ( [$front] as $allowed )
            if ( $origin == "https://$allowed") {
                header("Access-Control-Allow-Origin: $origin");
                header('Access-Control-Allow-Credentials: true');
            }
        $this->model = new \Model\Main;
    }
}