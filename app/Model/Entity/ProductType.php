<?php

namespace App\Model\Entity;

use PDOStatement;

class ProductType
{
    public int $id;
    public int $id_product;
    public int $id_type;

    /**
     * Retorna todos os tipos vinculados a um produto
     * 
     * @param int $id_product
     * 
     * @return PDOStatement|false
     */
    public static function selectTypesFromProduct(int $id_product): PDOStatement|false
    {
        try {
            $sql = '
                SELECT * FROM product_type 
                WHERE id_product = ' . $id_product;
            $statement =  DB_CONNECTION->prepare($sql);
            $statement->execute();
            return $statement;
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }

    /**
     * Insere um novo tipo
     * 
     * @return bool
     */
    public function create(): bool
    {
        try {
            $sql = '
                INSERT INTO product_type (
                    id_product, 
                    id_type) 
                VALUES (
                    ' . $this->id_product . ', 
                    ' . $this->id_type . ')';
            $statement = DB_CONNECTION->prepare($sql);
            $statement->execute();
            return true;
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }

    /**
     * Desvincula um produto de um tipo
     * 
     * @return bool
     */
    public function delete(): bool
    {
        try {
            $sql = '
                DELETE FROM product_type 
                WHERE 
                    id_product = ' . $this->id_product . ' 
                    AND id_type = ' . $this->id_type;
            $statement =  DB_CONNECTION->prepare($sql);
            $statement->execute();
            return true;
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }
}
