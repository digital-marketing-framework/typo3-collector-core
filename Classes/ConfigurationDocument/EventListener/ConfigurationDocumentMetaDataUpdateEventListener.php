<?php

namespace DigitalMarketingFramework\Typo3\Collector\Core\ConfigurationDocument\EventListener;

use DigitalMarketingFramework\Typo3\Core\ConfigurationDocument\Event\ConfigurationDocumentMetaDataUpdateEvent;
use DigitalMarketingFramework\Typo3\Collector\Core\Registry\Registry;

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
