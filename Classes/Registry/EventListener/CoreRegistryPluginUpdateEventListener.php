<?php

namespace DigitalMarketingFramework\Typo3\Collector\Core\Registry\EventListener;

use DigitalMarketingFramework\Collector\Core\CorePluginInitialization;
use DigitalMarketingFramework\Typo3\Core\Registry\Event\CoreRegistryPluginUpdateEvent;

class CoreRegistryPluginUpdateEventListener
{
    public function __invoke(CoreRegistryPluginUpdateEvent $event): void
    {
        CorePluginInitialization::initialize($event->getRegistry());
    }
}
