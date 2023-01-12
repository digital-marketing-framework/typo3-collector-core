<?php

namespace DigitalMarketingFramework\Typo3\Collector\Core\Routing\Aspect;

use TYPO3\CMS\Core\Routing\Aspect\PersistedMappableAspectInterface;
use TYPO3\CMS\Core\Routing\Aspect\StaticMappableAspectInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

class TyposcriptValueMapper implements PersistedMappableAspectInterface, StaticMappableAspectInterface
{
    protected bool $identityMapping = false;
    protected string $typoScriptPath;

    public function __construct(
        protected array $settings,
    ) {
        $this->identityMapping = (bool) $settings['identityMapping'];
        $this->typoScriptPath = (string) $settings['typoScriptPath'];
    }

    protected function getValueMap(): array
    {
        if ($this->typoScriptPath === '') {
            return [];
        }

        $configurationManager = GeneralUtility::makeInstance(ConfigurationManager::class);
        $typoScriptConfiguration = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);

        $pathParts = explode('.', $this->typoScriptPath);
        $variableName = array_pop($pathParts);
        $typoScriptReference = &$typoScriptConfiguration;
        while (!empty($pathParts)) {
            $pathPart = array_shift($pathParts);
            if (!isset($typoScriptReference[$pathPart . '.'])) {
                return [];
            }
            $typoScriptReference = &$typoScriptReference[$pathPart . '.'];
        }

        if (isset($typoScriptReference[$variableName])) {
            $typoScriptMap = $typoScriptReference[$variableName];
        } elseif (isset($typoScriptRefrerence[$variableName . '.'])) {
            $typoScriptMap = $typoScriptReference[$variableName . '.'];
        } else {
            return [];
        }

        if (!is_array($typoScriptMap)) {
            $this->identityMapping = true;
            $typoScriptMap = $typoScriptMap !== '' ? explode(',', $typoScriptMap) : [];
        }

        if ($this->identityMapping) {
            $map = [];
            foreach ($typoScriptMap as $value) {
                $map[$value] = $value;
            }
        } else {
            $map = $typoScriptMap;
        }

        return $map;
    }

    public function generate(string $value): ?string
    {
        return $this->getValueMap()[$value] ?? null;
    }

    public function resolve(string $value): ?string
    {
        print('<pre>');
        print_r([
            'map' => $this->getValueMap(),
            'flipped' => array_flip($this->getValueMap()),
            'resolved' => array_flip($this->getValueMap())[$value] ?? null,
        ]);
        exit;
        return array_flip($this->getValueMap())[$value] ?? null;
    }
}
