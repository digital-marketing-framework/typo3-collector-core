<?php

namespace DigitalMarketingFramework\Typo3\Collector\Core\Scheduler;

use DigitalMarketingFramework\Typo3\Collector\Core\Domain\Repository\InvalidRequestRepository;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

class SessionCleanupTask extends AbstractTask
{
    /**
     * @var int
     */
    protected const DEFAULT_TIMEOUT = 300;

    protected function getTimeout(): int
    {
        /** @var ExtensionConfiguration */
        $extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class);
        try {
            return $extensionConfiguration->get('digitalmarketingframework_collector')['botProtection']['timeout'] ?? static::DEFAULT_TIMEOUT;
        } catch (ExtensionConfigurationExtensionNotConfiguredException|ExtensionConfigurationPathDoesNotExistException) {
            return static::DEFAULT_TIMEOUT;
        }
    }

    public function execute(): bool
    {
        /** @var InvalidRequestRepository */
        $invalidRequestRepository = GeneralUtility::makeInstance(InvalidRequestRepository::class);

        /** @var PersistenceManagerInterface */
        $persistenceManager = GeneralUtility::makeInstance(PersistenceManagerInterface::class);

        $found = false;
        $results = $invalidRequestRepository->findExpired(time() - $this->getTimeout());
        foreach ($results as $result) {
            $found = true;
            $invalidRequestRepository->remove($result);
        }

        if ($found) {
            $persistenceManager->persistAll();
        }

        return true;
    }
}
