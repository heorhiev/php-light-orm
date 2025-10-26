<?php

namespace light\orm\repository\traits;

use light\orm\Entity;
use light\orm\repository\Repository;


/**
 * @method string entityClassName()
 * @method static string tableName()
 */
trait FindTrait
{
    private $_select = '*';

    private $_result;


    public function select(array $columns): Repository
    {
        $this->_select = join(', ', $columns);
        return $this;
    }


    public function findById(int $id): Repository
    {
        $stmt = $this->_db->getMysqli()->prepare(
            sprintf('SELECT %s FROM %s WHERE id = ?', $this->_select, static::tableName())
        );

        $stmt->bind_param('i', $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $this->_result = $result->num_rows ? $result->fetch_assoc() : [];

        return $this;
    }


    public function filter(array $conditions): Repository
    {
        $where = $columns = [];
        foreach ($conditions as $key => $value) {
            $columns[] = $key;
            $where[] = $key . ' = ?';
        }
        $where = join(' AND ', $where);

        // bind types
        $types = join('', $this->getBindTypes($columns));

        $stmt = $this->_db->getMysqli()->prepare(
            sprintf('SELECT %s FROM %s WHERE %s', $this->_select, static::tableName(), $where)
        );

        $stmt->bind_param($types, ...array_values($conditions));
        $stmt->execute();

        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $this->_result[] = $row;
        }

        return $this;
    }


    public function asArrayOne(): ?array
    {
        return $this->_result[0] ?? $this->_result;
    }


    public function asArrayAll(): ?array
    {
        return $this->_result;
    }


    public function asEntityOne(): ?Entity
    {
        $result = $this->_result[0] ?? $this->_result;

        if ($result) {
            $class = $this->entityClassName();
            return new $class($result);
        }

        return null;
    }


    public function asEntityAll(): ?Entity
    {
        $result = $this->_result;

        $class = $this->entityClassName();
        foreach ($result as $key => $value) {
            $result[$key] = new $class($value);
        }

        return $result;
    }
}