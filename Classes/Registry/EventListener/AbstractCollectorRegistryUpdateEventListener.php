<?php

namespace DigitalMarketingFramework\Typo3\Collector\Core\Registry\EventListener;

use DigitalMarketingFramework\Core\Initialization;
use DigitalMarketingFramework\Core\Registry\RegistryDomain;
use DigitalMarketingFramework\Core\Registry\RegistryUpdateType;
use DigitalMarketingFramework\Collector\Core\Registry\RegistryInterface;
use DigitalMarketingFramework\Typo3\Collector\Core\Registry\Event\CollectorRegistryUpdateEvent;

abstract class AbstractCollectorRegistryUpdateEventListener
{
    public function __construct(
        protected Initialization $initialization
    ) {
    }

    protected function initGlobalConfiguration(RegistryInterface $registry): void
    {
    }

    protected function initServices(RegistryInterface $registry): void
    {
    }

    protected function initPlugins(RegistryInterface $registry): void
    {
        $this->initialization->init(RegistryDomain::COLLECTOR, $registry);
    }

    public function __invoke(CollectorRegistryUpdateEvent $event): void
    {
        $registry = $event->getRegistry();
        $type = $event->getUpdateType();
        switch ($type) {
            case RegistryUpdateType::GLOBAL_CONFIGURATION:
                $this->initGlobalConfiguration($registry);
                break;
            case RegistryUpdateType::SERVICE:
                $this->initServices($registry);
                break;
            case RegistryUpdateType::PLUGIN:
                $this->initPlugins($registry);
                break;
        }
    }
}
