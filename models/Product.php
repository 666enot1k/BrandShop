<?php

namespace models;

use core\Utils;
use Imagick;

class Product extends \core\Model
{
    public function ChangePhoto($id, $file)
    {
        $folder = 'files/product/';
        $file_path = pathinfo($folder . $file);
        $file_big = $file_path['filename'] . '_b';
        $file_middle = $file_path['filename'] . '_m';
        $file_small = $file_path['filename'] . '_s';
        $product = $this->getProductById($id);
        if (is_file($folder . $product['photo'] . '_b.jpg') && is_file($folder . $file))
            unlink($folder . $product['photo'] . '_b.jpg');
        if (is_file($folder . $product['photo'] . '_m.jpg') && is_file($folder . $file))
            unlink($folder . $product['photo'] . '_m.jpg');
        if (is_file($folder . $product['photo'] . '_s.jpg') && is_file($folder . $file))
            unlink($folder . $product['photo'] . '_s.jpg');
        $product['photo'] = $file_path['filename'];
        $im_b = new Imagick();
        $im_b->readImage($_SERVER['DOCUMENT_ROOT'] . '/' . $folder . $file);
        $im_b->ThumbnailImage(1280, 1024, true, false);
        $im_b->writeImage($_SERVER['DOCUMENT_ROOT'] . '/' . $folder . $file_big . '.jpg');
        $this->UpdateProduct($product, $id);
        $im_m = new Imagick();
        $im_m->readImage($_SERVER['DOCUMENT_ROOT'] . '/' . $folder . $file);
        $im_m->ThumbnailImage(500, 500, true, false);
        $im_m->writeImage($_SERVER['DOCUMENT_ROOT'] . '/' . $folder . $file_middle . '.jpg');
        $this->UpdateProduct($product, $id);
        $im_s = new Imagick();
        $im_s->readImage($_SERVER['DOCUMENT_ROOT'] . '/' . $folder . $file);
        $im_s->ThumbnailImage(200, 200, true, false);
        $im_s->writeImage($_SERVER['DOCUMENT_ROOT'] . '/' . $folder . $file_small . '.jpg');
        unlink($folder . $file);
        $this->UpdateProduct($product, $id);

    }

    public function AddProduct($row)
    {
        $userModel = new \models\Users();
        $user = $userModel->GetCurrentUser();
        if ($user['login'] !== 'admin@admin.com' && $user['password'] !== '21232f297a57a5a743894a0e4a801fc3') {
            $result = [
                'error' => true,
                'message' => ['Користувач не аунтефікований']
            ];
            return $result;
        }
        $validateResult = $this->Validate($row);
        if (is_array($validateResult)) {
            $result = [
                'error' => true,
                'messages' => $validateResult
            ];
            return $result;
        }
        $fields = ['name_tov', 'price', 'text'];
        $rowFiltered = Utils::ArrayFilter($row, $fields);
        $rowFiltered['datetime'] = date('Y-m-d H:i:s');
        $rowFiltered['user_id'] = $user['id'];
        $rowFiltered['photo'] = ' . . . photo . . . ';
        $id = \core\Core::getInstance()->getDB()->insert('product', $rowFiltered);
        return $result = [
            'error' => false,
            'id' => $id
        ];
    }

    public function getLastProduct($count)
    {
        return \core\Core::getInstance()->getDB()->select('product', '*', null, ['datetime' => 'DESC'], $count);
    }

    public function getLastOrder($count1)
    {
        return \core\Core::getInstance()->getDB()->select('users_orders', '*', null, ['datetime' => 'DESC'], $count1);
    }

    public function getProductById($id)
    {
        $product = \core\Core::getInstance()->getDB()->select('product', '*', ['id' => $id]);
        if (!empty($product))
            return $product[0];
        else
            return null;
    }
    public function UpdateProduct($row, $id)
    {
        $userModel = new \models\Users();
        $user = $userModel->GetCurrentUser();
        if ($user == null)
            return false;
        $validateResult = $this->Validate($row);
        if (is_array($validateResult))
            return $validateResult;
        $fields = ['name_tov', 'price', 'text', 'photo'];
        $rowFiltered = Utils::ArrayFilter($row, $fields);
        $rowFiltered['datetime_lastedit'] = date('Y-m-d H:i:s');
        \core\Core::getInstance()->getDB()->update('product', $rowFiltered, ['id' => $id]);
        return true;
    }

    public function DeleteProduct($id)
    {
        $product = $this->getProductById($id);
        $userModel = new \models\Users();
        $user = $userModel->GetCurrentUser();
        if (empty($product) || $user['login'] !== 'admin@admin.com' && $user['password'] !== '21232f297a57a5a743894a0e4a801fc3')
            return false;
        \core\Core::getInstance()->getDB()->delete('product', ['id' => $id]);
        return true;
    }

    public function OrderProduct($row)
    {
        $userModel = new \models\Users();
        $user = $userModel->GetCurrentUser();
        $validateResult = $this->ValidateOrder($row);
        if (is_array($validateResult)) {
            $result = [
                'error' => true,
                'messages' => $validateResult
            ];
            return $result;
        }
        $fields = ['name_tov', 'size', 'price', 'lastname', 'firstname', 'city', 'address', 'phone_num'];
        $rowFiltered = Utils::ArrayFilter($row, $fields);
        $rowFiltered['datetime'] = date('Y-m-d H:i:s');
        $rowFiltered['user_id'] = $user['id'];
        $id = \core\Core::getInstance()->getDB()->insert('users_orders', $rowFiltered);
    }

    public function Validate($row)
    {
        $errors = [];
        if (empty($row['name_tov']))
            $errors [] = 'Поле "title" повинне бути заповнене';
        if (empty($row['price']))
            $errors [] = 'Поле "price" повинне бути заповнене';
        if (empty($row['text']))
            $errors [] = 'Поле "text" повинне бути заповнене';
        if (count($errors) > 0)
            return $errors;
        else
            return true;
    }

    public function ValidateOrder($row)
    {
        $errors = [];
        if (empty($row['name_tov']))
            $errors [] = 'Поле "Назва" повинне бути заповнене';
        if (empty($row['size']))
            $errors [] = 'Поле "Розмір" повинне бути заповнене';
        if (empty($row['price']))
            $errors [] = 'Поле "Ціна" повинне бути заповнене';
        if (empty($row['lastname']))
            $errors [] = 'Поле "Прізвище" повинне бути заповнене';
        if (empty($row['firstname']))
            $errors [] = 'Поле "Ім\'я" повинне бути заповнене';
        if (empty($row['city']))
            $errors [] = 'Поле "Місто" повинне бути заповнене';
        if (empty($row['address']))
            $errors [] = 'Поле "Адреса" повинне бути заповнене';
        if (empty($row['phone_num']))
            $errors [] = 'Поле "Номер телефону" повинне бути заповнене';
        if (count($errors) > 0)
            return $errors;
        else
            return true;
    }
}