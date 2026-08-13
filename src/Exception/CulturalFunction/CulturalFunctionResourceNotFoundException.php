<?php

declare(strict_types=1);

namespace App\Exception\CulturalFunction;

use App\Exception\ResourceNotFoundException;

class CulturalFunctionResourceNotFoundException extends ResourceNotFoundException
{
    protected const string RESOURCE = 'CulturalFunction';
}
