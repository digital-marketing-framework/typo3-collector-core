<?php

namespace DigitalMarketingFramework\Typo3\Collector\Core\Controller;

use DigitalMarketingFramework\Collector\Core\Model\Configuration\CollectorConfiguration;
use DigitalMarketingFramework\Collector\Core\Model\Configuration\CollectorConfigurationInterface;
use DigitalMarketingFramework\Collector\Core\Service\CollectorInterface;
use DigitalMarketingFramework\Core\ConfigurationDocument\ConfigurationDocumentManagerInterface;
use DigitalMarketingFramework\Core\Utility\GeneralUtility;
use DigitalMarketingFramework\Typo3\Collector\Core\Registry\Registry;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\View\JsonView;

class CollectorController extends ActionController
{
    /** @var JsonView */
    protected $view;

    /** @var string */
    protected $defaultViewObjectName = JsonView::class;

    protected ConfigurationDocumentManagerInterface $configurationDocumentManager;

    protected CollectorInterface $collector;

    public function __construct(Registry $registry)
    {
        $this->configurationDocumentManager = $registry->getConfigurationDocumentManager();
        $this->collector = $registry->getCollector();
    }

    protected function getConfiguration(): ?CollectorConfigurationInterface
    {
        $documentIdentifier = $this->settings['configurationDocument'] ?? '';
        if ($documentIdentifier) {
            $configurationStack = $this->configurationDocumentManager->getConfigurationStackFromIdentifier($this->settings['configurationDocument']);
            return new CollectorConfiguration($configurationStack);
        }
        return null;
    }

    protected function getAllowedDataMaps(): array
    {
        $allowedDataMaps = $this->settings['allowedDataMaps'] ?? '';
        return $allowedDataMaps ? explode(',', $allowedDataMaps) : [];
    }

    protected function getMap(CollectorConfigurationInterface $configuration, string $map = ''): string
    {
        if ($map !== '') {
            return $map;
        }
        return $configuration->getCollectorConfiguration()[CollectorInterface::KEY_DEFAULT_MAP] ?? '';
    }

    public function showAction(string $map = ''): ResponseInterface
    {
        $data = [];
        $configuration = $this->getConfiguration();
        if ($configuration !== null) {
            $map = $this->getMap($configuration, $map);
            $allowedDataMaps = $this->getAllowedDataMaps();
            if ($map !== '' && in_array($map, $allowedDataMaps)) {
                $data = GeneralUtility::castDataToArray($this->collector->collect($configuration, $map));
            }
        }

        if (empty($data)) {
            $data = false;
        }

        $this->view->assign('value', $data);
        return $this->htmlResponse();
    }
}
