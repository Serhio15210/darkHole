<?php
/**
 * User Controller
 *
 * @author Serhii Shkrabak
 * @global object $CORE- >model
 * @package Model\Main
 */


namespace Model;
use Model\Services\OneDrive;

class Main
{
    use \Library\Shared;




    private string $key = '1799168544:AAFNGOyOlGAdO1kJp09QwonDCLDCRAx5Ays';

    /**
     * @param string $type
     * @param string $value
     * @param int $code
     * @return array|null
     */
    public function uniwebhook(string $type = '', string $value = '', int $code = 0): ?array
    {//комуникация с запросами которые нам присылают
        $result = null;
        switch ($type) {
            case 'message':

                    if ($value == 'вихід') {
                    $result = ['type' => 'context', 'set' => null];
                } else
                    $result = [
                        'to' => $GLOBALS['uni.user'],
                        'type' => 'message',
                        'value' => "Сервіс `Перенесення проектів` отримав повідомлення $value"
                    ];
                break;
            case 'click':
                $buttons = $this->getButtons($code);
                $result = [
                    'to' => $GLOBALS['uni.user'],
                    'type' => 'message',
                    'value' => $this->getText($code),
                    'keyboard' => [
                        'buttons' => [$buttons]
                    ]
                ];
//
                break;
        }
        return $result;
    }

    private function getButtons(int $parent): array|null
    {
        $result = [];
        $buttons = \Model\Entities\Message::search(parent: $parent, limit: 10);
        foreach ($buttons as $button) {
            $result [] = ['id' => $button->id, 'title' => $button->title];
        }
        return $result;
    }

    private function getText(int $id): string
    {
        $message = \Model\Entities\Message::search($id, limit: 1);
        return $message->text;
    }

    /**
     * @throws \Exception
     */
    public function formcollectData($email, $token, $ftpServer): bool
    {
        $result = null;
        $name = explode('@', $email)[0];

        $file =  (new OneDrive)->get_from_onedrive($token);//получаем файл с onedrive

        if (isset($file)) {//Если в файле ничего нет
            rename($file, $name.$file) or die ("Помилка перейменування файлу");
            $local_file = ROOT . '/TransferOPU/' . $file;//путь к локальному файлу
            $conn_id = ftp_connect($ftpServer);
            $result = ftp_put($conn_id, $file, $local_file, FTP_BINARY);//загружаем на ftp-сервер
            fclose($file);
            ftp_close($conn_id);

        } else throw new \Exception( 'RESOURCE_LOST', 4 );
        return $result;
    }

    /**
     * @throws \Exception
     */
    public function formsyncData($email, $token, $archive): bool
    {
        $result = null;
        print_r("2\n");
        $archive = fopen($archive, 'r+');
        print_r("10\n");
        if (isset($archive)) {//Если файл не пустой
            $result = (new OneDrive)->send_to_onedrive($token,$archive);
            print_r("11\n");
            fclose($archive);
        } else throw new \Exception( 'RESOURCE_LOST', 4 );
        return $result;
    }


    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->db = new \Library\MySQL('core',
            \Library\MySQL::connect(
                $this->getVar('DB_HOST', 'e'),
                $this->getVar('DB_USER', 'e'),
                $this->getVar('DB_PASS', 'e')
            ));
        $this->setDB($this->db);
    }
}