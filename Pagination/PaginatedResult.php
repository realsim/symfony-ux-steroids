<?php

namespace Symfony\UX\Steroids\Pagination;

use Traversable;
use Countable;
use IteratorAggregate;

interface PaginatedResult extends Countable, IteratorAggregate
{
    public function paginator(): Paginator;

    public function count(): int;
    public function totalCount(): int;

    public function getIterator(): Traversable;
}
