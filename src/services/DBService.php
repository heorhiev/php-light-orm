<?php

namespace light\orm\services;

use light\orm\dto\DatabaseDto;
use mysqli;


class DBService
{
    private static $mysqli;

    /** @var DatabaseDto */
    private static $config;


    public static function init(DatabaseDto $config): void
    {
        self::$config = $config;
    }


    /**
     * @throws \Exception
     */
    public static function getMysqli(): mysqli
    {
        if (!self::$mysqli) {

            if (!self::$config instanceof DatabaseDto) {
                throw new \Exception('Database config not set');
            }

            self::$mysqli = new mysqli(
                self::$config->host,
                self::$config->username,
                self::$config->password,
                self::$config->name
            );;
        }

        return self::$mysqli;
    }
}