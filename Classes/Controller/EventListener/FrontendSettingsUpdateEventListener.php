<?php

namespace DigitalMarketingFramework\Typo3\Collector\Core\Controller\EventListener;

use DigitalMarketingFramework\Collector\Core\Registry\RegistryInterface;
use DigitalMarketingFramework\Typo3\Core\Controller\Event\FrontendSettingsUpdateEvent;
use TYPO3\CMS\Core\Domain\Repository\PageRepository;

class FrontendSettingsUpdateEventListener
{
    public function __construct(
        protected PageRepository $pageRepository,
    ) {
    }

    public function __invoke(FrontendSettingsUpdateEvent $event): void
    {
        $registry = $event->getRegistryCollection()->getRegistryByClass(RegistryInterface::class);
        $pageId = $GLOBALS['TSFE']->id;
        $page = $this->pageRepository->getPage($pageId);
        $configurationDocument = $page['tx_dmf_collector_core_content_modifier'] ?? '';
        $registry->getContentModifierHandler()->setPageSpecificSettingsFromConfigurationDocument($configurationDocument, true);
    }
}
