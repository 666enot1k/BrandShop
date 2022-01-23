<?php

namespace controllers;

use core\Controller;
use core\Model;

class Users extends Controller
{
    protected $usersModel;

    function __construct()
    {
        $this->usersModel = new \models\Users();
    }
    function actionLogout()
    {
        $title = 'Вихід';
        unset($_SESSION['user']);
        return $this->renderMessage('ok', 'Ви вийшли з аккаунту.', null, [
            'PageTitle' =>'',
            'MainTitle' => $title]);
    }
    function actionLogin()
    {
        $title = 'Вхід на сайт';
        if(isset($_SESSION['user']))
            return $this->renderMessage('ok', 'Ви вже увійшли.', null, [
                'PageTitle' => '',
                'MainTitle' => $title]);
        if ($this->isPost()) {
            $user = $this->usersModel->AuthUser($_POST['login'], $_POST['password']);
            if (!empty($user)) {
                $_SESSION['user'] = $user;
                return $this->renderMessage('ok', 'Ви успішно увійшли.', null, [
                    'PageTitle' => '',
                    'MainTitle' => $title]);
            } else
                return $this->renderError('wrong login or password', null, [
                    'PageTitle' => '',
                    'MainTitle' => $title,
                    'MessageText' => '',
                    'MessageClass' => 'danger']);
        } else {
            $params = [
                'PageTitle' => '',
                'MainTitle' => $title];
            return $this->render('login', null, $params);
        }
    }

    function actionRegister()
    {
        if ($this->isPost()) {
            $result = $this->usersModel->AddUser($_POST);
            if ($result === true) {
                return $this->renderMessage('ok', 'Користувач успішно зареєстрований', null, [
                    'PageTitle' => '',
                    'MainTitle' => 'Реєстрація на сайті']);
            } else {
                $message = implode('</br>', $result);
                return $this->renderError($message, null, [
                    'PageTitle' => '',
                    'MainTitle' => 'Реєстрація на сайті',
                    'MessageText' => '',
                    'MessageClass' => 'danger']);
            }
        } else {
            $params = [
                'PageTitle' => '',
                'MainTitle' => 'Реєстрація на сайті'];
            return $this->render('register', null, $params);
        }
    }
}