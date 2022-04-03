<?php

namespace Symfony\UX\Steroids\Controller\ArgumentResolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\UX\Steroids\Pagination\Paginator;

class PaginatorValueResolver implements ArgumentValueResolverInterface
{
    private int $defaultLimit;
    private int $maxLimit;
    private string $pageParam;
    private string $limitParam;

    public function __construct(int $defaultLimit, int $maxLimit, string $pageParam, string $limitParam)
    {
        $this->defaultLimit = $defaultLimit;
        $this->maxLimit = $maxLimit;
        $this->pageParam = $pageParam;
        $this->limitParam = $limitParam;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return is_a($argument->getType(), Paginator::class, true);
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        yield $this->buildPaginator($request);
    }

    private function buildPaginator(Request $request): Paginator
    {
        $currentPage = max(1, $request->query->getInt($this->pageParam));

        $limit = $request->query->getInt($this->limitParam, $this->defaultLimit);
        if ($limit > $this->maxLimit) {
            $limit = $this->maxLimit;
        }

        return new Paginator($limit, $currentPage, $this->pageParam, $this->limitParam);
    }
}
