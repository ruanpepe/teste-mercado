<?php

namespace App\Model\Entity;

use PDOStatement;

class Product
{
    public int $id;
    public string $name;
    public float $price;

    /**
     * Retorna todos os produtos
     * 
     * @return PDOStatement|false
     */
    public static function selectAll(): PDOStatement|false
    {
        try {
            $sql = '
                SELECT * FROM products';
            $statement =  DB_CONNECTION->prepare($sql);
            $statement->execute();
            return $statement;
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }

    /**
     * Retorna todos os produtos com os impostos
     * 
     * @return PDOStatement|false
     */
    public static function selectAllWithTaxes(): PDOStatement|false
    {
        try {
            $sql = '
                SELECT products.*, SUM(t.percent) as tax
                FROM products
                
                LEFT JOIN product_type pt ON products.id = pt.id_product
                LEFT JOIN types ON pt.id_type = types.id
                LEFT JOIN taxes t ON t.id_type = types.id
                
                GROUP BY products.id';
            $statement =  DB_CONNECTION->prepare($sql);
            $statement->execute();
            return $statement;
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }

    /**
     * Retorna um produto pelo id
     * 
     * @param int $id
     * 
     * @return PDOStatement|false
     */
    public static function selectById(int $id): PDOStatement|false
    {
        try {
            $sql = '
                SELECT * FROM products 
                WHERE id = ' . $id;
            $statement =  DB_CONNECTION->prepare($sql);
            $statement->execute();
            return $statement;
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }

    /**
     * Insere um novo produto
     * 
     * @return bool
     */
    public function create(): bool
    {
        try {
            $sql = '
                INSERT INTO products (
                    name, 
                    price) 
                VALUES (
                    :name, 
                    ' . $this->price . ')';
            $statement = DB_CONNECTION->prepare($sql);
            $statement->bindParam(':name', $this->name);
            $statement->execute();
            return true;
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }

    /**
     * Atualiza produto
     * 
     * @return bool
     */
    public function update(): bool
    {
        try {
            $sql = '
                UPDATE products 
                SET 
                    name = :name, 
                    price = ' . $this->price . ' 
                WHERE id = ' . $this->id;
            $statement =  DB_CONNECTION->prepare($sql);
            $statement->bindParam(':name', $this->name);
            $statement->execute();
            return true;
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }

    /**
     * Apaga um produto
     * 
     * @return bool
     */
    public function delete(): bool
    {
        try {
            $sql = '
                DELETE FROM products 
                WHERE id = ' . $this->id;
            $statement =  DB_CONNECTION->prepare($sql);
            $statement->execute();
            return true;
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }
}
