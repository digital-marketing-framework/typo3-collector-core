<?php

declare(strict_types=1);

namespace DigitalMarketingFramework\Typo3\Collector\Core\Updates;

use TYPO3\CMS\Core\Attribute\UpgradeWizard;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Upgrades\AbstractListTypeToCTypeUpdate;

/**
 * Migrates existing tt_content records from the v12/v13 "list" plugin subtype mechanism
 * to the v14+ first-class CType. TYPO3 14 removed list_type subtypes (Breaking #105538);
 * pre-v14 records of CType="list" with list_type="dmfcollectorcore_contentmodifier"
 * must be rewritten to CType="dmfcollectorcore_contentmodifier".
 *
 * The class only loads on TYPO3 v14+ (its parent class lives at this namespace since
 * v14). On v12/v13 it is never autoloaded because nothing references it and the
 * #[UpgradeWizard] attribute is only scanned by v14's install tool.
 */
#[UpgradeWizard('dmfCollectorCoreContentModifierListTypeToCType')]
final class ContentModifierListTypeToCTypeUpdate extends AbstractListTypeToCTypeUpdate
{
    public function getTitle(): string
    {
        return 'Migrate Anyrel "Content Modifier" plugin from list_type to CType';
    }

    public function getDescription(): string
    {
        return 'TYPO3 14 removed the "list" content type subtype mechanism. '
            . 'This wizard migrates existing "Anyrel Content" content elements '
            . '(CType=list, list_type=dmfcollectorcore_contentmodifier) to the new '
            . 'first-class CType "dmfcollectorcore_contentmodifier".';
    }

    protected function getListTypeToCTypeMapping(): array
    {
        return [
            'dmfcollectorcore_contentmodifier' => 'dmfcollectorcore_contentmodifier',
        ];
    }

    /**
     * Belt-and-suspenders safety: the inherited updateNecessary() would return true on
     * v12/v13 too if anything ever made the wizard visible to them (e.g., a future TYPO3
     * version that backports attribute scanning, or someone registering this class via
     * Services.yaml). Running the migration on v12/v13 would actively break data because
     * those versions register the plugin as a list_type subtype, not a first-class CType.
     */
    public function updateNecessary(): bool
    {
        if ((new Typo3Version())->getMajorVersion() < 14) {
            return false;
        }

        return parent::updateNecessary();
    }
}
