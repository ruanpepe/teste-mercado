<?php

namespace App\Http;

class Response
{
    private int $httpCode = 200;
    private array $headers = [];
    private string $contentType = 'text/html';
    private mixed $content;

    /**
     * Construtor da classe
     * 
     * @param int $httpCode
     * @param mixed $content
     * @param string $contentType
     */
    public function __construct(int $httpCode, mixed $content, string $contentType = 'text/html')
    {
        $this->httpCode = $httpCode;
        $this->content = $content;
        $this->setContentType($contentType);
    }

    /**
     * Atera o content type do response
     * 
     * @param string $contentType
     * 
     * @return void
     */
    public function setContentType(string $contentType): void
    {
        $this->contentType = $contentType;
        $this->addHeader('Content-Type', $contentType);
    }

    /**
     * Adiciona registro no cabeçalho do response
     * 
     * @param string $key
     * @param string $value
     * 
     * @return void
     */
    public function addHeader(string $key, string $value): void
    {
        $this->headers[$key] = $value;
    }

    /**
     * Envia os headers para o navegador
     * 
     * @return void
     */
    private function sendHeaders(): void
    {
        http_response_code($this->httpCode);
        foreach ($this->headers as $key => $value) {
            header($key . ': ' . $value);
        }
    }

    /**
     * Envia response ao usuário
     * 
     * @return void
     */
    public function sendResponse(): void
    {
        $this->sendHeaders();

        if ($this->contentType == 'text/html') {
            echo $this->content;
            return;
        }
    }
}
