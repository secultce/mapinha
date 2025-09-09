<?php

declare(strict_types=1);

namespace App\DataFixtures\Entity;

use App\Entity\Address;
use App\Entity\AgentAddress;
use App\Entity\City;
use App\Entity\SpaceAddress;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

class AddressFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public const string ADDRESS_ID_PREFIX = 'address';
    public const string ADDRESS_ID_1 = 'b1b3eddd-3eac-4d96-97b5-1662767ae5f6';
    public const string ADDRESS_ID_2 = 'b8636a9e-3906-4751-b4a9-7a24995813aa';
    public const string ADDRESS_ID_3 = '425bdb7a-1ea2-41b5-bcb8-3511ef8f750a';
    public const string ADDRESS_ID_4 = 'fd64752a-c7ed-44ff-b092-44076dea4b4c';
    public const string ADDRESS_ID_5 = 'c4469910-ddd8-4dff-93dc-7ae5d9dc9ccc';
    public const string ADDRESS_ID_6 = 'eb8fe1b6-612b-4ad8-99c3-b40db0fb1bf4';
    public const string ADDRESS_ID_7 = '53cd0cdf-535b-4f66-90ed-ba6319ee67c6';
    public const string ADDRESS_ID_8 = '479b58bd-8764-4bcb-bcd3-200c66c6f4f6';
    public const string ADDRESS_ID_9 = '0ef8757b-1f72-4a94-8f77-07f4e5027b58';
    public const string ADDRESS_ID_10 = '4e0c4523-5dd3-4e0b-ba71-fc16cfdf9afa';
    public const string ADDRESS_ID_11 = 'a1b2c3d4-e5f6-7890-abcd-ef1234567890';
    public const string ADDRESS_ID_12 = 'b2c3d4e5-f6a7-8901-bcde-ff2345678901';
    public const string ADDRESS_ID_13 = 'c3d4e5f6-a7b8-9012-cdef-ff3456789012';
    public const string ADDRESS_ID_14 = 'd4e5f6a7-b8c9-0123-def0-ff4567890123';
    public const string ADDRESS_ID_15 = 'e5f6a7b8-c9d0-1234-ef01-ff5678901234';

    public const array ADDRESSES = [
        [
            'id' => self::ADDRESS_ID_1,
            'street' => 'Rua Doutor João Moreira',
            'number' => '540',
            'complement' => 'Complexo Estação das Artes',
            'neighborhood' => 'Centro',
            'zipCode' => '60030000',
            'city' => '97847c18-ac1c-4a00-93d4-b4a3e72a262c', // Fortaleza/CE
            'owner' => SpaceFixtures::SPACE_ID_1,
            'ownerType' => 'space',
        ],
        [
            'id' => self::ADDRESS_ID_2,
            'street' => 'Rua Vinte e Oito de Setembro',
            'number' => '250',
            'complement' => 'Apto 202',
            'neighborhood' => 'Centro',
            'zipCode' => '65010000',
            'city' => '60e37453-19a2-4ff8-bed7-f08e28d14f78', // São Luís/MA
            'owner' => SpaceFixtures::SPACE_ID_2,
            'ownerType' => 'space',
        ],
        [
            'id' => self::ADDRESS_ID_3,
            'street' => 'Quadra SQS 102',
            'number' => '5',
            'complement' => 'Bloco A',
            'neighborhood' => 'Asa Sul',
            'zipCode' => '70330000',
            'city' => 'f6005001-9abf-4295-8ab5-572d54ec1ba0', // Brasília/DF
            'owner' => SpaceFixtures::SPACE_ID_3,
            'ownerType' => 'space',
        ],
        [
            'id' => self::ADDRESS_ID_4,
            'street' => 'Avenida Paulista',
            'number' => '1245',
            'complement' => 'Conj. 1201',
            'neighborhood' => 'Bela Vista',
            'zipCode' => '01310300',
            'city' => '914b3de2-fed5-410d-bba8-837b9f13e422', // São Paulo/SP
            'owner' => SpaceFixtures::SPACE_ID_4,
            'ownerType' => 'space',
        ],
        [
            'id' => self::ADDRESS_ID_5,
            'street' => 'Praça General Osório',
            'number' => '123',
            'complement' => 'Sala 5',
            'neighborhood' => 'Centro',
            'zipCode' => '80020010',
            'city' => 'c52035af-d85e-4fec-aa5d-8798476782d7', // Curitiba/PR
            'owner' => SpaceFixtures::SPACE_ID_5,
            'ownerType' => 'space',
        ],
        [
            'id' => self::ADDRESS_ID_6,
            'street' => 'Vila Oliveira',
            'number' => '1230',
            'complement' => null,
            'neighborhood' => 'Cidade Velha',
            'zipCode' => '66023050',
            'city' => '1995f238-67e4-4114-861d-1ac54fafef80', // Belém/PA
            'owner' => SpaceFixtures::SPACE_ID_6,
            'ownerType' => 'space',
        ],
        [
            'id' => self::ADDRESS_ID_7,
            'street' => 'Avenida Barnabé',
            'number' => '456',
            'complement' => 'Loja 2',
            'neighborhood' => 'Itaigara',
            'zipCode' => '41815420',
            'city' => 'ae05a7ef-b4c8-448d-b8ba-414cf9b38ecd', // Salvador/BA
            'owner' => SpaceFixtures::SPACE_ID_7,
            'ownerType' => 'space',
        ],
        [
            'id' => self::ADDRESS_ID_8,
            'street' => 'Avenida Fued José Sebba',
            'number' => '1184',
            'complement' => 'PUC Goiás - Câmpus V',
            'neighborhood' => 'Jardim Goiás',
            'zipCode' => '74805100',
            'city' => 'c1f0d7a0-64aa-486e-a3f5-4b29e0d01ef7', // Goiânia/GO
            'owner' => SpaceFixtures::SPACE_ID_8,
            'ownerType' => 'space',
        ],
        [
            'id' => self::ADDRESS_ID_9,
            'street' => 'Rua da Quitanda',
            'number' => '50',
            'complement' => null,
            'neighborhood' => 'Centro',
            'zipCode' => '20091005',
            'city' => '75f95b37-00f1-4a75-bac5-8eb15a46c6f3', // Rio de Janeiro/RJ
            'owner' => SpaceFixtures::SPACE_ID_9,
            'ownerType' => 'space',
        ],
        [
            'id' => self::ADDRESS_ID_10,
            'street' => 'Avenida Borges de Medeiros',
            'number' => '1565',
            'complement' => 'Sala 10',
            'neighborhood' => 'Praia de Belas',
            'zipCode' => '90110906',
            'city' => 'fb22ee01-1806-481f-af83-7deb980c89c3', // Porto Alegre/RS
            'owner' => SpaceFixtures::SPACE_ID_10,
            'ownerType' => 'space',
        ],
        [
            'id' => self::ADDRESS_ID_11,
            'street' => 'Avenida Epaminondas Jácome',
            'number' => '3126',
            'complement' => 'Casa',
            'neighborhood' => 'Base',
            'zipCode' => '69900034',
            'city' => '99ce2c19-78e4-4e25-bc33-4b69cc7603bd', // Rio Branco/AC (Norte)
            'owner' => AgentFixtures::AGENT_ID_1,
            'ownerType' => 'agent',
        ],
        [
            'id' => self::ADDRESS_ID_12,
            'street' => 'Rua dos Navegantes',
            'number' => '1205',
            'complement' => 'Apto 3',
            'neighborhood' => 'Boa Viagem',
            'zipCode' => '51020010',
            'city' => 'a97c7beb-9476-4347-ad2a-b60aaa58abd5', // Recife/PE (Nordeste)
            'owner' => AgentFixtures::AGENT_ID_3,
            'ownerType' => 'agent',
        ],
        [
            'id' => self::ADDRESS_ID_13,
            'street' => 'Rua 9',
            'number' => '303',
            'complement' => 'Sala 12',
            'neighborhood' => 'Setor Oeste',
            'zipCode' => '74120010',
            'city' => 'c1f0d7a0-64aa-486e-a3f5-4b29e0d01ef7', // Goiânia/GO (Centro-Oeste)
            'owner' => AgentFixtures::AGENT_ID_5,
            'ownerType' => 'agent',
        ],
        [
            'id' => self::ADDRESS_ID_14,
            'street' => 'Rua Augusta',
            'number' => '404',
            'complement' => 'Cobertura',
            'neighborhood' => 'Consolação',
            'zipCode' => '01305000',
            'city' => '914b3de2-fed5-410d-bba8-837b9f13e422', // São Paulo/SP (Sudeste)
            'owner' => AgentFixtures::AGENT_ID_7,
            'ownerType' => 'agent',
        ],
        [
            'id' => self::ADDRESS_ID_15,
            'street' => 'Largo Jornalista Glênio Peres',
            'number' => '',
            'complement' => null,
            'neighborhood' => 'Centro Histórico',
            'zipCode' => '90020050',
            'city' => 'fb22ee01-1806-481f-af83-7deb980c89c3', // Porto Alegre/RS (Sul)
            'owner' => AgentFixtures::AGENT_ID_9,
            'ownerType' => 'agent',
        ],
    ];

    public const array UPDATED_ADDRESSES = [
        [
            'id' => self::ADDRESS_ID_11,
            'street' => 'Avenida Epaminondas Jácome',
            'number' => '',
            'complement' => 'de 3122 ao fim - lado par',
            'neighborhood' => 'Base',
            'zipCode' => '69900034',
            'city' => '99ce2c19-78e4-4e25-bc33-4b69cc7603bd',
            'owner' => AgentFixtures::AGENT_ID_1,
            'ownerType' => 'agent',
        ],
        [
            'id' => self::ADDRESS_ID_4,
            'street' => 'Rua dos Andradas',
            'number' => '896',
            'complement' => 'de 0835 a 0999 - lado ímpar',
            'neighborhood' => 'Centro Histórico',
            'zipCode' => '90020005',
            'city' => 'fb22ee01-1806-481f-af83-7deb980c89c3',
            'owner' => SpaceFixtures::SPACE_ID_4,
            'ownerType' => 'space',
        ],
    ];

    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected TokenStorageInterface $tokenStorage,
        private readonly SerializerInterface $serializer,
    ) {
        parent::__construct($entityManager, $tokenStorage);
    }

    public function getDependencies(): array
    {
        return [
            AgentFixtures::class,
            SpaceFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $this->createAddresses($manager);
        $this->updateAddresses($manager);
    }

    private function createAddresses(ObjectManager $manager): void
    {
        foreach (self::ADDRESSES as $addressData) {
            $address = $this->mountAddress($addressData);

            $this->addReference(sprintf('%s-%s', self::ADDRESS_ID_PREFIX, $addressData['id']), $address);

            $manager->persist($address);
        }

        $manager->flush();
    }

    private function updateAddresses(ObjectManager $manager): void
    {
        foreach (self::UPDATED_ADDRESSES as $addressData) {
            $className = match ($addressData['ownerType']) {
                'agent' => AgentAddress::class,
                'space' => SpaceAddress::class,
            };

            $addressObj = $this->getReference(sprintf('%s-%s', self::ADDRESS_ID_PREFIX, $addressData['id']), $className);
            $address = $this->mountAddress($addressData, ['object_to_populate' => $addressObj]);

            $manager->persist($address);
        }

        $manager->flush();
    }

    public function mountAddress(array $addressData, array $context = []): Address
    {
        $address = $this->serializer->denormalize($addressData, Address::class, context: $context);
        $address->setCity($this->entityManager->getRepository(City::class)->find($addressData['city']));

        return $address;
    }
}
