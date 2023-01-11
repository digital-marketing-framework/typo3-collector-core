<?php

namespace DigitalMarketingFramework\Typo3\Collector\Core\Registry\Event;

use DigitalMarketingFramework\Collector\Core\Registry\RegistryInterface;

abstract class CollectorRegistryUpdateEvent
{
    public function __construct(
        protected RegistryInterface $registry,
    ) {
    }

    public function getRegistry(): RegistryInterface
    {
        return $this->registry;
    }
}
