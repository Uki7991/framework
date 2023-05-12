<?php

namespace App\Models;

use App\Exceptions\ModelNotFoundException;
use App\Exceptions\NotFoundHttpException;

#[\AllowDynamicProperties]
class Model
{
    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function save()
    {
        db()->insert($this->table, array_intersect_key(get_object_vars($this), array_flip($this->fillable)));

        $this->id = db()->lastInsertId();

        return $this;
    }

    public function update($data)
    {
        db()->update($this->table, array_intersect_key($data, array_flip($this->fillable)), ['id' => $this->id]);

        foreach (array_intersect_key($data, array_flip($this->fillable)) as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function refresh()
    {
        return self::firstOrFail($this->id);
    }

    private static function getQuery(string $sqlQuery, array $params)
    {
        foreach (db()->fetchAllAssociative($sqlQuery, $params) as $item) {
            yield new static($item);
        }
    }

    public static function get(string $sqlQuery, array $params)
    {
        $data = [];

        foreach (self::getQuery($sqlQuery, $params) as $item) {
            $data[] = $item;
        }

        return $data;
    }

    /**
     * @param string $sqlQuery
     * @param array $params
     * @return static|null
     * @throws \Doctrine\DBAL\Exception
     */
    public static function getFirst(string $sqlQuery, array $params)
    {
        $data = db()->fetchAssociative($sqlQuery, $params);

        if (!$data) {
            return null;
        }

        return new static($data);
    }



    private static function allDB()
    {
        foreach (db()->fetchAllAssociative("SELECT * FROM ". (new static())->table) as $item) {
            yield new static($item);
        }
    }

    public static function all()
    {
        $data = [];

        foreach (self::allDB() as $item) {
            $data[] = $item;
        }

        return $data;
    }

    public static function firstOrFail(string $id)
    {
        $item = db()->fetchAssociative("SELECT * FROM " . (new static())->table ." WHERE id = ?", [$id]);

        if (!$item) {
            throw new ModelNotFoundException('Model not found');
        }

        return new static($item);
    }

    public function __get(string $name)
    {
        return array_key_exists($name, get_object_vars($this))
            ? $this->{$key}
            : null;
    }

    public function __set(string $name, $value): void
    {
        $this->{$name} = $value;
    }
}