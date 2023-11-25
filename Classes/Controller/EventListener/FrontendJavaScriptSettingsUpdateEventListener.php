<?php

namespace DigitalMarketingFramework\Typo3\Collector\Core\Controller\EventListener;

use DigitalMarketingFramework\Collector\Core\Model\Configuration\CollectorConfiguration;
use DigitalMarketingFramework\Collector\Core\Model\Configuration\CollectorConfigurationInterface;
use DigitalMarketingFramework\Core\ConfigurationDocument\ConfigurationDocumentManagerInterface;
use DigitalMarketingFramework\Core\Exception\DigitalMarketingFrameworkException;
use DigitalMarketingFramework\Typo3\Core\Controller\Event\FrontendJavaScriptSettingsUpdateEvent;
use DigitalMarketingFramework\Typo3\Collector\Core\Registry\Registry;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;

class FrontendJavaScriptSettingsUpdateEventListener
{
    protected ConfigurationDocumentManagerInterface $configurationDocumentManager;

    public function __construct(
        protected Registry $registry,
        protected UriBuilder $uriBuilder
    ) {
        $this->configurationDocumentManager = $registry->getConfigurationDocumentManager();
    }

    protected function getConfiguration(): CollectorConfigurationInterface
    {
        $configurationStack = $this->configurationDocumentManager->getDefaultConfigurationStack();

        return new CollectorConfiguration($configurationStack);
    }

    protected function processContentModifiers(CollectorConfigurationInterface $configuration): array
    {
        $result = [];
        $contentModifiers = $this->registry->getFrontendContentModifiers($configuration);
        foreach ($contentModifiers as $contentModifier) {
            $keyword = $contentModifier->getKeyword();
            $name = $contentModifier->getContentModifierName();
            $settings = $contentModifier->getFrontendSettings();

            $result[] = [
                'type' => 'contentModifier',
                'plugin' => $keyword . '-' . $name,
                'settings' => $settings,
                'action' => 'showContentModifier',
                'arguments' => ['plugin' => $keyword, 'name' => $name],
            ];
        }
        return $result;
    }

    /**
     * @return array<array{type:string,plugin:string,settings:array<string,mixed>,action:string,arguments:array<string,mixed>}
     */
    protected function processDataTransformations(CollectorConfigurationInterface $configuration): array
    {
        $result = [];
        $transformationNames = $this->registry->getPublicDataTransformationNames($configuration);
        foreach ($transformationNames as $transformationName) {
            $result[] = [
                'type' => 'userData',
                'plugin' => $transformationName,
                'settings' => [],
                'action' => 'showUserData',
                'arguments' => ['map' => $transformationName],
            ];
        }
        return $result;
    }

    /**
     * @param array<array{type:string,plugin:string,settings:array<string,mixed>,action:string,arguments:array<string,mixed>} $plugins
     */
    protected function addPlugins(FrontendJavaScriptSettingsUpdateEvent $event, int $rootPageId, array $plugins): void
    {
        foreach ($plugins as $plugin) {
            $uri = $this->uriBuilder->reset()
                ->setTargetPageType(1673463849)
                ->setTargetPageUid($rootPageId)
                ->uriFor(
                    extensionName: 'DmfCollectorCore',
                    pluginName: 'AjaxService',
                    controllerName: 'Collector',
                    actionName: $plugin['action'],
                    controllerArguments: $plugin['arguments']
                );
            $event->addJavaScriptPlugin($plugin['type'], $plugin['plugin'], url: $uri, settings: $plugin['settings']);
        }
    }

    public function __invoke(FrontendJavaScriptSettingsUpdateEvent $event): void
    {
        $rootPageId = $event->getRootPageId();
        $configuration = $this->getConfiguration();

        $plugins = $this->processContentModifiers($configuration);
        $this->addPlugins($event, $rootPageId, $plugins);

        $plugins = $this->processDataTransformations($configuration);
        $this->addPlugins($event, $rootPageId, $plugins);
    }
}
