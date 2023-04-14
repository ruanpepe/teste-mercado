<?php

namespace App\Utils;

class View
{
    private static $vars = [];

    /**
     * Define os dados iniciais da classe
     * @param array $vars
     * 
     * @return void
     */
    public static function init(array $vars = []): void
    {
        self::$vars = $vars;
    }

    /**
     * Retorna o conteúdo de uma view
     * 
     * @param string $view
     * 
     * @return string
     */
    private static function getViewContent(string $view): string
    {
        $file = __DIR__ . '/../../public/view/' . $view . '.html';
        return file_exists($file) ? file_get_contents($file) : '';
    }

    /**
     * Renderiza o conteúdo de uma view
     * 
     * @param string $view
     * @param array $vars
     * 
     * @return string
     */
    public static function render(string $view, array $vars = []): string
    {
        $viewContent = self::getViewContent($view);

        $vars = array_merge(self::$vars, $vars);

        $vars_keys = array_keys($vars);
        $vars_keys = array_map(function (string $key) {
            return '{{' . $key . '}}';
        }, $vars_keys);

        return str_replace($vars_keys, array_values($vars), $viewContent);
    }
}
