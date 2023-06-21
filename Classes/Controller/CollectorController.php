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

    public function __construct(
        protected Registry $registry
    ) {
        $this->configurationDocumentManager = $registry->getConfigurationDocumentManager();
        $this->collector = $registry->getCollector();
    }

    protected function getConfiguration(): CollectorConfigurationInterface
    {
        // TODO should this really be TypoScript? maybe the extension configuration would be more appropriate
        $documentIdentifier = $this->settings['configurationDocument'];
        $configurationStack = $this->configurationDocumentManager->getConfigurationStackFromIdentifier($documentIdentifier);
        return new CollectorConfiguration($configurationStack);
    }

    public function showAction(string $map = ''): ResponseInterface
    {
        if ($map === '') {
            $map = $this->settings['defaultTransformation'];
        }
        $configuration = $this->getConfiguration();
        $transformation = $this->registry->getDataTransformation($map, $configuration, true);
        $data = [];
        if ($transformation->allowed()) {
            $data = $this->collector->collect($configuration, invalidIdentifierHandling:true);
            $data = $transformation->transform($data);
            $data = GeneralUtility::castDataToArray($data);
        }

        if (empty($data)) {
            $data = false;
        }

        $this->view->assign('value', $data);
        return $this->htmlResponse();
    }
}
