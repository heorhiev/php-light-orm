<?php

namespace light\orm;

use light\orm\repository\Repository;


abstract class Entity
{
    public $id;


    abstract public static function fields(): array;

    abstract public static function repository(): Repository;

    
    public function __construct($attributes = [])
    {
        $this->init($attributes);
    }


    public function update(array $attributes): bool
    {
        $updated = static::repository()->update($attributes, ['id' => $this->id]);

        if ($updated) {
            $this->init($attributes);
        }

        return $updated;
    }


    public function getAttributes(array $keys): array
    {
        $result = [];

        foreach ($keys as $key) {
            $result[] = $this->{$key};
        }

        return $result;
    }


    /**
     * Load
     */
    protected function init(array $attributes): void
    {
        foreach ($attributes as $property => $value) {
            if (property_exists($this, $property)) {
                $this->{$property} = $value;
            }
        }
    }
}