<?php declare(strict_types=1);

namespace Myfav\Org\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;

class Migration1720556163MyfavOrgAclAttributeGroup extends MigrationStep
{
    /**
     * getCreationTimestamp
     *
     * @return int
     */
    public function getCreationTimestamp(): int
    {
        return 1720556163;
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
            'CREATE TABLE IF NOT EXISTS `myfav_org_acl_attribute_group` (
            `id` BINARY(16) NOT NULL,
            `name` VARCHAR(128),
            `sort_order` INT(11) NOT NULL,
            `created_at` DATETIME(3) NOT NULL,
            `updated_at` DATETIME(3) NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');

        $connection->executeStatement(
            'INSERT INTO `myfav_org_acl_attribute_group` (`id`, `name`, `sort_order`, `created_at`, `updated_at`) VALUES
            (UNHEX(\'0195c695680173f696059695e6c811ac\'), \'employee\',  10, \'2025-03-16 17:15:01.000\', NULL),
            (UNHEX(\'0195c6959d1671c28e894bc2ce06c76d\'), \'role\', 20, \'2025-03-16 17:15:19.000\', NULL),
            (UNHEX(\'0195c7b9f99c70f291837d892a687c43\'), \'sw.profile\', 30, \'2025-03-16 17:15:19.000\', NULL),
            (UNHEX(\'0195c7ba2d4d72d0a1a17e099998b042\'), \'sw.addresses\', 40, \'2025-03-16 17:15:19.000\', NULL),
            (UNHEX(\'0195c7badad37025a6a46a9139a735c9\'), \'sw.payment.methods\', 50, \'2025-03-16 17:15:19.000\', NULL),
            (UNHEX(\'0195c7b916ac71f5906813e9693a0bfb\'), \'sw.orders\', 60, \'2025-03-16 17:15:19.000\', NULL);');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}