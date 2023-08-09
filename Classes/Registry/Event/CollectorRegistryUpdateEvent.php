<?php

namespace DigitalMarketingFramework\Typo3\Collector\Core\Registry\Event;

use DigitalMarketingFramework\Collector\Core\Registry\RegistryInterface;
use DigitalMarketingFramework\Core\Registry\RegistryUpdateType;

class CollectorRegistryUpdateEvent
{
    public function __construct(
        protected RegistryInterface $registry,
        protected RegistryUpdateType $type,
    ) {
    }

    public function getRegistry(): RegistryInterface
    {
        return $this->registry;
    }

    public function getUpdateType(): RegistryUpdateType
    {
        return $this->type;
    }
}
