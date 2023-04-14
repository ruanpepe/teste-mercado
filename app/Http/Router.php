<?php

namespace App\Http;

use \Closure;
use \Exception;
use \ReflectionFunction;

class Router
{
    private string $url = '';
    private string $prefix = '';
    private array $routes = [];
    private Request $request;

    /**
     * Construtor da classe
     * 
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->request = new Request();
    }

    /**
     * Define o prefixo das rotas
     * 
     * @return void
     */
    private function setPrefix(): void
    {
        $parseUrl = parse_url($this->url);
        $this->prefix = $parseUrl['path'] ?? '';
    }

    /**
     * Define rotas de GET
     * 
     * @param string $method
     * @param string $route
     * @param array $params
     * 
     * @return void
     */
    public function addRoute(string $method, string $route, array $params = []): void
    {
        foreach ($params as $key => $value) {
            if ($value instanceof Closure) {
                $params['controller'] = $value;
                unset($params[$key]);
                continue;
            }
        }

        $params['variables'] = [];
        $VariablePattern = '/{(.*?)}/';;
        if (preg_match_all($VariablePattern, $route, $matches)) {
            $route = preg_replace($VariablePattern, '(.*?)', $route);
            $params['variables'] = $matches[1];
        }

        $routePattern = '/^' . str_replace('/', '\/', $route) . '$/';

        $this->routes[$routePattern][$method] = $params;
    }

    /**
     * Define rotas de GET
     * 
     * @param string $route
     * @param array $params
     * 
     * @return void
     */
    public function get(string $route, array $params = []): void
    {
        $this->addRoute('GET', $route, $params);
    }

    /**
     * Define rotas de POST
     * 
     * @param string $route
     * @param array $params
     * 
     * @return void
     */
    public function post(string $route, array $params = []): void
    {
        $this->addRoute('POST', $route, $params);
    }

    /**
     * Define rotas de PUT
     * 
     * @param string $route
     * @param array $params
     * 
     * @return void
     */
    public function put(string $route, array $params = []): void
    {
        $this->addRoute('PUT', $route, $params);
    }

    /**
     * Define rotas de DELETE
     * 
     * @param string $route
     * @param array $params
     * 
     * @return void
     */
    public function delete(string $route, array $params = []): void
    {
        $this->addRoute('DELETE', $route, $params);
    }

    /**
     * Retorna a Uri da rota atual sem o prefixo
     * 
     * @return string
     */
    private function getUri(): string
    {
        $uri = $this->request->getUri();
        $explodedUri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];
        return end($explodedUri);
    }

    /**
     * Retorna os dados da rota atual
     * 
     * @return array
     */
    private function getRoute(): array
    {
        $uri = $this->getUri();
        $httpMethod = $this->request->getHttpMethod();
        foreach ($this->routes as $routePattern => $methods) {
            if (preg_match($routePattern, $uri, $matches)) {
                if (isset($methods[$httpMethod])) {
                    unset($matches[0]);

                    $keys = $methods[$httpMethod]['variables'];
                    $methods[$httpMethod]['variables'] = array_combine($keys, $matches);
                    $methods[$httpMethod]['variables']['request'] = $this->request;

                    return $methods[$httpMethod];
                }

                throw new Exception("Método não permitido", 405);
            }
        }

        throw new Exception("URL não encontrada", 404);
    }

    /**
     * Executa a rota atual
     * 
     * @return Response
     */
    public function run(): Response
    {
        try {
            $route = $this->getRoute();

            if (!isset($route['controller'])) {
                throw new Exception('A URL não pode ser processada', 500);
            }

            $arguments = [];
            $reflection = new ReflectionFunction($route['controller']);
            foreach ($reflection->getParameters() as $parameter) {
                $name = $parameter->getName();
                $arguments[$name] = $route['variables'][$name] ?? '';
            }

            return call_user_func_array($route['controller'], $arguments);
        } catch (Exception $e) {
            return new Response($e->getCode(), $e->getMessage());
        }
    }
}
