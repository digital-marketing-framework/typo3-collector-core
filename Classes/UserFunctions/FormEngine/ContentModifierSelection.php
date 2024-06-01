<?php

namespace DigitalMarketingFramework\Typo3\Collector\Core\UserFunctions\FormEngine;

use DigitalMarketingFramework\Collector\Core\Model\Configuration\CollectorConfiguration;
use DigitalMarketingFramework\Collector\Core\Model\Configuration\CollectorConfigurationInterface;
use DigitalMarketingFramework\Collector\Core\Registry\RegistryInterface;
use DigitalMarketingFramework\Core\Api\EndPoint\EndPointStorageInterface;
use DigitalMarketingFramework\Core\ConfigurationDocument\ConfigurationDocumentManagerInterface;
use DigitalMarketingFramework\Core\Model\Api\EndPointInterface;
use DigitalMarketingFramework\Core\Utility\GeneralUtility as DmfGeneralUtility;
use DigitalMarketingFramework\Typo3\Core\Registry\RegistryCollection;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ContentModifierSelection
{
    protected RegistryInterface $registry;

    protected EndPointStorageInterface $endPointStorage;

    protected ConfigurationDocumentManagerInterface $configurationDocumentManager;

    public function __construct()
    {
        $registryCollection = GeneralUtility::makeInstance(RegistryCollection::class);
        $this->registry = $registryCollection->getRegistryByClass(RegistryInterface::class);
        $this->endPointStorage = $this->registry->getEndPointStorage();
        $this->configurationDocumentManager = $this->registry->getConfigurationDocumentManager();
    }

    /**
     * @param array<string,mixed> $params
     */
    protected function pluginTypeAllowed(array $params, string $type): bool
    {
        $allowedTypes = trim($params['config']['allowedPluginTypes'] ?? '');

        if ($allowedTypes === '') {
            return true;
        }

        $types = explode(',', $allowedTypes);

        return in_array($type, $types, true);
    }

    protected function getConfigurationFromEndPoint(EndPointInterface $endPoint): CollectorConfigurationInterface
    {
        $configStack = $this->configurationDocumentManager->getConfigurationStackFromDocument($endPoint->getConfigurationDocument());

        return new CollectorConfiguration($configStack);
    }

    /**
     * @param array<string,mixed> $params
     */
    public function getEndPoints(array &$params): void
    {
        $endPoints = $this->endPointStorage->getAllEndPoints();
        foreach ($endPoints as $endPoint) {
            if (!$endPoint->getEnabled() || !$endPoint->getPullEnabled()) {
                continue;
            }

            $params['items'][] = [
                DmfGeneralUtility::getLabelFromValue($endPoint->getName()),
                $endPoint->getName(),
            ];
        }
    }

    /**
     * @return array<string,array<string>>
     */
    protected function getContentModifierMetaData(string $endPointName): array
    {
        $result = [];
        $endPoint = $this->endPointStorage->getEndPointByName($endPointName);
        if ($endPoint instanceof EndPointInterface) {
            $configuration = $this->getConfigurationFromEndPoint($endPoint);
            $ids = $configuration->getContentModifierIds();
            foreach ($ids as $id) {
                // TODO Should we let the content modifier decide whether it should be available in this plugin?
                $contentModifier = $this->registry->getContentModifier($configuration, $id);
                $plugin = $contentModifier->getKeyword();
                $name = $contentModifier->getContentModifierName();
                $result[$plugin][] = $name;
            }
        }

        return $result;
    }

    /**
     * @param array<string,mixed> $params
     */
    protected function getCurrentValue(array $params, string $field): string
    {
        $value = $params['row'][$field] ?? '';

        if (is_array($value)) {
            return $value[0] ?? '';
        }

        return $value;
    }

    /**
     * @param array<string,mixed> $params
     */
    public function getPluginTypesAndNames(array &$params): void
    {
        $endPointName = $this->getCurrentValue($params, 'settings.endPoint');
        $currentValue = $this->getCurrentValue($params, 'settings.contentModifierTypeAndName');
        $pluginType = $currentValue !== '' ? explode(':', $currentValue)[0] : '';
        $pluginName = $currentValue !== '' ? explode(':', $currentValue)[1] : '';

        $currentValueFound = false;
        if ($endPointName !== '') {
            $metaData = $this->getContentModifierMetaData($endPointName);
            foreach ($metaData as $type => $plugins) {
                if (!$this->pluginTypeAllowed($params, $type)) {
                    continue;
                }

                foreach ($plugins as $name) {
                    $key = $type . ':' . $name;
                    $label = DmfGeneralUtility::getLabelFromValue($type) . ' - ' . DmfGeneralUtility::getLabelFromValue($name);
                    $params['items'][] = [$label, $key];

                    if ($key === $currentValue) {
                        $currentValueFound = true;
                    }
                }
            }
        } else {
            $params['items'][] = ['Select an End Point first', ''];
        }

        if ($currentValue !== '' && !$currentValueFound) {
            $label = DmfGeneralUtility::getLabelFromValue($pluginType) . ' - ' . DmfGeneralUtility::getLabelFromValue($pluginName);
            array_unshift($params['items'], [
                '-- ' . $label . ' (old value) --',
                $currentValue,
            ]);
        }
    }
}
