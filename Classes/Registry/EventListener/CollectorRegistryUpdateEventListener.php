<?php

namespace DigitalMarketingFramework\Typo3\Collector\Core\Registry\EventListener;

use DigitalMarketingFramework\Collector\Core\Registry\RegistryInterface;
use DigitalMarketingFramework\Distributor\Core\DistributorCoreInitialization;
use DigitalMarketingFramework\Typo3\Collector\Core\Domain\Repository\InvalidRequestRepository;
use DigitalMarketingFramework\Typo3\Collector\Core\Service\InvalidIdentifierHandler;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

class CollectorRegistryUpdateEventListener extends AbstractCollectorRegistryUpdateEventListener
{
    public function __construct(
        protected PersistenceManager $persistenceManager,
        protected InvalidRequestRepository $failedAttemptRepository,
    ) {
        parent::__construct(new DistributorCoreInitialization());
    }

    protected function initServices(RegistryInterface $registry): void
    {
        parent::initServices($registry);
        $invalidIdentifierHandler = $registry->createObject(
            InvalidIdentifierHandler::class,
            [$this->persistenceManager, $this->failedAttemptRepository]
        );
        $registry->setInvalidIdentifierHandler($invalidIdentifierHandler);
    }
}
