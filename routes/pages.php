<?php

use \App\Http\Response;
use \App\Controller\Pages;

$router->get('/', [
    function () {
        return new Response(200, Pages\HomeController::getHome());
    }
]);

// Rotas de tipos
$router->get('/tipo/deletar/{id}', [
    function ($id) {
        return new Response(200, Pages\TypeController::delete($id));
    }
]);
$router->get('/tipo/editar/{id}', [
    function ($id) {
        return new Response(200, Pages\TypeController::edit($id));
    }
]);
$router->post('/tipo/atualizar', [
    function ($request) {
        return new Response(200, Pages\TypeController::update($request));
    }
]);
$router->get('/tipo', [
    function () {
        return new Response(200, Pages\TypeController::list());
    }
]);
$router->post('/tipo', [
    function ($request) {
        return new Response(200, Pages\TypeController::create($request));
    }
]);

// Rotas de impostos
$router->get('/imposto/deletar/{id}', [
    function ($id) {
        return new Response(200, Pages\TaxController::delete($id));
    }
]);
$router->post('/imposto', [
    function ($request) {
        return new Response(200, Pages\TaxController::create($request));
    }
]);

// Rotas de produtos
$router->get('/produto/{productId}/vinculartipo/{typeId}', [
    function ($productId, $typeId) {
        return new Response(200, Pages\ProductController::bindType($productId, $typeId));
    }
]);
$router->get('/produto/{productId}/desvinculartipo/{typeId}', [
    function ($productId, $typeId) {
        return new Response(200, Pages\ProductController::unbindType($productId, $typeId));
    }
]);
$router->get('/produto/deletar/{id}', [
    function ($id) {
        return new Response(200, Pages\ProductController::delete($id));
    }
]);
$router->get('/produto/editar/{id}', [
    function ($id) {
        return new Response(200, Pages\ProductController::edit($id));
    }
]);
$router->post('/produto/atualizar', [
    function ($request) {
        return new Response(200, Pages\ProductController::update($request));
    }
]);
$router->get('/produto', [
    function () {
        return new Response(200, Pages\ProductController::list());
    }
]);
$router->post('/produto', [
    function ($request) {
        return new Response(200, Pages\ProductController::create($request));
    }
]);

// Rotas de vendas
$router->get('/venda/deletar/{id}', [
    function ($id) {
        return new Response(200, Pages\SaleController::delete($id));
    }
]);
$router->get('/venda/ver/{id}', [
    function ($id) {
        return new Response(200, Pages\SaleController::show($id));
    }
]);
$router->get('/venda/nova', [
    function () {
        return new Response(200, Pages\SaleController::new());
    }
]);
$router->post('/venda/nova', [
    function ($request) {
        return new Response(200, Pages\SaleController::create($request));
    }
]);
$router->get('/venda', [
    function () {
        return new Response(200, Pages\SaleController::list());
    }
]);
