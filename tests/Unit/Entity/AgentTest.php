<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Agent;
use App\Entity\AgentAddress;
use App\Entity\Organization;
use App\Entity\Seal;
use App\Entity\User;
use App\Enum\SocialNetworkEnum;
use App\Helper\DateFormatHelper;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use ReflectionObject;
use Symfony\Component\Uid\Uuid;

class AgentTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $agent = new Agent();

        $uuid = Uuid::v4();
        $agent->setId($uuid);
        $this->assertSame($uuid, $agent->getId());

        $agent->setName('Test Agent');
        $this->assertSame('Test Agent', $agent->getName());

        $agent->setImage('http://example.com/image.jpg');
        $this->assertSame('http://example.com/image.jpg', $agent->getImage());

        $agent->setShortBio('Short biography');
        $this->assertSame('Short biography', $agent->getShortBio());

        $agent->setLongBio('Long biography description');
        $this->assertSame('Long biography description', $agent->getLongBio());

        $agent->setCulture(true);
        $this->assertTrue($agent->isCulture());

        $agent->setMain(true);
        $this->assertTrue($agent->isMain());

        $extraFields = ['field1' => 'value1'];
        $agent->setExtraFields($extraFields);
        $this->assertSame($extraFields, $agent->getExtraFields());

        $socialNetworks = [
            SocialNetworkEnum::INSTAGRAM->value => 'hello',
        ];
        $agent->setSocialNetworks($socialNetworks);
        $this->assertSame($socialNetworks, $agent->getSocialNetworks());
        $agent->removeSocialNetwork(SocialNetworkEnum::INSTAGRAM);
        $agent->addSocialNetwork(SocialNetworkEnum::INSTAGRAM->value, 'hello');
        $this->assertSame($socialNetworks, $agent->getSocialNetworks());

        $user = $this->createMock(User::class);
        $agent->setUser($user);
        $this->assertSame($user, $agent->getUser());
    }

    public function testCollectionsMethods(): void
    {
        $agent = new Agent();
        $refObject = new ReflectionObject($agent);
        $prop = $refObject->getProperty('addresses');
        $prop->setAccessible(true);
        $prop->setValue($agent, new ArrayCollection());

        $organization = $this->createMock(Organization::class);
        $agent->addOrganization($organization);
        $this->assertTrue($agent->getOrganizations()->contains($organization));

        $seal = $this->createMock(Seal::class);
        $agent->addSeal($seal);
        $this->assertTrue($agent->getSeals()->contains($seal));

        $address = $this->createMock(AgentAddress::class);
        $agent->addAddress($address);
        $this->assertTrue($agent->getAddresses()->contains($address));

        $agent->removeAddress($address);
        $this->assertFalse($agent->getAddresses()->contains($address));

        $this->assertInstanceOf(ArrayCollection::class, $agent->getOpportunities());
    }

    public function testToArrayMethod(): void
    {
        $agent = new Agent();
        $uuid = Uuid::v4();
        $agent->setId($uuid);
        $agent->setName('Test Agent');
        $agent->setImage('http://example.com/image.jpg');
        $agent->setShortBio('Short biography');
        $agent->setLongBio('Long biography description');
        $agent->setCulture(true);
        $agent->setExtraFields(['field1' => 'value1']);

        $organization1 = $this->createMock(Organization::class);
        $organization2 = $this->createMock(Organization::class);

        $organization1Uuid = Uuid::v4();
        $organization2Uuid = Uuid::v4();

        $organization1->method('getId')->willReturn($organization1Uuid);
        $organization2->method('getId')->willReturn($organization2Uuid);

        $agent->addOrganization($organization1);
        $agent->addOrganization($organization2);

        $agent->addSocialNetwork(SocialNetworkEnum::INSTAGRAM->value, 'hello');

        $createdAt = new DateTimeImmutable('2024-01-01 10:00:00');
        $agent->setCreatedAt($createdAt);

        $expectedArray = [
            'id' => $uuid->toRfc4122(),
            'name' => 'Test Agent',
            'image' => 'http://example.com/image.jpg',
            'shortBio' => 'Short biography',
            'longBio' => 'Long biography description',
            'culture' => true,
            'extraFields' => ['field1' => 'value1'],
            'organizations' => [
                $organization1Uuid->toRfc4122(),
                $organization2Uuid->toRfc4122(),
            ],
            'culturalFunction' => [],
            'socialNetworks' => [
                SocialNetworkEnum::INSTAGRAM->value => 'hello',
            ],
            'createdAt' => $createdAt->format(DateFormatHelper::DEFAULT_FORMAT),
            'updatedAt' => null,
            'deletedAt' => null,
        ];

        $actualArray = $agent->toArray();

        $this->assertSame($expectedArray, $actualArray);
    }
}
