<?php

namespace light\orm\repository;

use light\orm\Entity;
use light\orm\repository\interfaces\RepositoryInterface;
use light\orm\repository\traits\{FindTrait, SavedTrait, TypesTrait};
use light\orm\services\DBService;


abstract class Repository implements RepositoryInterface
{
    use FindTrait, SavedTrait, TypesTrait;


    protected $_entityClassName;
    protected DBService $_db;


    abstract public static function tableName(): string;


    public function __construct($entityClassName, DBService $db)
    {
        $this->_entityClassName = $entityClassName;
        $this->_db = $db;
    }


    /**
     * @return Entity
     */
    public function entityClassName(): string
    {
        return $this->_entityClassName;
    }


    public function getIdOrCreate(array $attributes): int
    {
        $result = $this->filter($attributes)->asArrayOne();

        if ($result) {
            return $result['id'];
        }

        return $this->create($attributes);
    }


    public function getDb(): DBService
    {
        return $this->_db;
    }
}