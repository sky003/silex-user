<?php

namespace User\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;

/**
 * Migration for `token` table.
 *
 * @package User\Migrations
 * @author  Anton Pelykh <anton.pelykh.dev@gmail.com>
 */
class Version20170827125642 extends AbstractMigration
{
    private $tableName = 'token';

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $table = $schema->createTable($this->tableName);

        $table->addColumn('id', Type::INTEGER, ['autoincrement' => true]);
        $table->addColumn('user_id', Type::INTEGER);
        $table->addColumn('access_token', Type::TEXT, ['notnull' => false]);
        $table->addColumn('refresh_token', Type::TEXT, ['notnull' => false]);
        $table->addColumn('access_token_expires_in', Type::DATETIME, ['notnull' => false]);
        $table->addColumn('refresh_token_expires_in', Type::DATETIME, ['notnull' => false]);
        $table->addColumn('status', Type::SMALLINT);
        $table->addColumn('issued_at', Type::DATETIME, ['notnull' => false]);
        $table->addColumn('created_at', Type::DATETIME);
        $table->addColumn('updated_at', Type::DATETIME, ['notnull' => false]);

        $table->setPrimaryKey(['id']);

        $table->addIndex(['user_id']);
        $table->addForeignKeyConstraint('user', ['user_id'], ['id']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable($this->tableName);
    }
}
