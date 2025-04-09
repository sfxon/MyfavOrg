<?php declare(strict_types=1);

namespace Myfav\Org\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;

class Migration1720556172CustomerData extends MigrationStep
{
    /**
     * getCreationTimestamp
     *
     * @return int
     */
    public function getCreationTimestamp(): int
    {
        return 1720556172;
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
            'CREATE TABLE IF NOT EXISTS `myfav_org_customer_data` (
            `id` BINARY(16) NOT NULL,
            `customer_id` BINARY(16),
            `myfav_org_company_id` BINARY(16) DEFAULT NULL,
            `myfav_org_acl_role_id` BINARY(16) DEFAULT NULL,
            `order_clearance_group_id` BINARY(16) DEFAULT NULL,
            `order_clearance_role_id` BINARY(16) DEFAULT NULL,
            `created_at` DATETIME(3) NOT NULL,
            `updated_at` DATETIME(3) NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}