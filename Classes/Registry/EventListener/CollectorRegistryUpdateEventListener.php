<?php

namespace DigitalMarketingFramework\Typo3\Collector\Core\Registry\EventListener;

use DigitalMarketingFramework\Collector\Core\CollectorCoreInitialization;
use DigitalMarketingFramework\Collector\Core\InvalidIdentifier\CountingInvalidIdentifierHandler;
use DigitalMarketingFramework\Collector\Core\Registry\RegistryInterface;
use DigitalMarketingFramework\Typo3\Collector\Core\Domain\Repository\InvalidRequestRepository;

class CollectorRegistryUpdateEventListener extends AbstractCollectorRegistryUpdateEventListener
{
    public function __construct(
        protected InvalidRequestRepository $invalidRequestRepository,
    ) {
        parent::__construct(new CollectorCoreInitialization('dmf_collector_core'));
    }

    protected function initServices(RegistryInterface $registry): void
    {
        parent::initServices($registry);

        $invalidIdentifierHandler = $registry->createObject(
            CountingInvalidIdentifierHandler::class,
            [$this->invalidRequestRepository]
        );
        $registry->setInvalidIdentifierHandler($invalidIdentifierHandler);
    }
}
