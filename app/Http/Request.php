<?php

namespace App\Http;

class Request
{

    private string $httpMethod;
    private string $uri;
    private array $queryParams = [];
    private array $postVars = [];
    private array $headers = [];

    /**
     * Construtor da classe
     */
    public function __construct()
    {
        $this->queryParams = $_GET ?? [];
        $this->postVars = $_POST ?? [];
        $this->headers = getallheaders();

        $this->httpMethod = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->uri = $_SERVER['REQUEST_URI'] ?? '';
    }

    /**
     * Retorna o método HTTP da requisição
     * 
     * @return string
     */
    public function getHttpMethod(): string
    {
        return $this->httpMethod;
    }

    /**
     * Retorna URI da requisição
     * 
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * Retorna parametros da URL da requisição
     * 
     * @return array
     */
    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    /**
     * Retorna as variáveis do POST da requisição
     * 
     * @return array
     */
    public function getPostVars(): array
    {
        return $this->postVars;
    }

    /**
     * Retorna os readers da requisição
     * 
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }
}
