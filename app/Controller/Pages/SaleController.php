<?php

namespace App\Controller\Pages;

use App\Controller\Pages\ProductController;
use App\Http\Request;
use App\Model\Entity\Product;
use App\Model\Entity\ProductType;
use App\Utils\View;
use App\Model\Entity\Sale;
use App\Model\Entity\SaleProduct;
use DateTime;

class SaleController extends PageController
{
    /**
     * Retorna a view da de nova venda
     * 
     * @return string
     */
    public static function new(): string
    {
        $content = View::render('pages/sale/new', [
            'productLines' => ProductController::getLinesWithTaxes(),
        ]);
        return parent::getPage('Nova venda', $content);
    }

    /**
     * Cadastra uma nova venda
     * 
     * @param Request $request
     * 
     * @return string
     */
    public static function create(Request $request): string
    {
        $postVars = $request->getPostVars();
        $postVars['productTotalPrice'] = [];
        $postVars['productTotalTax'] = [];
        $postVars['productTotalPriceWithTax'] = [];

        $productsIds = [];
        foreach ($postVars['productAmount'] as $key => $value) {
            if (!$value) {
                unset($postVars['productName'][$key]);
                unset($postVars['productPrice'][$key]);
                unset($postVars['productTax'][$key]);
                unset($postVars['productAmount'][$key]);
                continue;
            }
            $productsIds[] = $key;
            $postVars['productTotalPrice'][$key] = $postVars['productPrice'][$key] * $postVars['productAmount'][$key];
            $postVars['productTotalTax'][$key] = ($postVars['productTax'][$key] / 100 * $postVars['productTotalPrice'][$key]);
            $postVars['productTotalPriceWithTax'][$key] = $postVars['productTotalPrice'][$key] + $postVars['productTotalTax'][$key];
        }

        $newSale = new Sale();
        $newSale->products_prices = array_sum($postVars['productTotalPrice']);
        $newSale->products_taxes = array_sum($postVars['productTotalTax']);
        $newSale->final_price = array_sum($postVars['productTotalPriceWithTax']);
        $result = $newSale->create();
        $newSale->id = $result->fetchAll()['0']['id'];

        foreach ($productsIds as $productId) {
            $saleProduct = new SaleProduct();
            $saleProduct->id_sale = $newSale->id;
            $saleProduct->id_product = $productId;
            $saleProduct->product_name = $postVars['productName'][$productId];
            $saleProduct->product_amount = $postVars['productAmount'][$productId];
            $saleProduct->product_price = $postVars['productPrice'][$productId];
            $saleProduct->product_tax = $postVars['productTax'][$productId];
            $saleProduct->product_final_price = ($saleProduct->product_tax / 100 * $saleProduct->product_price) + $saleProduct->product_price;
            $saleProduct->create();
        }

        return self::show($newSale->id);
    }

    /**
     * Retorna a view da lista de vendas
     * 
     * @return string
     */
    public static function list(): string
    {
        $content = View::render('pages/sale/list', [
            'items' => self::getLines()
        ]);
        return parent::getPage('Lista de vendas', $content);
    }

    /**
     * Retorna as linhas de vendas
     * 
     * @return string
     */
    public static function getLines(): string
    {
        $itens = '';

        $results = Sale::selectAll();
        while ($sale = $results->fetchObject(Sale::class)) {
            $itens .= View::render('pages/sale/item', [
                'id' => $sale->id,
                'created_at' => $sale->created_at,
                'products_prices' => $sale->products_prices,
                'products_taxes' => $sale->products_taxes,
                'final_price' => $sale->final_price,
            ]);
        }

        return $itens;
    }

    /**
     * Retorna a view de exibição de venda
     * 
     * @param int $id
     * 
     * @return string
     */
    public static function show(int $id): string
    {
        $result = Sale::selectById($id);
        $sale = $result->fetchObject(Sale::class);
        $content = View::render('pages/sale/show', [
            'id' => $sale->id,
            'created_at' => $sale->created_at,
            'products_prices' => $sale->products_prices,
            'products_taxes' => $sale->products_taxes,
            'final_price' => $sale->final_price,
            'productLines' => self::getProductLines($id),
        ]);
        return parent::getPage('Ver venda ' . $id, $content);
    }

    /**
     * Retorna as linhas de produtos vendidos
     * 
     * @param int $idSale
     * 
     * @return string
     */
    public static function getProductLines(int $idSale): string
    {
        $items = '';

        $results = SaleProduct::selectAllBySale($idSale);
        while ($saleProduct = $results->fetchObject(SaleProduct::class)) {
            $items .= View::render('pages/sale/sale_product/item', [
                'product_name' => $saleProduct->product_name,
                'product_amount' => $saleProduct->product_amount,
                'product_price' => $saleProduct->product_price,
                'product_tax' => $saleProduct->product_tax,
                'product_final_price' => $saleProduct->product_final_price,
            ]);
        }

        return $items;
    }

    /**
     * Deleta uma venda
     * 
     * @param int $id
     * 
     * @return string
     */
    public static function delete(int $id): string
    {
        $sale = new Sale();
        $sale->id = $id;
        $sale->delete();

        return self::list();
    }
}
