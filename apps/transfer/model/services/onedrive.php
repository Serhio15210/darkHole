<?php
namespace Model\Services;
use \Microsoft\Graph\Graph;
use SplFileInfo;

class OneDrive{

    /**
     * @throws \Microsoft\Graph\Exception\GraphException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    function get_from_onedrive($token): mixed
    {
        $graph = new \Microsoft\Graph\Graph();
        $graph->setAccessToken($token);
        $ptr = null;
        $tmpFile = tmpfile(); //создание временного файла
        $tmpUri = stream_get_meta_data($tmpFile);
        $tmpUri = $tmpUri['uri']; //получение uri временного файла
        //скачивание с облака во временный файл
        $ptr = $graph->createRequest("GET", "/TransferOPU")->upload((string)$tmpUri);
        return $ptr;
    }




    /**
     * @throws \Microsoft\Graph\Exception\GraphException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    function send_to_onedrive($token, $file): bool
    {
        print_r("3\n");
        $graph = new Graph();
        print_r("4\n");
        $graph->setAccessToken($token);
        print_r("5\n");
        $ptr = $graph->createRequest("POST", "/TransferOPU")->download("/TransferOPU", $file);
        print_r("6\n");
        //return isset($ptr);
        return true;
    }

    public function __construct()
    {
    }

}

