<?php

namespace DigitalMarketingFramework\Typo3\Collector\Core\Controller;

use DigitalMarketingFramework\Collector\Core\ContentModifier\ContentModifierInterface;
use DigitalMarketingFramework\Collector\Core\ContentModifier\FrontendContentModifierInterface;
use DigitalMarketingFramework\Collector\Core\Model\Configuration\CollectorConfiguration;
use DigitalMarketingFramework\Collector\Core\Registry\RegistryInterface;
use DigitalMarketingFramework\Core\Api\EndPoint\EndPointStorageInterface;
use DigitalMarketingFramework\Core\ConfigurationDocument\ConfigurationDocumentManagerInterface;
use DigitalMarketingFramework\Core\Model\Api\EndPointInterface;
use DigitalMarketingFramework\Typo3\Core\Registry\RegistryCollection;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class ContentModifierController extends ActionController
{
    protected EndPointStorageInterface $endPointStorage;

    protected ConfigurationDocumentManagerInterface $configurationDocumentManager;

    protected RegistryInterface $registry;

    public function __construct(
        RegistryCollection $registryCollection,
    ) {
        $this->registry = $registryCollection->getRegistryByClass(RegistryInterface::class);
        $this->endPointStorage = $this->registry->getEndPointStorage();
        $this->configurationDocumentManager = $this->registry->getConfigurationDocumentManager();
    }

    /**
     * @return array<string,mixed>
     */
    protected function getContentConfiguration(string $publicKey): array
    {
        $document = $this->settings['contentConfiguration'] ?? '';

        if ($document === '') {
            return [];
        }

        $config = $this->configurationDocumentManager->getParser()->parseDocument($document);

        return $config[$publicKey] ?? [];
    }

    protected function getEndPoint(): ?EndPointInterface
    {
        $endPointName = $this->settings['endPoint'] ?? '';

        if ($endPointName === '') {
            return null;
        }

        return $this->endPointStorage->getEndPointByName($endPointName);
    }

    protected function getContentModifier(): ?ContentModifierInterface
    {
        $endPoint = $this->getEndPoint();

        if (!$endPoint instanceof EndPointInterface) {
            return null;
        }

        $type = '';
        $name = '';

        $typeAndName = (string)($this->settings['contentModifierTypeAndName'] ?? '');
        if ($typeAndName !== '') {
            $type = explode(':', $typeAndName)[0] ?? '';
            $name = explode(':', $typeAndName)[1] ?? '';
        }

        if ($type === '' || $name === '') {
            return null;
        }

        $configurationDocument = $endPoint->getConfigurationDocument();
        $configStack = $this->configurationDocumentManager->getConfigurationStackFromDocument($configurationDocument);
        $configuration = new CollectorConfiguration($configStack);

        $id = $configuration->getContentModifierIdFromName($name);
        $contentModifier = $this->registry->getContentModifier($configuration, $id);

        if (!$contentModifier instanceof ContentModifierInterface) {
            return null;
        }

        if ($contentModifier->getKeyword() !== $type) {
            return null;
        }

        if ($contentModifier instanceof FrontendContentModifierInterface) {
            $contentModifier->activateFrontendScripts();
        }

        return $contentModifier;
    }

    protected function renderContentModifier(?EndPointInterface $endPoint, ?ContentModifierInterface $contentModifier): string
    {
        if (!$endPoint instanceof EndPointInterface) {
            return $this->registry->renderErrorMessage('End point not found or disabled');
        }

        if (!$contentModifier instanceof ContentModifierInterface) {
            return $this->registry->renderErrorMessage('Content modifier not found');
        }

        $publicKey = $contentModifier->getPublicKey($endPoint);
        $contentConfiguration = $this->getContentConfiguration($publicKey);
        $result = $contentModifier->render($endPoint, $contentConfiguration);

        if ($result !== null) {
            return $result;
        }

        return $this->registry->renderErrorMessage('Content modifier could not be rendered');
    }

    public function renderContentModifierAction(): ResponseInterface
    {
        $endPoint = $this->getEndPoint();
        $contentModifier = $this->getContentModifier();
        $rendered = $this->renderContentModifier($endPoint, $contentModifier);

        return $this->htmlResponse($rendered);
    }
}
