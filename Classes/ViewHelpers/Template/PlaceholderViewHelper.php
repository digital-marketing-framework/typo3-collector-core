<?php

namespace DigitalMarketingFramework\Typo3\Collector\Core\ViewHelpers\Template;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class PlaceholderViewHelper extends AbstractViewHelper
{
    /**
     * @var bool
     */
    protected $escapeOutput = false;

    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('name', 'string', 'Placeholder name', true);
    }

    public function render(): string
    {
        return '{{' . $this->arguments['name'] . '}}';
    }
}
