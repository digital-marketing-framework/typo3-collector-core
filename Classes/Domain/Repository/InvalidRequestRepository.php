<?php

namespace DigitalMarketingFramework\Typo3\Collector\Core\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class InvalidRequestRepository extends Repository
{
    public function createQuery()
    {
        $query = parent::createQuery();
        $query->getQuerySettings()->setStoragePageIds([0]);

        return $query;
    }

    public function findExpired(int $expireTimestamp): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching($query->lessThanOrEqual('tstamp', $expireTimestamp));

        return $query->execute();
    }
}
