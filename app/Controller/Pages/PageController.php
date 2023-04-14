<?php

namespace App\Controller\Pages;

use \App\Utils\View;

class PageController
{
    /**
     * Retorna a view do layout principal
     * 
     * @param string $title
     * @param string $content
     * 
     * @return string
     */
    public static function getPage(string $title, string $content): string
    {
        return View::render('pages/page', [
            'title' => $title,
            'content' => $content,

            'header' => self::getHeader($title),
            'footer' => self::getFooter(),
        ]);
    }

    /**
     * Retorna a view do header do layout principal
     * 
     * @param ?string $title
     * 
     * @return string
     */
    public static function getHeader(string $title = ''): string
    {
        return View::render('pages/header', [
            'title' => $title
        ]);
    }

    /**
     * Retorna a view do footer do layout principal
     * 
     * @return string
     */
    public static function getFooter(): string
    {
        return View::render('pages/footer');
    }
}
