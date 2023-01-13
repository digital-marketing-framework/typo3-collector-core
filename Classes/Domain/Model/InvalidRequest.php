<?php

namespace DigitalMarketingFramework\Typo3\Collector\Core\Domain\Model;

use DateTime;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class InvalidRequest extends AbstractEntity
{
    protected DateTime $tstamp;
    protected string $identifier;
    protected int $count;

    public function __construct()
    {
        $this->pid = 0;
    }

    public function setTstamp(DateTime $tstamp): void
    {
        $this->tstamp = $tstamp;
    }

    public function getTstamp(): DateTime
    {
        return $this->tstamp;
    }

    public function isExpired(int $timeout = 60): bool
    {
        return $this->tstamp->getTimestamp() + $timeout < time();
    }

    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function setCount(int $count): void
    {
        $this->count = $count;
    }

    public function getCount(): int
    {
        return $this->count;
    }
}
