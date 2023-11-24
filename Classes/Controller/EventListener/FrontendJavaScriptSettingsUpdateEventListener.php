<?php

namespace DigitalMarketingFramework\Typo3\Collector\Core\Controller\EventListener;

use DigitalMarketingFramework\Collector\Core\Model\Configuration\CollectorConfiguration;
use DigitalMarketingFramework\Collector\Core\Model\Configuration\CollectorConfigurationInterface;
use DigitalMarketingFramework\Core\ConfigurationDocument\ConfigurationDocumentManagerInterface;
use DigitalMarketingFramework\Core\Exception\DigitalMarketingFrameworkException;
use DigitalMarketingFramework\Core\GlobalConfiguration\GlobalConfigurationInterface;
use DigitalMarketingFramework\Typo3\Core\Controller\Event\FrontendJavaScriptSettingsUpdateEvent;
use DigitalMarketingFramework\Typo3\Collector\Core\Registry\Registry;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;

class FrontendJavaScriptSettingsUpdateEventListener
{
    protected GlobalConfigurationInterface $globalConfiguration;

    protected ConfigurationDocumentManagerInterface $configurationDocumentManager;

    public function __construct(
        protected Registry $registry,
        protected UriBuilder $uriBuilder
    ) {
        $this->globalConfiguration = $registry->getGlobalConfiguration();
        $this->configurationDocumentManager = $registry->getConfigurationDocumentManager();
    }

    protected function getConfiguration(): CollectorConfigurationInterface
    {
        $documentIdentifier = $this->globalConfiguration->get('core')['configurationStorage']['defaultConfigurationDocument'] ?? '';
        if ($documentIdentifier === '') {
            throw new DigitalMarketingFrameworkException('No default configuration document identifier given');
        }

        $configurationStack = $this->configurationDocumentManager->getConfigurationStackFromIdentifier($documentIdentifier);

        return new CollectorConfiguration($configurationStack);
    }

    public function __invoke(FrontendJavaScriptSettingsUpdateEvent $event): void
    {
        $rootPageId = $event->getRootPageId();
        $configuration = $this->getConfiguration();

        $contentModifiers = $this->registry->getFrontendContentModifiers($configuration);
        foreach ($contentModifiers as $contentModifier) {
            $keyword = $contentModifier->getKeyword();
            $name = $contentModifier->getContentModifierName();

            $uri = $this->uriBuilder->reset()
                ->setTargetPageType(1673463849)
                ->setTargetPageUid($rootPageId)
                ->uriFor(
                    extensionName: 'DmfCollectorCore',
                    pluginName: 'AjaxService',
                    controllerName: 'Collector',
                    actionName: 'showContentModifier',
                    controllerArguments: ['plugin' => $keyword, 'version' => $name]
                );

            $event->addJavaScriptPlugin('content-modifier', $keyword . '-' . $name, url: $uri, settings: []);
        }
    }
}
