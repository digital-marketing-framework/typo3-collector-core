<?php

use DigitalMarketingFramework\Typo3\Collector\Core\Scheduler\SessionCleanupTask;

defined('TYPO3') || die();

(static function () {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][SessionCleanupTask::class] = [
        'extension' => 'dmf_collector_core',
        'title' => 'Digital Marketing Framework - Collector - Session Cleanup (Bot Protection)',
        'description' => 'Removes expired entries from the bot-protection tables',
    ];
})();
