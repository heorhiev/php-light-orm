<?php

namespace light\orm\repository;

use light\orm\components\Entity;
use light\orm\repository\interfaces\RepositoryInterface;
use light\orm\repository\traits\{FindTrait, SavedTrait, TypesTrait};


abstract class Repository implements RepositoryInterface
{
    use FindTrait, SavedTrait, TypesTrait;


    protected $_entityClassName;


    abstract public static function tableName(): string;


    public function __construct($entityClassName)
    {
        $this->_entityClassName = $entityClassName;
    }


    /**
     * @return Entity
     */
    public function entityClassName(): string
    {
        return $this->_entityClassName;
    }
}