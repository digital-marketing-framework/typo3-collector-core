<?php

namespace DigitalMarketingFramework\Typo3\Collector\Core\Registry;

use DigitalMarketingFramework\Collector\Core\Registry\Registry as CoreCollectorRegistry;
use DigitalMarketingFramework\Typo3\Core\Registry\Event\CoreRegistryGlobalConfigurationUpdateEvent;
use DigitalMarketingFramework\Typo3\Core\Registry\Event\CoreRegistryPluginUpdateEvent;
use DigitalMarketingFramework\Typo3\Core\Registry\Event\CoreRegistryServiceUpdateEvent;
use DigitalMarketingFramework\Typo3\Collector\Core\Registry\Event\CollectorRegistryPluginUpdateEvent;
use DigitalMarketingFramework\Typo3\Collector\Core\Registry\Event\CollectorRegistryServiceUpdateEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Core\SingletonInterface;

class Registry extends CoreCollectorRegistry implements SingletonInterface
{
    public function __construct(
        protected EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function initializeObject(): void
    {
        $this->eventDispatcher->dispatch(
            new CoreRegistryGlobalConfigurationUpdateEvent($this)
        );
        $this->eventDispatcher->dispatch(
            new CoreRegistryServiceUpdateEvent($this)
        );
        $this->eventDispatcher->dispatch(
            new CoreRegistryPluginUpdateEvent($this)
        );
        $this->eventDispatcher->dispatch(
            new CollectorRegistryServiceUpdateEvent($this)
        );
        $this->eventDispatcher->dispatch(
            new CollectorRegistryPluginUpdateEvent($this)
        );
    }
}
