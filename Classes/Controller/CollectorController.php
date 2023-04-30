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

    protected function getConfiguration(string $document): ?CollectorConfigurationInterface
    {
        $documentIdentifier = $this->settings['configurationDocuments'][$document] ?? null;
        if ($documentIdentifier !== null) {
            $configurationStack = $this->configurationDocumentManager->getConfigurationStackFromIdentifier($documentIdentifier);
            return new CollectorConfiguration($configurationStack);
        }
        return null;
    }

    public function showAction(string $document = 'default'): ResponseInterface
    {
        $data = [];
        $configuration = $this->getConfiguration($document);
        if ($configuration !== null) {
            $data = GeneralUtility::castDataToArray($this->collector->collect($configuration));
        }

        if (empty($data)) {
            $data = false;
        }

        $this->view->assign('value', $data);
        return $this->htmlResponse();
    }
}
