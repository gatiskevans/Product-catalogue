<?php

namespace App\Repositories;

use App\Models\Collections\ProductsCollection;
use App\Models\Product;
use App\MySQLConnect\MySQLConnect;
use App\Redirect\Redirect;
use Carbon\Carbon;
use PDO;
use Ramsey\Uuid\Uuid;

class MySQLProductsRepository extends MySQLConnect implements ProductsRepository
{
    public function getOne(string $id): Product
    {
        $sql = "SELECT * FROM products WHERE product_id=?";
        $statement = $this->connect()->prepare($sql);
        $statement->execute([$id]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return new Product(
            $result['product_id'],
            $result['title'],
            $result['category'],
            $result['quantity'],
            $result['created_at'],
            $result['edited_at'],
        );
    }

    public function getAll(): ProductsCollection
    {
        $productsCollection = new ProductsCollection();

        $sql = "SELECT * FROM products ORDER BY created_at DESC";

        $statement = $this->connect()->prepare($sql);
        $statement->execute();

        foreach($statement->fetchAll(PDO::FETCH_ASSOC) as $row){
            $productsCollection->add(new Product(
                $row['product_id'],
                $row['title'],
                $row['category'],
                $row['quantity'],
                $row['created_at'],
                $row['edited_at'],
            ));
        }

        return $productsCollection;
    }

    public function add(array $product): void
    {
        $sql = "INSERT INTO products (product_id, title, category, quantity, created_at) VALUES (?,?,?,?,?)";
        $this->connect()->prepare($sql)->execute([
            Uuid::uuid4(),
            $product['title'],
            $product['category'],
            $product['quantity'],
            Carbon::now()
        ]);
    }

    public function edit(array $product, string $id): void
    {
        $sql = "UPDATE products SET title=?, category=?, quantity=?, edited_at=? WHERE product_id=?";
        $this->connect()->prepare($sql)->execute([
            $product['title'],
            $product['category'],
            $product['quantity'],
            Carbon::now(),
            $id
        ]);
    }

    public function delete(string $id): void
    {
        $sql = "DELETE FROM products WHERE product_id=?";
        $this->connect()->prepare($sql)->execute([$id]);
    }

    public function search(array $query): ProductsCollection
    {
        $productsCollection = new ProductsCollection();

        $query = $query['search'] ?? null;
        if(empty($query)) Redirect::to('/');

        $sql = "SELECT * FROM products WHERE category=?";
        $statement = $this->connect()->prepare($sql);
        $statement->execute([$query]);

        if($statement->rowCount() <= 0) Redirect::to('/');

        foreach($statement->fetchAll(PDO::FETCH_ASSOC) as $row){
            $productsCollection->add(new Product(
                $row['product_id'],
                $row['title'],
                $row['category'],
                $row['quantity'],
                $row['created_at'],
                $row['edited_at'],
            ));
        }

        return $productsCollection;
    }
}