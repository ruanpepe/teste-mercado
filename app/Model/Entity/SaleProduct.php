<?php

namespace App\Model\Entity;

use PDOStatement;

class SaleProduct
{
    public int $id;
    public int $id_sale;
    public ?int $id_product;
    public string $product_name;
    public int $product_amount;
    public float $product_price;
    public float $product_tax;
    public float $product_final_price;


    /**
     * Retorna todos os produtos de uma venda
     * 
     * @param int $saleId
     * 
     * @return PDOStatement|false
     */
    public static function selectAllBySale(int $saleId): PDOStatement|false
    {
        try {
            $sql = '
                SELECT * FROM sale_product 
                WHERE id_sale = ' . $saleId;
            $statement =  DB_CONNECTION->prepare($sql);
            $statement->execute();
            return $statement;
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }

    /**
     * Insere uma nova venda
     * 
     * @return PDOStatement|false
     */
    public function create(): PDOStatement|false
    {
        try {
            $sql = '
                INSERT INTO sale_product (
                    id_sale, 
                    id_product, 
                    product_name, 
                    product_amount, 
                    product_price, 
                    product_tax, 
                    product_final_price) 
                VALUES (
                    ' . $this->id_sale . ',
                    ' . ($this->id_product ?? null) . ',
                    :product_name, 
                    ' . $this->product_amount . ',
                    ' . $this->product_price . ',
                    ' . $this->product_tax . ',
                    ' . $this->product_final_price . ')';
            $statement = DB_CONNECTION->prepare($sql);
            $statement->bindParam(':product_name', $this->product_name);
            $statement->execute();
            return $statement;
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }
}
