<?php
/**
 * Telegram communication serice
 *
 * @author Serhii Shkrabak
 * @package Library\Telegram
 */
namespace Model\Services;
class Uniroad
{
    use \Library\Shared;

    public Int $state = 0;
    public Int $version = 1;
    private Array $data;
    public array $structures = [];
    private array $query = [];
    private String $url;
    private ?\Model\Entities\Service $provider = null;

    public function __construct() {
        $this->db = $this->getDB();
    }

    private function run($multi = false):void {
        $answer = $this->request($this->url, [
            'uniroad' => $this->version,
            'token' => $this->provider->token,
            'query' => json_encode([$this->query]),
            'user' => isset($GLOBALS['user']) ? $GLOBALS['user']->guid : (isset($GLOBALS['uni.user']) ? $GLOBALS['uni.user'] : null)
        ]);
        if ($response = json_decode($answer, true)) {

            if ($response['state'] === 0) {
                if (isset($response['data']))
                    $this->data = $response['data'];
            }
            else {
                printme($response);
                throw new \Exception("Service {$this->provider->title} has problem", 10);
            }
        } else {
            $answer = str_replace("<br />", "", $answer);
            $answer = str_replace("<b>", "", $answer);
            $answer = str_replace("</b>", "", $answer);
            $answer = explode("\n", $answer);
            printme($answer);
            throw new \Exception("Service {$this->provider->title} incompatible", 10);
        }
    }

    public function many(): array
    {
        $this->run();
        return $this->data;
    }

    public function one($index = 0):mixed {
        $result = null;
        $this->run();
        if (isset($this->data['callback'][$index]))
            $result = [
                'callback' => [ $this->data['callback'][$index] ]
            ];
        else {
            $result = $this->data[$index] ?? null;
        }
        return $result;
    }

    public function get(Int|String $service, Array $query, String $method = 'uni/webhook'):self {
        $this->storage = [];

        if (($provider = $this->getService($service)) && $provider->webhook) {
            $this->query = $query;
            $this->url = $provider->webhook . "/$method";
            $this->provider = $provider;
        } else
            throw new \Exception("Service {$provider->title} need setup", 10);

        return $this;
    }

    private function getService (Int|String $service) {
        return is_numeric($service)
            ? \Model\Entities\Service::search(id: $service, limit: 1)
            : \Model\Entities\Service::search(code: $service, limit: 1);
    }

}