<?php

namespace DigitalMarketingFramework\Typo3\Collector\Core\ViewHelpers\ContentModifier;

class ContentSettingsViewHelper extends AbstractContentModifierViewHelper
{
    public function render(): array
    {
        return $this->getContentModifierHandler()->getContentSpecificFrontendSettingsFromConfigurationDocument(
            $this->getConfigurationDocument(),
            $this->asList(),
            $this->getContentId()
        );
    }
}
