<?php

namespace light\orm\migrations;


interface MigrationInterface
{
    public function up(): bool;
    public function down(): bool;
}