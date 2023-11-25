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
        $configurationStack = $this->configurationDocumentManager->getDefaultConfigurationStack();

        return new CollectorConfiguration($configurationStack);
    }

    public function showUserDataAction(string $map = ''): ResponseInterface
    {
        $configuration = $this->getConfiguration();

        if ($map === '') {
            $map = $configuration->getDefaultDataTransformationName();
        }

        $data = [];
        if ($map !== '' && $configuration->dataTransformationExists($map)) {
            $transformation = $this->registry->getDataTransformation($map, $configuration, true);
            if ($transformation->allowed()) {
                $data = $this->collector->collect($configuration, invalidIdentifierHandling: true);
                $data = $transformation->transform($data);
                $data = GeneralUtility::castDataToArray($data);
            }
        }

        if ($data === []) {
            $data = false;
        }

        $this->view->assign('value', $data);

        return $this->htmlResponse();
    }

    public function showContentModifierAction(string $plugin, string $name): ResponseInterface
    {
        $configuration = $this->getConfiguration();
        $contentModifierId = $configuration->getContentModifierIdFromName($name);
        $contentModifier = $this->registry->getContentModifier($configuration, $contentModifierId);

        $data = $contentModifier->getFrontendData();
        $this->view->assign('value', $data);

        return $this->htmlResponse();
    }
}
