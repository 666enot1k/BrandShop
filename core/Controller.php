<?php

namespace core;

/**
 * базовий клас для всіх контролерів
 */
class Controller
{
    public function isPost()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
            return true;
        else
            return false;
    }

    public function isGet()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET')
            return true;
        else
            return false;
    }

    public function postFilter($fields)
    {
        return Utils::ArrayFilter($_POST, $fields);
    }

    public function render($viewName, $localParams = null, $globalParams = null)
    {
        $tpl = new Template();
        if (is_array($localParams))
            $tpl->setParams($localParams);
        if (!is_array($globalParams))
            $globalParams = [];
        $moduleName = strtolower((new \ReflectionClass($this))->getShortName());
        $globalParams['PageContent'] = $tpl->render("views/{$moduleName}/{$viewName}.php");
        return $globalParams;
    }

    public function renderMessage($message, $localParams = null, $globalParams = null)
    {
        $tpl = new Template();
        if (is_array($localParams))
            $tpl->setParams($localParams);
        $tpl->setParam('MessageText', $message);
        if (!is_array($globalParams))
            $globalParams = [];
        $globalParams['PageContent'] = $tpl->render("views/layout/message.php");
        return $globalParams;
    }
    public function renderError($message, $localParams = null, $globalParams = null)
    {
        $tpl = new Template();
        if (is_array($localParams))
            $tpl->setParams($localParams);
        $tpl->setParam('MessageText', $message);
        if (!is_array($globalParams))
            $globalParams = [];
        $globalParams['PageContent'] = $tpl->render("views/layout/error.php");
        return $globalParams;
    }


}