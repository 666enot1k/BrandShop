<?php

namespace controllers;

use core\Controller;

class Site extends Controller
{
    public function actionIndex()
    {
        $result = [
            'Title' => 'Назва',
            'Content' => 'Опис'
        ];
        return $this->render('index', null, [
            'MainTitle' => 'Головна сторінка',
            'PageTitle' => ''
        ]);

    }

}