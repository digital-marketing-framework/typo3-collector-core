<?php

namespace DigitalMarketingFramework\Typo3\Collector\Core\Controller\EventListener;

use DigitalMarketingFramework\Typo3\Collector\Core\Registry\Registry;
use DigitalMarketingFramework\Typo3\Core\Controller\Event\FrontendJavaScriptUpdateEvent;

class FrontendJavaScriptUpdateEventListener
{
    public function __construct(protected Registry $registry)
    {
    }

    public function __invoke(FrontendJavaScriptUpdateEvent $event): void
    {
        $event->processRegistry($this->registry);
    }
}
