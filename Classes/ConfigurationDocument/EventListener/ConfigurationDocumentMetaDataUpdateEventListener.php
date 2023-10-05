<?php

namespace DigitalMarketingFramework\Typo3\Collector\Core\ConfigurationDocument\EventListener;

use DigitalMarketingFramework\Typo3\Collector\Core\Registry\Registry;
use DigitalMarketingFramework\Typo3\Core\ConfigurationDocument\Event\ConfigurationDocumentMetaDataUpdateEvent;

class ConfigurationDocumentMetaDataUpdateEventListener
{
    public function __construct(
        protected Registry $registry,
    ) {
    }

    public function __invoke(ConfigurationDocumentMetaDataUpdateEvent $event): void
    {
        $event->processRegistry($this->registry);
    }
}
