<?php


namespace database\migrations;

use database\traits\DbConfigTrait;
use Phinx\Migration\AbstractMigration;

abstract class MigrationAbstract extends AbstractMigration
{
    use DbConfigTrait;
}
