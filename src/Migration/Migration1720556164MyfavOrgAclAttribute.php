<?php declare(strict_types=1);

namespace Myfav\Org\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;

class Migration1720556164MyfavOrgAclAttribute extends MigrationStep
{
    /**
     * getCreationTimestamp
     *
     * @return int
     */
    public function getCreationTimestamp(): int
    {
        return 1720556164;
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
            'CREATE TABLE IF NOT EXISTS `myfav_org_acl_attribute` (
            `id` BINARY(16) NOT NULL,
            `technical_name` VARCHAR(128),
            `myfav_org_acl_attribute_group_id` BINARY(16) NOT NULL,
            `created_at` DATETIME(3) NOT NULL,
            `updated_at` DATETIME(3) NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');

        $connection->executeStatement(
            'INSERT INTO `myfav_org_acl_attribute` (`id`, `technical_name`, `myfav_org_acl_attribute_group_id`, `created_at`, `updated_at`) VALUES
            (UNHEX(\'0195c69b0fe97050a5872f9615b80a7f\'), \'employee.create\',  UNHEX(\'0195C695680173F696059695E6C811AC\'), \'2025-03-16 17:15:01.000\', NULL),
            (UNHEX(\'01959FBC8A8D72879C46E7860474DB5D\'), \'employee.read\',  UNHEX(\'0195C695680173F696059695E6C811AC\'), \'2025-03-16 17:15:01.000\', NULL),
            (UNHEX(\'01959FBCD3D071FFB80EA0F53B1861D4\'), \'employee.update\', UNHEX(\'0195C695680173F696059695E6C811AC\'), \'2025-03-16 17:15:19.000\', NULL),
            (UNHEX(\'01959FBD1B1C705B95EB72C42922275B\'), \'employee.delete\', UNHEX(\'0195C695680173F696059695E6C811AC\'), \'2025-03-16 17:15:37.000\', NULL),
            
            (UNHEX(\'0195c69b539e7396ac2165da351d47b6\'), \'role.create\', UNHEX(\'0195c6959d1671c28e894bc2ce06c76d\'), \'2025-03-16 17:15:56.000\', NULL),
            (UNHEX(\'01959FBD646D7018A8471FE4A3243637\'), \'role.read\', UNHEX(\'0195c6959d1671c28e894bc2ce06c76d\'), \'2025-03-16 17:15:56.000\', NULL),
            (UNHEX(\'01959FBD76D773F5BD8DE83141880810\'), \'role.update\', UNHEX(\'0195c6959d1671c28e894bc2ce06c76d\'), \'2025-03-16 17:16:06.000\', NULL),
            (UNHEX(\'01959FBDBFFF70FFA7748871B8D014F5\'), \'role.delete\', UNHEX(\'0195c6959d1671c28e894bc2ce06c76d\'), \'2025-03-16 17:16:18.000\', NULL),

            (UNHEX(\'0195c7bcde5071a890bfe100e272f8a5\'), \'sw.profile.update\', UNHEX(\'0195c7b9f99c70f291837d892a687c43\'), \'2025-03-16 17:16:18.000\', NULL),

            (UNHEX(\'0195c7bd398e726d9ebba0029b507b4c\'), \'sw.addresses.update\', UNHEX(\'0195c7ba2d4d72d0a1a17e099998b042\'), \'2025-03-16 17:16:18.000\', NULL),

            (UNHEX(\'0195c7bdca66702d9b1b992871646d0f\'), \'sw.payment.methods.update\', UNHEX(\'0195c7badad37025a6a46a9139a735c9\'), \'2025-03-16 17:16:18.000\', NULL),

            (UNHEX(\'0195c7be1ee372959f527b318514deb6\'), \'sw.orders.update\', UNHEX(\'0195c7b916ac71f5906813e9693a0bfb\'), \'2025-03-16 17:16:18.000\', NULL),

            (UNHEX(\'019618e532be713ba2f5a04a55187f5d\'), \'orderclearancegroup.create\', UNHEX(\'019618decc9572eb9465bd289d5461aa\'), \'2025-03-16 17:15:56.000\', NULL),
            (UNHEX(\'019618e54cbf7128b964d553808b40d0\'), \'orderclearancegroup.read\', UNHEX(\'019618decc9572eb9465bd289d5461aa\'), \'2025-03-16 17:15:56.000\', NULL),
            (UNHEX(\'019618e55e5c736297d7a3b45097d1b4\'), \'orderclearancegroup.update\', UNHEX(\'019618decc9572eb9465bd289d5461aa\'), \'2025-03-16 17:16:06.000\', NULL),
            (UNHEX(\'019618e56ce17295b39320c603414047\'), \'orderclearancegroup.delete\', UNHEX(\'019618decc9572eb9465bd289d5461aa\'), \'2025-03-16 17:16:18.000\', NULL),
            ;');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}