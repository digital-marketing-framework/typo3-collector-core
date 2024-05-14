<?php

namespace DigitalMarketingFramework\Typo3\Collector\Core\Api\EventListener;

use DigitalMarketingFramework\Collector\Core\Api\RouteResolver\CollectorRouteResolverInterface;
use DigitalMarketingFramework\Collector\Core\Model\Configuration\CollectorConfiguration;
use DigitalMarketingFramework\Collector\Core\Model\Configuration\CollectorConfigurationInterface;
use DigitalMarketingFramework\Core\Api\RouteResolver\EntryRouteResolverInterface;
use DigitalMarketingFramework\Core\ConfigurationDocument\ConfigurationDocumentManagerInterface;
use DigitalMarketingFramework\Core\Utility\GeneralUtility;
use DigitalMarketingFramework\Typo3\Collector\Core\Registry\Registry;
use DigitalMarketingFramework\Typo3\Core\Api\Event\FrontendJavaScriptSettingsUpdateEvent;

class FrontendJavaScriptSettingsUpdateEventListener
{
    protected ConfigurationDocumentManagerInterface $configurationDocumentManager;

    protected EntryRouteResolverInterface $entryRouteResolver;

    protected CollectorRouteResolverInterface $collectorRouteResolver;

    public function __construct(
        protected Registry $registry
    ) {
        $this->configurationDocumentManager = $registry->getConfigurationDocumentManager();
        $this->entryRouteResolver = $registry->getApiEntryRouteResolver();
        $this->collectorRouteResolver = $registry->getCollectorApiRouteResolver();
    }

    protected function getConfiguration(): CollectorConfigurationInterface
    {
        $configurationStack = $this->configurationDocumentManager->getDefaultConfigurationStack();

        return new CollectorConfiguration($configurationStack);
    }

    /**
     * @return array<array{id:string,url:string,settings:array<string,mixed>}>
     */
    protected function processContentModifiers(CollectorConfigurationInterface $configuration): array
    {
        $result = [];
        $contentModifierRoute = $this->collectorRouteResolver->getContentModifierRoute();
        $contentModifiers = $this->registry->getFrontendContentModifiers($configuration);
        foreach ($contentModifiers as $contentModifier) {
            $keyword = $contentModifier->getKeyword();
            $name = $contentModifier->getContentModifierName();
            $settings = $contentModifier->getFrontendSettings();

            $route = $contentModifierRoute->getResourceRoute(
                idAffix: implode(':', [$keyword, $name]),
                variables: [
                    CollectorRouteResolverInterface::VARIABLE_PLUGIN_TYPE => GeneralUtility::slugify($keyword),
                    CollectorRouteResolverInterface::VARIABLE_PLUGIN_ID => GeneralUtility::slugify($name),
                ]
            );

            $result[] = [
                'id' => $route->getId(),
                'url' => $this->entryRouteResolver->getFullPath($route->getPath()),
                'settings' => $settings,
            ];
        }

        return $result;
    }

    /**
     * @return array<array{id:string,url:string,settings:array<string,mixed>}>
     */
    protected function processDataTransformations(CollectorConfigurationInterface $configuration): array
    {
        $result = [];
        $transformationRoute = $this->collectorRouteResolver->getUserDataRoute();
        $transformationNames = $this->registry->getPublicDataTransformationNames($configuration);
        foreach ($transformationNames as $transformationName) {
            $route = $transformationRoute->getResourceRoute(
                idAffix: $transformationName,
                variables: [
                    CollectorRouteResolverInterface::VARIABLE_TRANSFORMATION_ID => GeneralUtility::slugify($transformationName),
                ]
            );

            $result[] = [
                'id' => $route->getId(),
                'url' => $this->entryRouteResolver->getFullPath($route->getPath()),
                'settings' => [],
            ];
        }

        return $result;
    }

    /**
     * @param array<array{id:string,url:string,settings:array<string,mixed>}> $plugins
     */
    protected function addPlugins(FrontendJavaScriptSettingsUpdateEvent $event, array $plugins): void
    {
        foreach ($plugins as $plugin) {
            $event->addJavaScriptPlugin($plugin['id'], url: $plugin['url'], settings: $plugin['settings']);
        }
    }

    public function __invoke(FrontendJavaScriptSettingsUpdateEvent $event): void
    {
        $configuration = $this->getConfiguration();

        $plugins = $this->processContentModifiers($configuration);
        $this->addPlugins($event, $plugins);

        $plugins = $this->processDataTransformations($configuration);
        $this->addPlugins($event, $plugins);
    }
}
