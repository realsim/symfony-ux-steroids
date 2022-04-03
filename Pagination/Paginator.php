<?php

namespace Symfony\UX\Steroids\Pagination;

use function sprintf;
use function trigger_error;

final class Paginator
{
    private const MAX_LIMIT_ON_PAGE = 100;

    private int $limitOnPage;
    private int $currentPage;
    private string $pageParameter;
    private string $limitParameter;

    public static function firstResults(int $resultsLimit): self
    {
        return new self($resultsLimit, 1);
    }

    public function __construct(int $limitOnPage, int $currentPage, string $pageParameter = 'page', string $limitParameter = 'limit')
    {
        $this->setLimitOnPage($limitOnPage);
        $this->setCurrentPage($currentPage);
        $this->pageParameter = $pageParameter;
        $this->limitParameter = $limitParameter;
    }

    private function setLimitOnPage(int $limitOnPage): void
    {
        if ($limitOnPage < 1 || $limitOnPage > self::MAX_LIMIT_ON_PAGE) {
            trigger_error(sprintf('Invalid limit on page value %d.', $limitOnPage), E_USER_NOTICE);

            $limitOnPage = self::MAX_LIMIT_ON_PAGE;
        }
        $this->limitOnPage = $limitOnPage;
    }

    private function setCurrentPage(int $currentPage): void
    {
        if ($currentPage < 1) {
            trigger_error(sprintf('Invalid current page value %d.', $currentPage), E_USER_NOTICE);

            $currentPage = 1;
        }
        $this->currentPage = $currentPage;
    }

    public function limitOnPage(): int
    {
        return $this->limitOnPage;
    }

    public function currentPage(): int
    {
        return $this->currentPage;
    }

    public function currentOffset(): int
    {
        return ($this->currentPage - 1) * $this->limitOnPage;
    }

    public function pageParameter(): string
    {
        return $this->pageParameter;
    }
}
