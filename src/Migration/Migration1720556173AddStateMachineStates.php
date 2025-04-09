<?php declare(strict_types=1);

namespace Myfav\Org\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;

class Migration1720556173AddStateMachineStates extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1720556173;
    }

    public function update(Connection $connection): void
    {
        $stateMachineId = $connection->fetchOne(
            '
            SELECT `id`
            FROM `state_machine`
            WHERE technical_name = :technicalName
            ',
            ['technicalName' => 'order.state'],
        );

        $stateMachineId = Uuid::fromBytesToHex($stateMachineId);

        $orderOpenStateId = $connection->fetchOne(
            '
            SELECT `id`
            FROM `state_machine_state`
            WHERE
                technical_name = :technicalName AND
                state_machine_id = UNHEX(:stateMachineId)

            ',
            [
                'technicalName' => 'open',
                'stateMachineId' => $stateMachineId
            ],
        );

        $orderOpenStateId = Uuid::fromBytesToHex($orderOpenStateId);

        $orderCancelledStateId = $connection->fetchOne(
            '
            SELECT `id`
            FROM `state_machine_state`
            WHERE
                technical_name = :technicalName AND
                state_machine_id = UNHEX(:stateMachineId)

            ',
            [
                'technicalName' => 'cancelled',
                'stateMachineId' => $stateMachineId
            ],
        );
        $orderCancelledStateId = Uuid::fromBytesToHex($orderCancelledStateId);

        $orderInProgressStateId = $connection->fetchOne(
            '
            SELECT `id`
            FROM `state_machine_state`
            WHERE
                technical_name = :technicalName AND
                state_machine_id = UNHEX(:stateMachineId)

            ',
            [
                'technicalName' => 'in_progress',
                'stateMachineId' => $stateMachineId
            ],
        );
        $orderInProgressStateId = Uuid::fromBytesToHex($orderInProgressStateId);

        // Get an id for our new state.
        $newStateMachineStateId = Uuid::randomHex();
        $newDeclinedClearanceStateMachineStateId = Uuid::randomHex();

        // Get all languages.
        $defaultLanguageId = Defaults::LANGUAGE_SYSTEM;

        $languageIdEnglisch = $this->getLanguageIdByLocale($connection, 'en-GB');
        $languageIdGerman = $this->getLanguageIdByLocale($connection, 'de-DE');

        if($defaultLanguageId == $languageIdEnglisch || $defaultLanguageId == $languageIdGerman) {
            $defaultLanguageId = null;
        }

        // Add State machine states
        $sql = <<<SQL
INSERT INTO `state_machine_state` (`id`, `technical_name`, `state_machine_id`, `created_at`, `updated_at`)
VALUES 
    (UNHEX('{$newStateMachineStateId}'), 'waiting_for_clearance', UNHEX('{$stateMachineId}'), now(), NULL);
SQL;
        $connection->executeStatement($sql);

        $sql = <<<SQL
INSERT INTO `state_machine_state` (`id`, `technical_name`, `state_machine_id`, `created_at`, `updated_at`)
VALUES 
    (UNHEX('{$newDeclinedClearanceStateMachineStateId}'), 'clearance_declined', UNHEX('{$stateMachineId}'), now(), NULL);
SQL;
        $connection->executeStatement($sql);

        // Übersetzungen für State Machine State schreiben.
        if($defaultLanguageId) {
            $sql = <<<SQL
INSERT INTO `state_machine_state_translation` (`language_id`, `state_machine_state_id`, `name`, `custom_fields`, `created_at`, `updated_at`)
VALUES 
    (UNHEX('{$defaultLanguageId}'), UNHEX('{$newStateMachineStateId}'), 'Clearance required', NULL, now(), NULL);
SQL;
            $connection->executeStatement($sql);

            $sql = <<<SQL
INSERT INTO `state_machine_state_translation` (`language_id`, `state_machine_state_id`, `name`, `custom_fields`, `created_at`, `updated_at`)
VALUES 
    (UNHEX('{$defaultLanguageId}'), UNHEX('{$newDeclinedClearanceStateMachineStateId}'), 'Clearance declined', NULL, now(), NULL);
SQL;
            $connection->executeStatement($sql);
        }

        if($languageIdEnglisch) {
            $sql = <<<SQL
INSERT INTO `state_machine_state_translation` (`language_id`, `state_machine_state_id`, `name`, `custom_fields`, `created_at`, `updated_at`)
VALUES 
    (UNHEX('{$languageIdEnglisch}'), UNHEX('{$newStateMachineStateId}'), 'Clearance required', NULL, now(), NULL);
SQL;
            $connection->executeStatement($sql);

            $sql = <<<SQL
INSERT INTO `state_machine_state_translation` (`language_id`, `state_machine_state_id`, `name`, `custom_fields`, `created_at`, `updated_at`)
VALUES 
    (UNHEX('{$languageIdEnglisch}'), UNHEX('{$newDeclinedClearanceStateMachineStateId}'), 'Clearance declined', NULL, now(), NULL);
SQL;
            $connection->executeStatement($sql);
        }

        if($languageIdGerman) {
            $sql = <<<SQL
INSERT INTO `state_machine_state_translation` (`language_id`, `state_machine_state_id`, `name`, `custom_fields`, `created_at`, `updated_at`)
VALUES 
    (UNHEX('{$languageIdGerman}'), UNHEX('{$newStateMachineStateId}'), 'Freigabe erforderlich', NULL, now(), NULL);
SQL;
            $connection->executeStatement($sql);

            $sql = <<<SQL
INSERT INTO `state_machine_state_translation` (`language_id`, `state_machine_state_id`, `name`, `custom_fields`, `created_at`, `updated_at`)
VALUES 
    (UNHEX('{$languageIdGerman}'), UNHEX('{$newDeclinedClearanceStateMachineStateId}'), 'Freigabe abgelehnt', NULL, now(), NULL);
SQL;
            $connection->executeStatement($sql);
        }

        // Transitions erstellen.
        // Get ids for our new state-transitions.
        $openToClearanceId = Uuid::randomHex();
        $clearanceToOpenId = Uuid::randomHex();
        $clearanceToCancelId = Uuid::randomHex();
        $clearanceToInProcessId = Uuid::randomHex();

        $sql = <<<SQL
INSERT INTO `state_machine_transition` (`id`, `action_name`, `state_machine_id`, `from_state_id`, `to_state_id`, `custom_fields`, `created_at`, `updated_at`)
VALUES 
    (UNHEX('{$openToClearanceId}'), 'order_open_to_clearance', UNHEX('{$stateMachineId}'), UNHEX('{$orderOpenStateId}'), UNHEX('{$newStateMachineStateId}'), NULL, now(), NULL),
    (UNHEX('{$clearanceToOpenId}'), 'order_clearance_to_cancel', UNHEX('{$stateMachineId}'), UNHEX('{$newStateMachineStateId}'), UNHEX('{$orderOpenStateId}'), NULL, now(), NULL),
    (UNHEX('{$clearanceToCancelId}'), 'order_clearance_to_open', UNHEX('{$stateMachineId}'), UNHEX('{$newStateMachineStateId}'), UNHEX('{$orderCancelledStateId}'), NULL, now(), NULL),
    (UNHEX('{$clearanceToInProcessId}'), 'order_clearance_to_process', UNHEX('{$stateMachineId}'), UNHEX('{$newStateMachineStateId}'), UNHEX('{$orderInProgressStateId}'), NULL, now(), NULL);
SQL;
        $connection->executeStatement($sql);

        // Transitions erstellen für DECLINED
        // Get ids for our new state-transitions.
        $waitingForClearanceToClearanceDeclinedId = Uuid::randomHex();
        $clearanceDeclinedToOpenId = Uuid::randomHex();
        $clearanceDeclinedToCancelId = Uuid::randomHex();
        $clearanceDeclinedToWaitingForClearanceId = Uuid::randomHex();

        $sql = <<<SQL
INSERT INTO `state_machine_transition` (`id`, `action_name`, `state_machine_id`, `from_state_id`, `to_state_id`, `custom_fields`, `created_at`, `updated_at`)
VALUES 
    (UNHEX('{$waitingForClearanceToClearanceDeclinedId}'), 'waiting_for_clearance_to_clearance_declined', UNHEX('{$stateMachineId}'), UNHEX('{$newStateMachineStateId}'), UNHEX('{$newDeclinedClearanceStateMachineStateId}'), NULL, now(), NULL),
    (UNHEX('{$clearanceDeclinedToOpenId}'), 'order_clearance_to_open', UNHEX('{$stateMachineId}'), UNHEX('{$newDeclinedClearanceStateMachineStateId}'), UNHEX('{$orderOpenStateId}'), NULL, now(), NULL),
    (UNHEX('{$clearanceDeclinedToCancelId}'), 'order_clearance_to_cancel', UNHEX('{$stateMachineId}'), UNHEX('{$newDeclinedClearanceStateMachineStateId}'), UNHEX('{$orderCancelledStateId}'), NULL, now(), NULL),
    (UNHEX('{$clearanceDeclinedToWaitingForClearanceId}'), 'clearance_declined_to_waiting_for_clearance', UNHEX('{$stateMachineId}'), UNHEX('{$newDeclinedClearanceStateMachineStateId}'), UNHEX('{$newStateMachineStateId}'), NULL, now(), NULL);
SQL;
        $connection->executeStatement($sql);
    }

    /**
     * updateDestructive
     *
     * @param  mixed $connection
     * @return void
     */
    public function updateDestructive(Connection $connection): void
    {
    }

    /**
     * getLanguageIdByLocale
     *
     * @param  mixed $connection
     * @param  mixed $locale
     * @return string
     */
    private function getLanguageIdByLocale(Connection $connection, string $locale): ?string
    {
        $sql = <<<'SQL'
SELECT `language`.`id`
FROM `language`
INNER JOIN `locale` ON `locale`.`id` = `language`.`locale_id`
WHERE `locale`.`code` = :code
SQL;

        $languageId = $connection->executeQuery($sql, ['code' => $locale])->fetchOne();
        if (!$languageId && $locale !== 'en-GB') {
            return null;
        }

        if (!$languageId) {
            return null;
        }

        return Uuid::fromBytesToHex($languageId);
    }
}