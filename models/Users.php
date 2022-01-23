<?php

namespace models;


use core\Utils;

class Users extends \core\Model
{
    public function Validate($formRow)
    {
        $errors = [];
        if (empty($formRow['login']))
            $errors [] = 'Поле "Логін" повинне бути заповнене';
        $user = $this->GetUserByLogin($formRow['login']);
        if (!empty($user))
            $errors [] = 'Користувач з таким логіном вже існує';
        if (empty($formRow['password']))
            $errors [] = 'Поле "Пароль" повинне бути заповнене';
        if ($formRow['password'] != $formRow['password2'])
            $errors [] = 'Паролі повинні співпадати';
        if (empty($formRow['lastname']))
            $errors [] = 'Поле "Прізвище" повинне бути заповнене';
        if (empty($formRow['firstname']))
            $errors [] = 'Поле "Ім\'я" повинне бути заповнене';
        if (count($errors) > 0)
            return $errors;
        else
            return true;
    }

    public function IsUserAuthentication()
    {
        return isset($_SESSION['user']);
    }
    public function getCurrentUser()
    {
        if ($this->IsUserAuthentication())
            return $_SESSION['user'];
        else
            return null;
    }

    public function AddUser($userRow)
    {
        $validateResult = $this->Validate($userRow);
        if (is_array($validateResult))
            return $validateResult;
        $fields = ['login', 'password', 'lastname', 'firstname', 'access'];
        $userRowFiltered = Utils::ArrayFilter($userRow, $fields);
        $userRowFiltered['password'] = md5($userRowFiltered['password']);
        \core\Core::getInstance()->getDB()->insert('users', $userRowFiltered);
        return true;
    }
    public function AuthUser($login, $password)
    {
        $password = md5($password);
        $users = \core\Core::getInstance()->getDB()->select('users', '*', [
            'login' => $login,
            'password' => $password
        ]);
        if (count($users) > 0) {
            $user = $users[0];
            return $user;
        } else
            return false;
    }

    public function AuthAdmin($access)
    {

    }

    public function GetUserByLogin($login)
    {
        $rows = \core\Core::getInstance()->getDB()->select('users', '*', ['login' => $login]);
        if (count($rows) > 0)
            return $rows[0];
        else
            return null;
    }

}