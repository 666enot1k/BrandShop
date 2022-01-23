<?php

namespace core;


/**
 * Головний клас ядра сиситеми
 * (сингтон)
 */
class Core
{
    private static $instance;
    private static $mainTemplate;
    private static $db;

    private function __construct()
    {
        global $Config;
        spl_autoload_register('\core\Core::__autoload');
        self::$db = new \core\DB($Config['Database']['Server'],
            $Config['Database']['Username'],
            $Config['Database']['Password'],
            $Config['Database']['Database']);
    }

    /**
     * повертає екземпляр ядра системи
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new Core();
            return self::getInstance();
        } else
            return self::$instance;
    }

    /**
     * отримати об'єкт-з'єднання з базою данних
     */
    public function getDB()
    {
        return self::$db;
    }

    /**
     * Ініціалізація системи
     */
    public function init()
    {
        session_start();
        self::$mainTemplate = new Template();
    }

    /**
     * виконує основний процес роботи СMS-системи
     */
    public function run()
    {
        $path = $_GET['path'];
        $pathParts = explode('/', $path);
        $className = ucfirst($pathParts[0]);
        if (empty($className))
            $fullClassName = 'controllers\\Site';
        else
            $fullClassName = 'controllers\\' . $className;
        $methodName = ucfirst($pathParts[1]);
        if (empty($methodName))
            $fullMethodName = 'actionIndex';
        else
            $fullMethodName = 'action' . ucfirst($pathParts[1]);
        if (class_exists($fullClassName)) {
            $controller = new $fullClassName();
            if (method_exists($controller, $fullMethodName)) {
                $method = new \ReflectionMethod($fullClassName, $fullMethodName);
                $paramsArray = [];
                foreach ($method->getParameters() as $parameter) {
                    array_push($paramsArray, isset($_GET[$parameter->name]) ? $_GET[$parameter->name] : null);
                }
                $result = $method->invokeArgs($controller, $paramsArray);
                if (is_array($result)) {
                    self::$mainTemplate->setParams($result);
                }
            } else
                throw new \Exception('404 Not Found');
        } else
            throw new \Exception('404 Not Found');

    }

    /**
     * завершення роботи системи та виведення результату
     */
    public function done()
    {
        self::$mainTemplate->display('views/layout/index.php');

    }

    /**
     * Автозавантажувач класів
     */
    public static function __autoload($className)
    {
        $fileName = $className . '.php';
        if (is_file($fileName))
            include($fileName);
    }
}