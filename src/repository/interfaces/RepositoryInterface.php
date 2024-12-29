<?php

namespace light\orm\repository\interfaces;

use light\orm\Entity;
use light\orm\repository\Repository;



interface RepositoryInterface
{
    public function select(array $columns): Repository;
    public function findById(int $id): Repository;

    public function asArrayOne(): ?array;

    public function asEntityOne(): ?Entity;

    public function entityClassName(): string;

    public function getBindTypes(array $columns): array;

    public function create(array $attributes): int;

    public function update(array $attributes, array $conditions): int;

    public function delete(array $conditions): bool;

    public static function tableName(): string;
}