<?php

namespace Symfony\UX\Steroids\Pagination\Attribute;

#[\Attribute(\Attribute::TARGET_PARAMETER)]
class Paginate
{
    public int $onPage;
    public ?string $pageParameter;
    public ?string $limitParameter;

    public function __construct(int $onPage, ?string $pageParameter = null, ?string $limitParameter = null)
    {
        $this->onPage = $onPage;
        $this->pageParameter = $pageParameter;
        $this->limitParameter = $limitParameter;
    }
}
