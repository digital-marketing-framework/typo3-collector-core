<?php

namespace DigitalMarketingFramework\Typo3\Collector\Core\ViewHelpers\ContentModifier;

class RenderViewHelper extends AbstractContentModifierViewHelper
{
    /**
     * @var bool
     */
    protected $escapeOutput = false;

    public function render(): string
    {
        return $this->getContentModifierHandler()->renderFromConfigurationDocument(
            $this->getConfigurationDocument(),
            $this->asList()
        );
    }
}
