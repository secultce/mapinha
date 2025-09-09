<?php

declare(strict_types=1);

namespace App\Resolver\ValueResolver;

use App\Request\Query\Filters;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final class FiltersValueResolver implements ValueResolverInterface
{
    public function resolve(Request $request, ArgumentMetadata $argument): array
    {
        $type = $argument->getType();

        if (Filters::class !== $type) {
            return [];
        }

        $filters = $request->query->all('filters');

        return [new Filters($filters)];
    }
}
