<?php

namespace DigitalMarketingFramework\Typo3\Collector\Core\Registry\EventListener;

use DigitalMarketingFramework\Core\Registry\RegistryCollectionInterface;
use DigitalMarketingFramework\Core\Registry\RegistryDomain;
use DigitalMarketingFramework\Typo3\Collector\Core\Registry\Registry;

class RegistryCollectionEventListener
{
    public function __construct(
        protected Registry $registry,
    ) {
    }

    public function __invoke(RegistryCollectionInterface $collection): void
    {
        $collection->addRegistry(RegistryDomain::COLLECTOR, $this->registry);
    }
}
