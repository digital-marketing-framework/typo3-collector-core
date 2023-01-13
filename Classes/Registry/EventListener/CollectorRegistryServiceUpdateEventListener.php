<?php

namespace DigitalMarketingFramework\Typo3\Collector\Core\Registry\EventListener;

use DigitalMarketingFramework\Typo3\Collector\Core\Domain\Repository\InvalidRequestRepository;
use DigitalMarketingFramework\Typo3\Collector\Core\Registry\Event\CollectorRegistryServiceUpdateEvent;
use DigitalMarketingFramework\Typo3\Collector\Core\Service\InvalidIdentifierHandler;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

class CollectorRegistryServiceUpdateEventListener
{
    public function __construct(
        protected PersistenceManager $persistenceManager,
        protected InvalidRequestRepository $failedAttemptRepository,
    ) {
    }

    public function __invoke(CollectorRegistryServiceUpdateEvent $event): void
    {
        $registry = $event->getRegistry();
        $invalidIdentifierHandler = $registry->createObject(
            InvalidIdentifierHandler::class,
            [$this->persistenceManager, $this->failedAttemptRepository]
        );
        $event->getRegistry()->setInvalidIdentifierHandler($invalidIdentifierHandler);
    }
}
