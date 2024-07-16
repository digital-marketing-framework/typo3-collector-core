<?php

namespace DigitalMarketingFramework\Typo3\Collector\Core\ViewHelpers\ContentModifier\ContentSettings;

use DigitalMarketingFramework\Typo3\Collector\Core\ViewHelpers\ContentModifier\AbstractContentModifierViewHelper;

class RegisterViewHelper extends AbstractContentModifierViewHelper
{
    public function render(): string
    {
        $this->getContentModifierHandler()->setContentSpecificSettingsFromConfigurationDocument(
            $this->getConfigurationDocument(),
            $this->asList(),
            $this->arguments['id']
        );

        return '';
    }
}
