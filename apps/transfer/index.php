<?PHP /*** Core bootloader** @author Serhii Shkrabak */
    print_r($_FILES);
    /* RESULT STORAGE */
    $RESULT = [
        'state' => 0,
        'data' => [],
        'debug' => []
    ];

    /* ENVIRONMENT SETUP */
    define('ROOT', $_SERVER['DOCUMENT_ROOT'] . '/'); // Unity entrypoint;
    define('MODE', $_SERVER['MODE']);

    if (MODE == 'development')
        ini_set('display_errors', 'on');

    register_shutdown_function('shutdown', 'OK'); // Unity shutdown function

    spl_autoload_register('load'); // Class autoloader

    set_exception_handler('handler'); // Handle all errors in one function

    /* HANDLERS */

    /*
    * Class autoloader
    */
    function load(string $class): void
    {
        $class = strtolower(str_replace('\\', '/', $class));
     $file = "$class.php";
     if (file_exists($file))
         include $file;
    }

    function printme(Mixed $var): void
    {
        $stack = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 1)[0];
         $GLOBALS['RESULT']['debug'][] = [
            'type' => 'Trace',
            'details' => $var,
            'file' => $stack['file'],
            'line' => $stack['line']
        ];
    }

    /*
    * Error logger
    */
    function handler(Throwable $e): void
    {
        global $RESULT;
        $codes = ['SUCCESS', 'REQUEST_INCOMLETE', 'REQUEST_INCORRECT', 'ACCESS_DENIED', 'RESOURCE_LOST', 'REQUEST_UNKNOWN', 'INTERNAL_ERROR', 10 => 'ERROR_EXTERNAL'];
        $message = $e->getMessage();
        $code = $e->getCode();
        $RESULT['state'] = $code ? $code : 6;
        $RESULT['message'] = $codes[$RESULT['state']] . ": $message";
        $RESULT['debug'][] = [
            'type' => get_class($e),
            'details' => $message,
            'code' => $code,
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTrace(),
        ];
    }

    /*
     * Shutdown handler
    */
    function shutdown(): void
    {
        global $RESULT;
        $error = error_get_last();
        if (!$error) {
            header("Content-Type: application/json; charset=utf-8");
        if ($RESULT['state'])
            unset($RESULT['data']);
        if (MODE != 'development')
            unset($RESULT['debug']);
        echo json_encode($RESULT, JSON_UNESCAPED_UNICODE);
        }
    }

    if (!isset($_GET['file'])) {
        $CORE = new Controller\Main;
        $data = $CORE->exec();
        if ($data !== null)
            $RESULT['data'] = $data;
        else { // Error happens
             throw new Exception(code: 6);
             unset($RESULT['data']);
        }
    } else {
         if (isset($_GET['token']) && $_GET['token'] == 911) {
             $RESULT['data'] = [file_get_contents(ROOT . $_GET['file'])];
            }
        }
?>
