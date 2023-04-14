<?php

namespace App\Controller\Pages;

use App\Http\Request;
use App\Utils\View;
use App\Model\Entity\Tax;

class TaxController extends PageController
{
    /**
     * Retorna as linhas de tipos
     * 
     * @param int $idType
     * 
     * @return string
     */
    public static function getLines(int $idType): string
    {
        $items = '';

        $results = Tax::selectAll($idType);
        while ($tax = $results->fetchObject(Tax::class)) {
            $items .= View::render('pages/type/tax/item', [
                'id' => $tax->id,
                'name' => $tax->name,
                'percent' => $tax->percent,
            ]);
        }

        return $items;
    }

    /**
     * Cadastra um novo imposto
     * 
     * @param Request $request
     * 
     * @return string
     */
    public static function create(Request $request): string
    {
        $postVars = $request->getPostVars();

        $newTax = new Tax();
        $newTax->id_type = $postVars['typeId'];
        $newTax->name = $postVars['taxName'];
        $newTax->percent = $postVars['taxPercent'];
        $newTax->create();

        return TypeController::edit($newTax->id_type);
    }

    /**
     * Deleta um imposto
     * 
     * @param int $id
     * 
     * @return string
     */
    public static function delete(int $id): string
    {
        $tax = new Tax();
        $result = Tax::selectById($id);
        $tax = $result->fetchObject(Tax::class);

        if (!$tax) {
            return TypeController::list();
        }

        $result = $tax->delete();

        return TypeController::edit($tax->id_type);
    }
}
