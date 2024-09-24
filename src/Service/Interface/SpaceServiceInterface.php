<?php

declare(strict_types=1);

namespace App\Service\Interface;

use App\Entity\Space;
use Symfony\Component\Uid\Uuid;

interface SpaceServiceInterface
{
    public function create(array $space): Space;

    public function get(Uuid $id): Space;

    public function list(): array;

    public function remove(Uuid $id): void;

    public function update(Uuid $identifier, array $space): Space;
}
