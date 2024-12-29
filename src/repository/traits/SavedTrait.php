<?php

namespace light\orm\repository\traits;

use light\orm\services\DBService;


/**
 * @method array getColumns()
 * @method array getBindTypes(array $columns)
 * @method static string tableName()
 */
trait SavedTrait
{
    /**
     * returned auto incremented id
     */
    public function create(array $attributes): int
    {
        $columns = array_keys($attributes);
        $placeholders = join(', ', array_fill(0, count($attributes), '?'));
        $types = join('', $this->getBindTypes($columns));

        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)', static::tableName(),
            join(', ', $columns),
            $placeholders
        );

        $st = DBService::getMysqli()->prepare($sql);

        $st->bind_param($types, ...array_values($attributes));

        $executed = $st->execute();

        if (!$executed) {
            throw new \Exception(print_r($st->error, 1));
        }

        return $st->insert_id;
    }


    public function update(array $attributes, array $conditions): bool
    {
        $columns = [];

        // updated columns
        $updates = [];
        foreach ($attributes as $key => $value) {
            $columns[] = $key;
            $updates[] = $key . ' = ?';
        }
        $updates = join(', ', $updates);

        // conditions
        $where = [];
        foreach ($conditions as $key => $value) {
            $columns[] = $key;
            $where[] = $key . ' = ?';
        }
        $where = join(' AND ', $where);

        // bind types
        $types = join('', $this->getBindTypes($columns));

        // build sql
        $sql = sprintf('UPDATE %s SET %s WHERE %s', static::tableName(), $updates, $where);

        $st = DBService::getMysqli()->prepare($sql);

        $st->bind_param($types, ...array_merge(array_values($attributes), array_values($conditions)));

        return $st->execute();
    }


    public function delete(array $conditions): bool
    {
        // conditions
        $where = [];
        foreach ($conditions as $key => $value) {
            $where[] = $key . ' = ?';
        }
        $where = join(' AND ', $where);

        // bind types
        $types = join('', $this->getBindTypes(array_keys($conditions)));

        // build sql
        $sql = sprintf('DELETE FROM %s WHERE %s', static::tableName(), $where);

        $st = DBService::getMysqli()->prepare($sql);

        $st->bind_param($types, ...array_values($conditions));

        return $st->execute();
    }
}