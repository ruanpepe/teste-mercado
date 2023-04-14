<?php

namespace App\Controller\Pages;

use \App\Utils\View;

class HomeController extends PageController
{
    /**
     * Retorna a view da página principal
     * 
     * @return string
     */
    public static function getHome(): string
    {
        $content = View::render('pages/home');

        return parent::getPage('Página Inicial', $content);
    }
}
