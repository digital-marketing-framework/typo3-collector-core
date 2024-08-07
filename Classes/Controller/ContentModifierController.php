<?php

namespace DigitalMarketingFramework\Typo3\Collector\Core\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class ContentModifierController extends ActionController
{
    public function renderContentModifierAction(): ResponseInterface
    {
        $this->view->assign('pluginId', $this->request->getAttribute('currentContentObject')->data['uid']);

        return $this->htmlResponse();
    }
}
