<?php

namespace DigitalMarketingFramework\Typo3\Collector\Core\ViewHelpers\ContentModifier\ContentSettings;

use DigitalMarketingFramework\Typo3\Collector\Core\ViewHelpers\ContentModifier\AbstractContentModifierViewHelper;

class DataAttributesViewHelper extends AbstractContentModifierViewHelper
{
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('asString', 'bool', 'render attributes', false, false);
    }

    /**
     * @return array<string,string>|string
     */
    public function render(): array|string
    {
        $attributes = $this->getContentModifierHandler()->getDataAttributesFromConfigurationDocument(
            $this->getConfigurationDocument(),
            $this->asList(),
            $this->getContentId()
        );

        $asString = (bool)$this->arguments['asString'];
        if (!$asString) {
            return $attributes;
        }

        $attributeStrings = [];
        foreach ($attributes as $key => $value) {
            $attributeStrings[] = sprintf('%s="%s" ', $key, $value);
        }

        return implode(' ', $attributeStrings);
    }
}
