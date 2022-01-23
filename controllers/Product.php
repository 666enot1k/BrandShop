<?php

namespace controllers;

use core\Controller;

/**
 * контроллер для модуля Product
 */
class Product extends Controller
{
    protected $user;
    protected $shopModel;
    protected $userModel;
    protected $ordersModel;

    public function __construct()
    {
        $this->userModel = new \models\Users();
        $this->shopModel = new \models\Product();
        $this->user = $this->userModel->getCurrentUser();
    }

    /**
     * Відображення початкової сторінки модуля
     */
    public function actionIndex()
    {
        global $Config;
        $title = 'Каталог';
        $lastProduct = $this->shopModel->getLastProduct($Config['ProductCount']);
        return $this->render('index', ['lastProduct' => $lastProduct], [
            'PageTitle' => '',
            'MainTitle' => $title]);
    }

    public function actionBasket()
    {
        $title = 'Мої замовлення';
        global $Config;
        $lastOrder = $this->shopModel->getLastOrder($Config['ProductCount']);
        return $this->render('basket', ['lastOrder' => $lastOrder],
            [
                'PageTitle' => '',
                'MainTitle' => $title]);

    }

    /**
     * перегляд
     */
    public function actionView()
    {
        $id = $_GET['id'];
        $product = $this->shopModel->getProductById($id);
        $title = $product['name_tov'];
        return $this->render('view', ['model' => $product], [
            'PageTitle' => '',
            'MainTitle' => $title]);
    }

    /**
     * замовлення
     */
    function actionOrder()
    {
        $id = $_GET['id'];
        $product = $this->shopModel->getProductById($id);
        $titleForbidden = 'Доступ заборонено';
        if (empty($this->user))
            return $this->render('forbidden', null, [
                'PageTitle' => $titleForbidden,
                'MainTitle' => $titleForbidden]);
        $title = 'Оформлення замовлення';
        if ($this->isPost()) {
            $this->shopModel->OrderProduct($_POST);
            return $this->renderMessage('ok', 'Замовлення створено', null, [
                'PageTitle' => '',
                'MainTitle' => $title]);
        } else
            return $this->render('order', ['model' => $product], [
                'PageTitle' => '',
                'MainTitle' => $title]);
    }

    /**
     * додавання
     */
    public function actionAdd()
    {
        $userModel = new \models\Users();
        $user = $userModel->GetCurrentUser();
        if ($user['login'] !== 'admin@admin.com' && $user['password'] !== '21232f297a57a5a743894a0e4a801fc3')
            return $this->renderError('forbidden', null, [
                'PageTitle' => '',
                'MainTitle' => 'forbidden']);
        $title = 'Додавання нової речі до каталогу';
        if ($this->isPost()) {
            $result = $this->shopModel->AddProduct($_POST);
            if ($result['error'] === false) {
                $allowed_types = ['image/png', 'image/jpeg'];
                if (is_file($_FILES['file']['tmp_name']) && in_array($_FILES['file']['type'], $allowed_types)) {
                    switch ($_FILES['file']['type']) {
                        case 'image/png':
                            $extension = 'png';
                            break;
                        default:
                            $extension = 'jpg';
                    }
                    $name = $result['id'] . '_' . uniqid() . '.' . $extension;
                    move_uploaded_file($_FILES['file']['tmp_name'], 'files/product/' . $name);
                    $this->shopModel->ChangePhoto($result['id'], $name);
                }
                return $this->renderMessage('ok', 'Продукт успішно додано', null, [
                    'PageTitle' => '',
                    'MainTitle' => $title]);
            } else {
                $message = implode('</br>', $result['messages']);
                return $this->renderError($message, ['model' => $_POST], [
                    'PageTitle' => '',
                    'MainTitle' => $title,
                    'MessageText' => '',
                    'MessageClass' => 'danger']);
            }
        } else
            return $this->render('form', ['model' => $_POST], [
                'PageTitle' => '',
                'MainTitle' => $title]);
    }

    /**
     * редагування
     */
    public function actionEdit()
    {
        $id = $_GET['id'];
        $product = $this->shopModel->getProductById($id);
        $userModel = new \models\Users();
        $user = $userModel->GetCurrentUser();
        if ($user['login'] !== 'admin@admin.com' && $user['password'] !== '21232f297a57a5a743894a0e4a801fc3')
            return $this->renderError('forbidden', null, [
                'PageTitle' => '',
                'MainTitle' => 'forbidden']);
        $title = 'Додавання нової речі до каталогу';
        if ($this->isPost()) {
            $result = $this->shopModel->UpdateProduct($_POST, $id);
            if ($result === true) {
                $allowed_types = ['image/png', 'image/jpeg'];
                if (is_file($_FILES['file']['tmp_name']) && in_array($_FILES['file']['type'], $allowed_types)) {
                    switch ($_FILES['file']['type']) {
                        case 'image/png':
                            $extension = 'png';
                            break;
                        default:
                            $extension = 'jpg';
                    }
                    $name = $id . '_' . uniqid() . '.' . $extension;
                    move_uploaded_file($_FILES['file']['tmp_name'], 'files/product/' . $name);
                    $this->shopModel->ChangePhoto($id, $name);
                }
                return $this->renderMessage('ok', 'Форму успішно збережено', null, [
                    'PageTitle' => '',
                    'MainTitle' => $title]);
            } else {
                $message = implode('</br>', $result);
                return $this->renderError($message, ['model' => $product], [
                    'PageTitle' => '',
                    'MainTitle' => $title,
                    'MessageText' => '',
                    'MessageClass' => 'danger']);
            }
        } else
            return $this->render('edit', ['model' => $product], [
                'PageTitle' => '',
                'MainTitle' => $title]);
    }

    /**
     * видалення
     */
    public function actionDelete()
    {
        $id = $_GET['id'];
        $product = $this->shopModel->getProductById($id);
        $title = 'Видалення продукту';
        if (isset($_GET['confirm']) && $_GET['confirm'] == 'yes') {
            if ($this->shopModel->DeleteProduct($id))
                header('Location: /product/');
            else
                return $this->renderMessage('error', 'Помилка видалення', null, [
                    'PageTitle' => '',
                    'MainTitle' => $title]);

        }
        return $this->render('delete', ['model' => $product], [
            'PageTitle' => '',
            'MainTitle' => $title]);
    }


    /**
     * Відображення списку
     */
    public function actionList()
    {
        echo "actionList";
    }

}