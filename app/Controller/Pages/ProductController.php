<?php

namespace App\Controller\Pages;

use App\Http\Request;
use App\Utils\View;
use App\Model\Entity\Product;
use App\Model\Entity\ProductType;

class ProductController extends PageController
{
    /**
     * Retorna as linhas de produtos
     * 
     * @return string
     */
    public static function getLines(): string
    {
        $itens = '';

        $results = Product::selectAll();
        while ($product = $results->fetchObject(Product::class)) {
            $itens .= View::render('pages/product/item', [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
            ]);
        }

        return $itens;
    }

    /**
     * Retorna as linhas de produtos com os impostos
     * 
     * @return string
     */
    public static function getLinesWithTaxes(): string
    {
        $itens = '';

        $results = Product::selectAllWithTaxes();
        while ($product = $results->fetchObject(Product::class)) {
            $itens .= View::render('pages/product/item_sale', [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price ?? 0,
                'tax' => $product->tax ?? 0,
            ]);
        }

        return $itens;
    }

    /**
     * Retorna a view da lista de produtos
     * 
     * @return string
     */
    public static function list(): string
    {
        $content = View::render('pages/product/list', [
            'items' => self::getLines()
        ]);
        return parent::getPage('Novo produto', $content);
    }

    /**
     * Cadastra um novo produto
     * 
     * @param Request $request
     * 
     * @return string
     */
    public static function create(Request $request): string
    {
        $postVars = $request->getPostVars();

        $newProduct = new Product();
        $newProduct->name = $postVars['name'];
        $newProduct->price = $postVars['price'];
        $newProduct->create();

        return self::list();
    }

    /**
     * Retorna a view de edição de produto
     * 
     * @param int $id
     * 
     * @return string
     */
    public static function edit(int $id): string
    {
        $result = Product::selectById($id);
        $product = $result->fetchObject(Product::class);
        $content = View::render('pages/product/edit', [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'typeLines' => TypeController::getLinesToProduct($id),
        ]);
        return parent::getPage('Editar produto ' . $id, $content);
    }

    /**
     * Vincula produto ao tipo
     * 
     * @param int $productId
     * @param int $typeId
     * 
     * @return string
     */
    public static function bindType(int $productId, int $typeId): string
    {
        $productType = new ProductType();
        $productType->id_product = $productId;
        $productType->id_type = $typeId;
        $productType->create();

        return self::edit($productId);
    }

    /**
     * Desvincula produto do tipo
     * 
     * @param int $productId
     * @param int $typeId
     * 
     * @return string
     */
    public static function unbindType(int $productId, int $typeId): string
    {
        $productType = new ProductType();
        $productType->id_product = $productId;
        $productType->id_type = $typeId;
        $productType->delete();

        return self::edit($productId);
    }

    /**
     * Atualiza um produto
     * 
     * @param Request $request
     * 
     * @return string
     */
    public static function update(Request $request): string
    {
        $postVars = $request->getPostVars();
        $product = new Product();
        $product->id = $postVars['id'];
        $product->name = $postVars['name'];
        $product->price = $postVars['price'];
        $product->update();

        return self::list();
    }

    /**
     * Deleta um produto
     * 
     * @param int $id
     * 
     * @return string
     */
    public static function delete(int $id): string
    {
        $product = new Product();
        $product->id = $id;
        $product->delete();

        return self::list();
    }
}
