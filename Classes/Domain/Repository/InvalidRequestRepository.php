<?php

namespace DigitalMarketingFramework\Typo3\Collector\Core\Domain\Repository;

use DigitalMarketingFramework\Collector\Core\InvalidIdentifier\InvalidIdentifierSchema;
use DigitalMarketingFramework\Collector\Core\InvalidIdentifier\InvalidRequestStorageInterface;
use DigitalMarketingFramework\Collector\Core\Model\InvalidRequest\InvalidRequest;
use DigitalMarketingFramework\Collector\Core\Model\InvalidRequest\InvalidRequestInterface;
use DigitalMarketingFramework\Core\SchemaDocument\Schema\ContainerSchema;
use DigitalMarketingFramework\Typo3\Core\Domain\Repository\ItemStorageRepository;
use Doctrine\DBAL\ParameterType;
use TYPO3\CMS\Core\Database\ConnectionPool;

/**
 * @extends ItemStorageRepository<InvalidRequestInterface>
 */
class InvalidRequestRepository extends ItemStorageRepository implements InvalidRequestStorageInterface
{
    public function __construct(ConnectionPool $connectionPool)
    {
        parent::__construct($connectionPool, InvalidRequest::class, 'tx_dmfcollectorcore_domain_model_invalidrequest');
    }

    public function fetchExpired(int $expireTimestamp): array
    {
        $queryBuilder = $this->buildQuery();
        $queryBuilder->andWhere(
            $queryBuilder->expr()->lte('tstamp', $queryBuilder->createNamedParameter($expireTimestamp, ParameterType::INTEGER))
        );

        $rows = $queryBuilder->executeQuery()->fetchAllAssociative();

        return $this->createResults($rows);
    }

    public function fetchByIdentifier(string $identifier): ?InvalidRequestInterface
    {
        return $this->fetchOneFiltered(['identifier' => $identifier]);
    }

    public static function getSchema(): ContainerSchema
    {
        return new InvalidIdentifierSchema();
    }
}
