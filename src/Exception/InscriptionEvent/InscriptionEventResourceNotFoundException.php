<?php

declare(strict_types=1);

namespace App\Exception\InscriptionEvent;

use App\Exception\ResourceNotFoundException;

class InscriptionEventResourceNotFoundException extends ResourceNotFoundException
{
    protected const string RESOURCE = 'InscriptionEvent';
}
