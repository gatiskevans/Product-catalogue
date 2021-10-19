<?php

namespace App\Repositories;

use App\Models\Collections\ProductsCollection;
use App\Models\Product;
use App\MySQLConnect\MySQLConnect;
use Carbon\Carbon;
use PDO;
use PDOStatement;
use Ramsey\Uuid\Uuid;

class MySQLProductsRepository extends MySQLConnect implements ProductsRepository
{
    public function getOne(string $id, string $userId): Product
    {
        $sql = "SELECT * FROM products WHERE product_id=? AND user_id=?";
        $statement = $this->connect()->prepare($sql);
        $statement->execute([$id, $userId]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return new Product(
            $result['product_id'],
            $result['title'],
            $result['category'],
            $result['quantity'],
            $result['created_at'],
            $result['user_id'],
            $result['edited_at']
        );
    }

    public function getByTitle(array $product, string $userId): bool
    {
        $sql = "SELECT * FROM products WHERE title=? AND user_id=?";
        $statement = $this->connect()->prepare($sql);
        $statement->execute([$product['title'], $userId]);

        if ($statement->rowCount() > 0) {
            return true;
        }
        return false;
    }

    public function getAll(string $userId): ProductsCollection
    {
        $productsCollection = new ProductsCollection();

        $sql = "SELECT * FROM products WHERE user_id=? ORDER BY created_at DESC";

        $statement = $this->connect()->prepare($sql);
        $statement->execute([$userId]);

        return $this->buildProducts($productsCollection, $statement);
    }

    public function add(array $product, string $userId): void
    {
        $productId = Uuid::uuid4();
        if (isset($product['tags'])) {
            $tags = $this->tags()->getByName($product['tags']);
            $this->tags()->insertTags($productId, $tags);
        }

        $sql = "INSERT INTO products (product_id, title, category, quantity, created_at, user_id) VALUES (?,?,?,?,?,?)";
        $this->connect()->prepare($sql)->execute([
            $productId,
            $product['title'],
            $product['category'],
            $product['quantity'],
            Carbon::now(),
            $userId,
        ]);
    }

    public function edit(array $product, string $id): void
    {
        $tags = $this->tags()->getAll();

        if (isset($product['tags'])) {
            $this->tags()->deleteTags($product['id'], $tags);
            $tags = $this->tags()->getByName($product['tags']);
            $this->tags()->insertTags($product['id'], $tags);
        } else {
            $this->tags()->deleteTags($product['id'], $tags);
        }

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

    public function search(string $query, string $userId, string $sortBy): ProductsCollection
    {
        $productsCollection = new ProductsCollection();

        [$sort, $order] = explode("@", $sortBy);

        if ($query === 'all') {
            $sql = "SELECT * FROM products WHERE user_id=? ORDER BY {$sort} {$order}";
            $statement = $this->connect()->prepare($sql);
            $statement->execute([$userId]);
        } else {
            $sql = "SELECT * FROM products WHERE category=? AND user_id=? ORDER BY {$sort} {$order}";
            $statement = $this->connect()->prepare($sql);
            $statement->execute([$query, $userId]);
        }
        return $this->buildProducts($productsCollection, $statement);
    }

    public function searchByTags(array $tags, string $userId): ProductsCollection
    {
        $productsCollection = new ProductsCollection();
        $tags = "'" . implode("', '", $tags) . "'";

        $sql = "SELECT tag_id FROM tags WHERE tag IN ({$tags})";

        $statement = $this->connect()->prepare($sql);
        $statement->execute();
        $results = $this->fetchAllQueryResults($sql);

        $tagIds = [];
        foreach($results as $result)
        {
            $tagIds[] = $result['tag_id'];
        }

        $tagIds = implode(',', $tagIds);

        $sql = "SELECT product_id FROM product_tag WHERE tag_id IN({$tagIds})";

        $results = $this->fetchAllQueryResults($sql);

        $productIds = [];
        foreach($results as $result)
        {
            $productIds[] = $result['product_id'];
        }

        $productIds = array_unique($productIds);
        $productIds = "'" . implode("', '", $productIds) . "'";

        $sql = "SELECT * FROM products WHERE user_id=? AND product_id IN ({$productIds})";
        $statement = $this->connect()->prepare($sql);
        $statement->execute([$userId]);

        return $this->buildProducts($productsCollection, $statement);
    }

    private function tags(): TagsRepository
    {
        return new MySQLTagsRepository();
    }

    private function buildProducts(ProductsCollection $productsCollection, PDOStatement $sqlStatement): ProductsCollection
    {
        foreach ($sqlStatement->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $tags = $this->tags()->toArray($row['product_id']);
            $productsCollection->add(new Product(
                $row['product_id'],
                $row['title'],
                $row['category'],
                $row['quantity'],
                $row['created_at'],
                $row['user_id'],
                $row['edited_at'],
                $tags
            ));
        }
        return $productsCollection;
    }

    private function fetchAllQueryResults(string $sql, array $execute = []): array
    {
        $statement = $this->connect()->prepare($sql);
        $statement->execute($execute);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}