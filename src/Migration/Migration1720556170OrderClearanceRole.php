<?php declare(strict_types=1);

namespace Myfav\Org\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;

class Migration1720556170OrderClearanceRole extends MigrationStep
{
    /**
     * getCreationTimestamp
     *
     * @return int
     */
    public function getCreationTimestamp(): int
    {
        return 1720556170;
    }

    /**
     * update
     *
     * @param  Connection $connection
     * @return void
     */
    public function update(Connection $connection): void
    {
        $connection->executeStatement(
            'CREATE TABLE IF NOT EXISTS `order_clearance_role` (
            `id` BINARY(16) NOT NULL,
            `name` VARCHAR(32),
            `created_at` DATETIME(3) NOT NULL,
            `updated_at` DATETIME(3) NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');

        $connection->executeStatement(
            'INSERT INTO `order_clearance_role` (`id`, `name`, `created_at`, `updated_at`)
            VALUES (UNHEX(\'0196192887a871f38d43089598db212b\'), \'Manager\', now(), NULL);'
        );

        $connection->executeStatement(
            'INSERT INTO `order_clearance_role` (`id`, `name`, `created_at`, `updated_at`)
            VALUES (UNHEX(\'019619298b5671ae9570af6538ca375e\'), \'User\', now(), NULL);'
        );
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}