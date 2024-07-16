<?php

namespace DigitalMarketingFramework\Typo3\Collector\Core\ViewHelpers\ContentModifier;

use DigitalMarketingFramework\Collector\Core\ContentModifier\ContentModifierHandlerInterface;
use DigitalMarketingFramework\Collector\Core\Registry\RegistryInterface;
use DigitalMarketingFramework\Typo3\Core\Registry\RegistryCollection;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

abstract class AbstractContentModifierViewHelper extends AbstractViewHelper
{
    protected RegistryInterface $registry;

    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('configuration', 'string', 'Configuration document', true);
        $this->registerArgument('asList', 'bool', 'A list of content modifiers', true);
        $this->registerArgument('id', 'string', 'Content ID', true);
    }

    protected function getRegistry(): RegistryInterface
    {
        if (!isset($this->registry)) {
            $registryCollection = GeneralUtility::makeInstance(RegistryCollection::class);
            $this->registry = $registryCollection->getRegistryByClass(RegistryInterface::class);
        }

        return $this->registry;
    }

    protected function getContentModifierHandler(): ContentModifierHandlerInterface
    {
        return $this->getRegistry()->getContentModifierHandler();
    }

    protected function getConfigurationDocument(): string
    {
        return $this->arguments['configuration'];
    }

    protected function asList(): bool
    {
        return $this->arguments['asList'];
    }

    protected function getContentId(): string
    {
        return $this->arguments['id'];
    }
}
