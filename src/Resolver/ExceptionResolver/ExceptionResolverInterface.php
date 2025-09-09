<?php

declare(strict_types=1);

namespace App\Resolver\ExceptionResolver;

use Exception;
use Symfony\Component\HttpFoundation\Request;

interface ExceptionResolverInterface
{
    public function resolve(Request $request, Exception $exception): array;
}
