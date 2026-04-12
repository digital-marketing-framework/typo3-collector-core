<?php

namespace DigitalMarketingFramework\Typo3\Collector\Core;

use DigitalMarketingFramework\Collector\Core\CollectorCoreInitialization;
use DigitalMarketingFramework\Collector\Core\InvalidIdentifier\CountingInvalidIdentifierHandler;
use DigitalMarketingFramework\Collector\Core\Registry\RegistryInterface as CollectorRegistryInterface;
use DigitalMarketingFramework\Core\Registry\RegistryInterface;
use DigitalMarketingFramework\Typo3\Collector\Core\Domain\Repository\InvalidRequestRepository;
use DigitalMarketingFramework\Typo3\Core\Typo3Initialization;

class Typo3CollectorCoreInitialization extends Typo3Initialization
{
    public function __construct(
        protected InvalidRequestRepository $invalidRequestRepository,
    ) {
        parent::__construct(
            inner: new CollectorCoreInitialization('dmf_collector_core'),
            packageName: 'typo3-collector-core',
            packageAlias: 'dmf_collector_core',
        );
    }

    public function initServices(string $domain, RegistryInterface $registry): void
    {
        parent::initServices($domain, $registry);

        if ($registry instanceof CollectorRegistryInterface) {
            $invalidIdentifierHandler = $registry->createObject(
                CountingInvalidIdentifierHandler::class,
                [$this->invalidRequestRepository]
            );
            $registry->setInvalidIdentifierHandler($invalidIdentifierHandler);
        }
    }
}
