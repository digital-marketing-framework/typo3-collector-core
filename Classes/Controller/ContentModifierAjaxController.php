<?php

namespace DigitalMarketingFramework\Typo3\Collector\Core\Controller;

use DigitalMarketingFramework\Collector\Core\ContentModifier\ContentModifierInterface;
use DigitalMarketingFramework\Collector\Core\ContentModifier\FrontendElementContentModifierInterface;
use DigitalMarketingFramework\Collector\Core\ContentModifier\FrontendFormContentModifierInterface;
use DigitalMarketingFramework\Collector\Core\ContentModifier\FrontendPageContentModifierInterface;
use DigitalMarketingFramework\Collector\Core\ContentModifier\FrontendPluginContentModifierInterface;
use DigitalMarketingFramework\Collector\Core\Registry\RegistryInterface;
use DigitalMarketingFramework\Core\Api\EndPoint\EndPointStorageInterface;
use DigitalMarketingFramework\Core\ConfigurationDocument\ConfigurationDocumentManagerInterface;
use DigitalMarketingFramework\Core\ConfigurationDocument\Controller\FullDocumentConfigurationEditorController;
use DigitalMarketingFramework\Core\Exception\DigitalMarketingFrameworkException;
use DigitalMarketingFramework\Typo3\Core\Controller\AbstractAjaxController;
use DigitalMarketingFramework\Typo3\Core\Registry\RegistryCollection;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;

class ContentModifierAjaxController extends AbstractAjaxController
{
    protected EndPointStorageInterface $endPointStorage;

    protected ConfigurationDocumentManagerInterface $configurationDocumentManager;

    protected RegistryInterface $registry;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        RegistryCollection $registryCollection,
    ) {
        $this->registry = $registryCollection->getRegistryByClass(RegistryInterface::class);
        $this->endPointStorage = $this->registry->getEndPointStorage();
        $this->configurationDocumentManager = $this->registry->getConfigurationDocumentManager();
        $editorController = $this->registry->createObject(FullDocumentConfigurationEditorController::class);
        parent::__construct($responseFactory, $editorController);
    }

    /**
     * @return array{contentModifierList:bool,contentModifierInterface:class-string<ContentModifierInterface>}
     */
    protected function getSchemaDocumentConfiguration(ServerRequestInterface $request): array
    {
        $parameters = $request->getQueryParams();

        return [
            'contentModifierList' => (bool)($parameters['contentModifierList'] ?? '0'),
            'contentModifierInterface' => match ($parameters['contentModifierGroup'] ?? '') {
                'plugin' => FrontendPluginContentModifierInterface::class,
                'page' => FrontendPageContentModifierInterface::class,
                'element' => FrontendElementContentModifierInterface::class,
                'form' => FrontendFormContentModifierInterface::class,
                default => throw new DigitalMarketingFrameworkException('Invalid content modifier group'),
            },
        ];
    }

    protected function prepareAction(ServerRequestInterface $request): void
    {
        parent::prepareAction($request);

        $schemaDocumentConfig = $this->getSchemaDocumentConfiguration($request);

        $schemaDocument = $this->registry->getContentModifierHandler()->getContentModifierBackendSettingsSchemaDocument(
            asList: $schemaDocumentConfig['contentModifierList'],
            contentModifierInterface: $schemaDocumentConfig['contentModifierInterface']
        );

        $this->editorController->setSchemaDocument($schemaDocument);
    }
}
