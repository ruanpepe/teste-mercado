<?php

namespace App\Controller\Pages;

use App\Http\Request;
use App\Model\Entity\ProductType;
use App\Utils\View;
use App\Model\Entity\Type;

class TypeController extends PageController
{
    /**
     * Retorna as linhas de tipos
     * 
     * @return string
     */
    public static function getLines(): string
    {
        $items = '';

        $results = Type::selectAll();
        while ($type = $results->fetchObject(Type::class)) {
            $items .= View::render('pages/type/item', [
                'id' => $type->id,
                'name' => $type->name,
            ]);
        }

        return $items;
    }

    /**
     * Retorna as linhas de tipos
     * 
     * @param int $idProduct
     * 
     * @return string
     */
    public static function getLinesToProduct(int $idProduct): string
    {
        $items = '';

        $bindedTypes = [];
        foreach ((ProductType::selectTypesFromProduct($idProduct))->fetchAll() as $type) {
            array_push($bindedTypes, $type['id_type']);
        }

        $results = Type::selectAllWithTaxes($bindedTypes);
        while ($type = $results->fetchObject(Type::class)) {
            $items .= View::render('pages/product/item_type', [
                'productId' => $idProduct,
                'typeId' => $type->id,
                'name' => $type->name,
                'tax' => $type->tax ?? '0',
                'binded' => in_array($type->id, $bindedTypes) ? 'checked' : '',
            ]);
        }

        return $items;
    }

    /**
     * Retorna a view da lista de tipos
     * 
     * @return string
     */
    public static function list(): string
    {
        $content = View::render('pages/type/list', [
            'items' => self::getLines()
        ]);
        return parent::getPage('Novo tipo', $content);
    }

    /**
     * Cadastra um novo tipo
     * 
     * @param Request $request
     * 
     * @return string
     */
    public static function create(Request $request): string
    {
        $postVars = $request->getPostVars();

        $novoTipo = new Type();
        $novoTipo->name = $postVars['name'];
        $novoTipo->create();

        return self::list();
    }

    /**
     * Retorna a view de edição de tipo
     * 
     * @param int $id
     * 
     * @return string
     */
    public static function edit(int $id): string
    {
        $result = Type::selectById($id);
        $type = $result->fetchObject(Type::class);
        $content = View::render('pages/type/edit', [
            'id' => $type->id,
            'name' => $type->name,
            'taxLines' => TaxController::getLines($type->id),
        ]);
        return parent::getPage('Editar tipo ' . $id, $content);
    }

    /**
     * Atualiza um tipo
     * 
     * @param Request $request
     * 
     * @return string
     */
    public static function update(Request $request): string
    {
        $postVars = $request->getPostVars();
        $type = new Type();
        $type->id = $postVars['id'];
        $type->name = $postVars['name'];
        $type->update();

        return self::list();
    }

    /**
     * Deleta um tipo
     * 
     * @param int $id
     * 
     * @return string
     */
    public static function delete(int $id): string
    {
        $type = new Type();
        $type->id = $id;
        $type->delete();

        return self::list();
    }
}
