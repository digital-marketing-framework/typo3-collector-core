<?php

namespace DigitalMarketingFramework\Typo3\Collector\Core\Domain\Repository;

use DigitalMarketingFramework\Typo3\Collector\Core\Domain\Model\InvalidRequest;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * @extends Repository<InvalidRequest>
 */
class InvalidRequestRepository extends Repository
{
    /**
     * @return QueryInterface
     *
     * @phpstan-return QueryInterface<InvalidRequest>
     */
    public function createQuery() // @phpstan-ignore-line TODO Who can define the signature in a way that is compatible with its parent?
    {
        $query = parent::createQuery();
        $query->getQuerySettings()->setStoragePageIds([0]);

        return $query;
    }

    /**
     * @return QueryResultInterface<InvalidRequest>
     */
    public function findExpired(int $expireTimestamp): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching($query->lessThanOrEqual('tstamp', $expireTimestamp));

        return $query->execute();
    }
}
