<?php

declare(strict_types=1);

namespace App\Exception\CulturalLanguage;

use App\Exception\ResourceNotFoundException;

class CulturalLanguageResourceNotFoundException extends ResourceNotFoundException
{
    protected const string RESOURCE = 'CulturalLanguage';
}
