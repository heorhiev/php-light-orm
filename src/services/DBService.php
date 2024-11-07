<?php

namespace light\orm\services\db;

use app\orm\dto\config\DatabaseDto;
use light\app\services\SettingsService;
use mysqli;


class DBService
{
    private static $mysqli;


    public static function getMysqli(): mysqli
    {
        if (!self::$mysqli) {
            /** @var DatabaseDto $options */
            $options = SettingsService::load('database', DatabaseDto::class);
            self::$mysqli = new mysqli($options->host, $options->username, $options->password, $options->name);;
        }

        return self::$mysqli;
    }
}