<?php declare(strict_types=1);

namespace Myfav\Org\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;

class Migration1720556167MyfavOrgEmployee extends MigrationStep
{
    /**
     * getCreationTimestamp
     *
     * @return int
     */
    public function getCreationTimestamp(): int
    {
        return 1720556167;
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
            'CREATE TABLE IF NOT EXISTS `myfav_org_employee` (
            `id` BINARY(16) NOT NULL,
            `customer_id` BINARY(16),
            `myfav_org_acl_role_id` BINARY(16),
            `default_payment_method_id` BINARY(16),
            `language_id` BINARY(16),
            `default_billing_address_id` BINARY(16),
            `default_shipping_address_id` BINARY(16),
            `salutation_id` BINARY(16),

            `first_name` VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
            `lastname` VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
            `position` VARCHAR(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `password` VARCHAR(1024) COLLATE utf8mb4_unicode_ci NULL,
            `email` VARCHAR(254) COLLATE utf8mb4_unicode_ci NOT NULL,
            `title` VARCHAR(100) COLLATE utf8mb4_unicode_ci NULL,
            `active` TINYINT(1) NOT NULL DEFAULT 1,
            `first_login` DATE NULL,
            `last_login` DATETIME(3) NULL,
            `custom_fields` JSON NULL,

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