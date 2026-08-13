<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Agent;
use App\Entity\AgentAddress;
use App\Repository\Interface\AddressRepositoryInterface;
use App\Service\Interface\AddressServiceInterface;
use App\Service\Interface\CityServiceInterface;
use Symfony\Component\Uid\Uuid;

readonly class AddressService implements AddressServiceInterface
{
    public function __construct(
        private AddressRepositoryInterface $addressRepository,
        private CityServiceInterface $cityService
    ) {
    }

    public function create(Agent $agent, array $addressData): void
    {
        $agentAddress = new AgentAddress();
        $agentAddress->setId(Uuid::v4());
        $agentAddress->setZipcode($addressData['zipcode']);
        $agentAddress->setStreet($addressData['street']);
        $agentAddress->setNumber($addressData['number']);
        $agentAddress->setNeighborhood($addressData['neighborhood']);

        if ($addressData['complement']) {
            $agentAddress->setComplement($addressData['complement']);
        }

        if ($addressData['cityId']) {
            $city = $this->cityService->get($addressData['cityId']);
            if ($city) {
                $agentAddress->setCity($city);
            }
        }

        $agentAddress->setOwner($agent);

        $this->addressRepository->save($agentAddress);
    }
}
