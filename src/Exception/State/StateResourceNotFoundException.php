<?php

declare(strict_types=1);

namespace App\Exception\State;

use App\Exception\ResourceNotFoundException;

class StateResourceNotFoundException extends ResourceNotFoundException
{
    protected const string RESOURCE = 'State';
}
