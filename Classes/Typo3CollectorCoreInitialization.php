<?php

namespace DigitalMarketingFramework\Typo3\Collector\Core;

use DigitalMarketingFramework\Collector\Core\CollectorCoreInitialization;
use DigitalMarketingFramework\Collector\Core\InvalidIdentifier\CountingInvalidIdentifierHandler;
use DigitalMarketingFramework\Collector\Core\Registry\RegistryInterface as CollectorRegistryInterface;
use DigitalMarketingFramework\Core\Registry\RegistryDomain;
use DigitalMarketingFramework\Core\Registry\RegistryInterface;
use DigitalMarketingFramework\Typo3\Collector\Core\Domain\Repository\InvalidRequestRepository;

class Typo3CollectorCoreInitialization extends CollectorCoreInitialization
{
    public function __construct(
        protected InvalidRequestRepository $invalidRequestRepository,
    ) {
        parent::__construct('dmf_collector_core');
    }

    public function initServices(string $domain, RegistryInterface $registry): void
    {
        parent::initServices($domain, $registry);

        if ($domain === RegistryDomain::COLLECTOR && $registry instanceof CollectorRegistryInterface) {
            $invalidIdentifierHandler = $registry->createObject(
                CountingInvalidIdentifierHandler::class,
                [$this->invalidRequestRepository]
            );
            $registry->setInvalidIdentifierHandler($invalidIdentifierHandler);
        }
    }
}
