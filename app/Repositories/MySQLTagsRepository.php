<?php

namespace App\Repositories;

use App\DD;
use App\Models\Collections\TagsCollection;
use App\Models\Tag;
use App\MySQLConnect\MySQLConnect;
use PDO;

class MySQLTagsRepository extends MySQLConnect implements TagsRepository
{
    public function getAll(): TagsCollection
    {
        $tagsCollection = new TagsCollection();

        $sql = "SELECT * FROM tags";
        $statement = $this->connect()->prepare($sql);
        $statement->execute();

        foreach($statement->fetchAll(PDO::FETCH_ASSOC) as $row)
        {
            $tagsCollection->add(new Tag(
                $row['tag_id'],
                $row['tag']
            ));
        }

        return $tagsCollection;
    }

    public function getProductTags(string $id): ?TagsCollection
    {
        $tagsCollection = new TagsCollection();

        $sql = "SELECT tag_id FROM product_tag WHERE product_id=?";

        $statement = $this->connect()->prepare($sql);
        $statement->execute([$id]);

        if($statement->rowCount() <= 0) return null;
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        $tags = [];
        foreach($results as $row)
        {
            foreach($row as $value)
            {
                $tags[] = $value;
            }
        }
        $tags = implode(',', $tags);

        $sql = "SELECT * FROM tags WHERE tag_id IN ({$tags})";
        $statement = $this->connect()->prepare($sql);
        $statement->execute();

        foreach($statement->fetchAll(PDO::FETCH_ASSOC) as $row)
        {
            $tagsCollection->add(new Tag(
                $row['tag_id'],
                $row['tag'],
            ));
        }

        return $tagsCollection;
    }

    public function toArray(string $id): ?array
    {
        $tagsArray = [];
        $tags = $this->getProductTags($id);
        if(isset($tags))
        {
            foreach($tags->getTags() as $tag)
            {
                $tagsArray[] = $tag;
            }
            return $tagsArray;
        }
        return null;
    }

    public function getByName(array $tags): TagsCollection
    {
        $tagsCollection = new TagsCollection();

        $tags = "'" . implode("', '", $tags) . "'";

        $sql = "SELECT * FROM tags WHERE tag IN ({$tags})";

        $statement = $this->connect()->prepare($sql);
        $statement->execute();

        foreach($statement->fetchAll(PDO::FETCH_ASSOC) as $row)
        {
            $tagsCollection->add(new Tag(
                $row['tag_id'],
                $row['tag']
            ));
        }

        return $tagsCollection;
    }

    public function deleteTags(string $productId, TagsCollection $tags): void
    {
        /** @var Tag $tag */
        foreach($tags->getTags() as $tag)
        {
            $sql = "DELETE FROM product_tag WHERE product_id=? AND tag_id=?";
            $statement = $this->connect()->prepare($sql);
            $statement->execute([
                $productId,
                $tag->getTagId()
            ]);
        }
    }

    public function insertTags(string $productId, TagsCollection $tags): void
    {
        /** @var Tag $tag */
        foreach($tags->getTags() as $tag)
        {
            $sql = "INSERT INTO product_tag VALUES(?,?)";
            $statement = $this->connect()->prepare($sql);
            $statement->execute([
                $productId,
                $tag->getTagId()
            ]);
        }
    }
}