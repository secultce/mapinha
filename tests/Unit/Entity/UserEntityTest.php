<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Agent;
use App\Entity\User;
use App\Enum\UserStatusEnum;
use App\Tests\AbstractApiTestCase;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Uid\Uuid;

class UserEntityTest extends AbstractApiTestCase
{
    public function testGettersAndSettersFromUserEntityShouldBeSuccessful(): void
    {
        $user = new User();

        $this->assertCount(0, $user->getAgents());
        $this->assertNull($user->getLastLogin());
        $this->assertFalse($user->isActive());

        $id = Uuid::v4();
        $firstname = 'Fulano';
        $lastname = 'Ditau';
        $name = $firstname.' '.$lastname;
        $socialName = 'Belcrano Dilá';
        $email = 'fulano.ditau@email.com';
        $image = 'fulano_profile.jpg';
        $agent = new Agent();
        $agents = new ArrayCollection([new Agent(), new Agent()]);
        $createdAt = new DateTimeImmutable();
        $deletedAt = new DateTime();
        $updatedAt = new DateTime();

        $user->setId($id);
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setSocialName($socialName);
        $user->setEmail($email);
        $user->setImage($image);
        $user->setAgents($agents);
        $user->setCreatedAt($createdAt);
        $user->setUpdatedAt($updatedAt);
        $user->setDeletedAt($deletedAt);

        $this->assertEquals($name, $user->getName());
        $this->assertIsString($user->getName());

        $this->assertEquals($socialName, $user->getSocialName());
        $this->assertIsString($user->getSocialName());

        $this->assertEquals($email, $user->getEmail());
        $this->assertIsString($user->getEmail());

        $this->assertEquals($image, $user->getImage());
        $this->assertIsString($user->getImage());

        $this->assertEquals($agents, $user->getAgents());
        $this->assertInstanceOf(ArrayCollection::class, $user->getAgents());

        $user->setAgents(new ArrayCollection());
        $user->addAgent($agent);

        $this->assertTrue($user->getAgents()->contains($agent));
        $this->assertInstanceOf(Agent::class, $user->getAgents()->first());

        $this->assertEquals($createdAt, $user->getCreatedAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $user->getCreatedAt());

        $this->assertEquals($deletedAt, $user->getDeletedAt());
        $this->assertInstanceOf(DateTime::class, $user->getDeletedAt());

        $this->assertFalse($user->isActive());
        $this->assertEquals($updatedAt, $user->getLastLogin());

        $this->assertEquals([
            'id' => $id->toRfc4122(),
            'firstname' => $firstname,
            'lastname' => $lastname,
            'socialName' => $socialName,
            'email' => $email,
            'image' => $image,
            'status' => UserStatusEnum::AWAITING_CONFIRMATION->value,
            'createdAt' => $createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $updatedAt->format('Y-m-d H:i:s'),
            'deletedAt' => $deletedAt->format('Y-m-d H:i:s'),
        ], $user->toArray());
    }

    public function testAddDuplicateAgentShouldNotBeAdded(): void
    {
        $user = new User();
        $agent = new Agent();

        $user->addAgent($agent);
        $user->addAgent($agent);

        $this->assertCount(1, $user->getAgents());
    }
}
