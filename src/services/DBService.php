<?php

namespace light\orm\services;

use light\orm\dto\DatabaseDto;
use mysqli;


class DBService
{
    private $mysqli;

    /** @var DatabaseDto */
    private $config;


    public function __construct(DatabaseDto $config)
    {
        $this->config = $config;
    }


    /**
     * @throws \Exception
     */
    public function getMysqli(): mysqli
    {
        if (!$this->mysqli) {

            if (!$this->config instanceof DatabaseDto) {
                throw new \Exception('Database config not set');
            }

            $this->mysqli = new mysqli(
                $this->config->host,
                $this->config->username,
                $this->config->password,
                $this->config->name
            );;
        }

        return $this->mysqli;
    }
}