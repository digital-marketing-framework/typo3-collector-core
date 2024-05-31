<?php

namespace DigitalMarketingFramework\Typo3\Collector\Core\Controller;

use DigitalMarketingFramework\Collector\Core\ContentModifier\ContentModifierInterface;
use DigitalMarketingFramework\Collector\Core\Model\Configuration\CollectorConfiguration;
use DigitalMarketingFramework\Collector\Core\Registry\RegistryInterface;
use DigitalMarketingFramework\Core\Api\EndPoint\EndPointStorageInterface;
use DigitalMarketingFramework\Core\ConfigurationDocument\ConfigurationDocumentManagerInterface;
use DigitalMarketingFramework\Core\ConfigurationDocument\Controller\FullDocumentConfigurationEditorController;
use DigitalMarketingFramework\Core\Model\Api\EndPointInterface;
use DigitalMarketingFramework\Core\SchemaDocument\SchemaDocument;
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

    protected function getContentModifier(?EndPointInterface $endPoint, string $typeAndName): ?ContentModifierInterface
    {
        if (!$endPoint instanceof EndPointInterface) {
            return null;
        }

        $type = explode(':', $typeAndName)[0] ?? '';
        $name = explode(':', $typeAndName)[1] ?? '';

        if ($type === '' || $name === '') {
            return null;
        }

        $configStack = $this->configurationDocumentManager->getConfigurationStackFromDocument($endPoint->getConfigurationDocument());
        $configuration = new CollectorConfiguration($configStack);

        $id = $configuration->getContentModifierIdFromName($name);
        $contentModifier = $this->registry->getContentModifier($configuration, $id);

        if (!$contentModifier instanceof ContentModifierInterface) {
            return null;
        }

        if ($contentModifier->getKeyword() !== $type) {
            return null;
        }

        return $contentModifier;
    }

    protected function buildSchemaDocument(?EndPointInterface $endPoint, ?ContentModifierInterface $contentModifier): SchemaDocument
    {
        $schemaDocument = new SchemaDocument();

        if ($endPoint instanceof EndPointInterface && $contentModifier instanceof ContentModifierInterface) {
            $key = $contentModifier->getPublicKey($endPoint);
            $schema = $schemaDocument->getMainSchema();
            $schema->getRenderingDefinition()->setLabel('Content Configuration');

            $modifierSchema = $contentModifier->getBackendSettingsSchema($schemaDocument);
            $modifierSchema->getRenderingDefinition()->setNavigationItem(false);
            $modifierSchema->getRenderingDefinition()->setSkipHeader(true);
            $schema->addProperty($key, $modifierSchema);
        }

        return $schemaDocument;
    }

    protected function prepareAction(ServerRequestInterface $request): void
    {
        parent::prepareAction($request);

        $parameters = $request->getQueryParams();
        $endPointName = $parameters['endPoint'] ?? '';
        $contentModifierTypeAndName = $parameters['contentModifierTypeAndName'] ?? '';

        $endPoint = $this->endPointStorage->getEndPointByName($endPointName);
        $contentModifier = $this->getContentModifier($endPoint, $contentModifierTypeAndName);

        $schemaDocument = $this->buildSchemaDocument($endPoint, $contentModifier);
        $this->editorController->setSchemaDocument($schemaDocument);
    }
}
