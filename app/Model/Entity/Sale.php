<?php

namespace App\Model\Entity;

use PDOStatement;

class Sale
{
    public int $id;
    public string $created_at;
    public float $products_prices;
    public float $products_taxes;
    public float $final_price;

    /**
     * Retorna todas as vendas
     * 
     * @return PDOStatement|false
     */
    public static function selectAll(): PDOStatement|false
    {
        try {
            $sql = '
                SELECT * FROM sales';
            $statement =  DB_CONNECTION->prepare($sql);
            $statement->execute();
            return $statement;
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }

    /**
     * Insere um novo registro na tabela vendas
     * 
     * @return PDOStatement|false
     */
    public function create(): PDOStatement|false
    {
        try {
            $sql = '
                INSERT INTO sales (
                    products_prices, 
                    products_taxes, 
                    final_price) 
                VALUES (
                    ' . $this->products_prices . ', 
                    ' . $this->products_taxes . ', 
                    ' . $this->final_price . ') 
                RETURNING id';
            $statement = DB_CONNECTION->prepare($sql);
            $statement->execute();
            return $statement;
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }

    /**
     * Retorna uma venda pelo id
     * 
     * @param int $id
     * 
     * @return PDOStatement|false
     */
    public static function selectById(int $id): PDOStatement|false
    {
        try {
            $sql = '
                SELECT * FROM sales 
                WHERE id = ' . $id;
            $statement =  DB_CONNECTION->prepare($sql);
            $statement->execute();
            return $statement;
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }

    /**
     * Apaga uma venda
     * 
     * @return bool
     */
    public function delete(): bool
    {
        try {
            $sql = '
                DELETE FROM sales 
                WHERE id = ' . $this->id;
            $statement =  DB_CONNECTION->prepare($sql);
            $statement->execute();
            return true;
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }
}
