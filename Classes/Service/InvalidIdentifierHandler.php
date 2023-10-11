<?php

namespace DigitalMarketingFramework\Typo3\Collector\Core\Service;

use DateTime;
use DigitalMarketingFramework\Collector\Core\Service\InvalidIdentifierHandler as CoreInvalidIdentifierHandler;
use DigitalMarketingFramework\Core\Context\ContextInterface;
use DigitalMarketingFramework\Core\GlobalConfiguration\GlobalConfigurationAwareInterface;
use DigitalMarketingFramework\Core\GlobalConfiguration\GlobalConfigurationAwareTrait;
use DigitalMarketingFramework\Typo3\Collector\Core\Domain\Model\InvalidRequest;
use DigitalMarketingFramework\Typo3\Collector\Core\Domain\Repository\InvalidRequestRepository;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

class InvalidIdentifierHandler extends CoreInvalidIdentifierHandler implements GlobalConfigurationAwareInterface
{
    use GlobalConfigurationAwareTrait;

    /**
     * @var array<string,mixed>
     */
    protected array $settings = [];

    /**
     * @var bool
     */
    protected const DEFAULT_ENABLED = false;

    /**
     * @var int
     */
    protected const DEFAULT_TIMEOUT = 300;

    /**
     * @var int
     */
    protected const DEFAULT_PENALTY_PER_ATTEMPT = 5;

    /**
     * @var int
     */
    protected const DEFAULT_MAX_PENALTY = 30;

    protected string $identifier;

    protected ?InvalidRequest $invalidRequest = null;

    public function __construct(
        protected PersistenceManager $persistenceManager,
        protected InvalidRequestRepository $invalidRequestRepository,
    ) {
    }

    protected function enabled(): bool
    {
        return $this->settings['enabled'] ?? static::DEFAULT_ENABLED;
    }

    protected function getPenalty(int $invalidRequestCount): int
    {
        $penalty = $invalidRequestCount * ($this->settings['penaltyPerAttempt'] ?? static::DEFAULT_PENALTY_PER_ATTEMPT);
        if ($penalty > ($this->settings['maxPenalty'] ?? static::DEFAULT_MAX_PENALTY)) {
            $penalty = $this->settings['maxPenalty'] ?? static::DEFAULT_MAX_PENALTY;
        }

        return $penalty;
    }

    protected function getInvalidRequestCount(): int
    {
        $this->initRecord();

        if (!$this->invalidRequest instanceof InvalidRequest) {
            return 0;
        }

        if ($this->invalidRequest->isExpired($this->settings['timeout'] ?? static::DEFAULT_TIMEOUT)) {
            return 0;
        }

        return $this->invalidRequest->getCount();
    }

    protected function setInvalidRequestCount(int $invalidRequestCount): void
    {
        if ($this->identifier === '') {
            // we can't save anything if there is no identifier given
            return;
        }

        $this->initRecord();

        if ($this->invalidRequest instanceof InvalidRequest) {
            if ($invalidRequestCount > 0) {
                if ($invalidRequestCount !== $this->invalidRequest->getCount()) {
                    $this->invalidRequest->setTstamp(new DateTime());
                    $this->invalidRequest->setCount($invalidRequestCount);
                    $this->invalidRequestRepository->update($this->invalidRequest);
                    $this->persistenceManager->persistAll();
                }
            } else {
                $this->invalidRequestRepository->remove($this->invalidRequest);
                $this->persistenceManager->persistAll();
            }
        } elseif ($invalidRequestCount > 0) {
            $invalidRequest = new InvalidRequest();
            $invalidRequest->setIdentifier($this->identifier);
            $invalidRequest->setTstamp(new DateTime());
            $invalidRequest->setCount($invalidRequestCount);
            $this->invalidRequestRepository->add($invalidRequest);
            $this->persistenceManager->persistAll();
        }
    }

    protected function init(ContextInterface $context): void
    {
        $this->settings = $this->globalConfiguration->get('dmf_collector_core')['botProtection'] ?? [];

        $ipAddress = $context->getIpAddress();
        $this->identifier = $ipAddress === '' ? '' : hash('md5', (string)$ipAddress);
    }

    protected function initRecord(): void
    {
        if (!$this->invalidRequest instanceof InvalidRequest) {
            $this->invalidRequest = null;
            if ($this->identifier !== '') {
                $this->invalidRequest = $this->invalidRequestRepository->findOneByIdentifier($this->identifier);
            }
        }
    }
}
